<?php

include_once('config.php');

// Consulta para obter produtos existentes
$sql_select_produtos = "SELECT nome, categoria, precoCusto, precoVenda, fornecedor, descricao FROM products";
$resultado_produtos = mysqli_query($conexao, $sql_select_produtos);
$produtos = array();

if ($resultado_produtos === false) {
    die("Erro na consulta de produtos: " . mysqli_error($conexao));
} else {
    if (mysqli_num_rows($resultado_produtos) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado_produtos)) {
            $produtos[] = $linha['nome'];
        }
    }
}

// Consulta para obter categorias existentes
$sql_select_categorias = "SELECT categoria FROM categorias"; 
$resultado_categorias = mysqli_query($conexao, $sql_select_categorias);
$categorias = array();

if ($resultado_categorias === false) {
    die("Erro na consulta de categorias: " . mysqli_error($conexao));
} else {
    if (mysqli_num_rows($resultado_categorias) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado_categorias)) {
            $categorias[] = $linha['categoria'];
        }
    }
}

// Consulta para obter fornecedores existentes
$sql_select_fornecedores = "SELECT nome FROM fornecedor";
$resultado_fornecedores = mysqli_query($conexao, $sql_select_fornecedores);
$fornecedores = array();

if ($resultado_fornecedores === false) {
    die("Erro na consulta de fornecedores: " . mysqli_error($conexao));
} else {
    if (mysqli_num_rows($resultado_fornecedores) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado_fornecedores)) {
            $fornecedores[] = $linha['nome'];
        }
    }
}

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["produto"]) && isset($_POST["quantidade"]) && isset($_POST["productPriceCost"]) && isset($_POST["productPrice"]) && isset($_POST["productSupplier"]) && isset($_POST["productCategory"]) && isset($_POST["productDescription"])) {
        $produto = mysqli_real_escape_string($conexao, $_POST["produto"]);
        $nova_quantidade = (int)$_POST["quantidade"];
        $preco_custo = (float)$_POST["productPriceCost"];
        $preco_venda = (float)$_POST["productPrice"];
        $fornecedor = mysqli_real_escape_string($conexao, $_POST["productSupplier"]);
        $categoria = mysqli_real_escape_string($conexao, $_POST["productCategory"]);
        $descricao = mysqli_real_escape_string($conexao, $_POST["productDescription"]);

        // Validação do preço
        if ($preco_custo < 0 || $preco_venda < 0) {
            $errorMessage = "O preço de custo e o preço de venda não podem ser negativos.";
        } elseif ($preco_custo >= $preco_venda) {
            $errorMessage = "O preço de custo não pode ser maior ou igual ao preço de venda.";
        } elseif (strlen($descricao) > 100) {
            $errorMessage = "A descrição não pode ter mais de 50 caracteres.";
        } else {
            // Consulta para verificar produto existente com preço de custo, preço de venda e fornecedor
            $sql_verificar_produto = "SELECT * FROM products WHERE nome = '$produto' AND precoCusto = $preco_custo AND precoVenda = $preco_venda AND fornecedor = '$fornecedor'";
            $resultado_verificar = mysqli_query($conexao, $sql_verificar_produto);

            if (mysqli_num_rows($resultado_verificar) > 0) {
                // Atualizar a quantidade do produto existente
                $sql_atualizar_quantidade = "UPDATE products SET quantidade = quantidade + $nova_quantidade WHERE nome = '$produto' AND precoCusto = $preco_custo AND precoVenda = $preco_venda AND fornecedor = '$fornecedor'";
                if (mysqli_query($conexao, $sql_atualizar_quantidade)) {
                    $successMessage = "Quantidade do produto '$produto' atualizada com sucesso.";
                } else {
                    $errorMessage = "Erro ao atualizar a quantidade do produto: " . mysqli_error($conexao);
                }
            } else {
                // Inserir novo produto com descrição
                $sql_inserir_produto = "INSERT INTO products (nome, quantidade, precoCusto, precoVenda, fornecedor, categoria, descricao) VALUES ('$produto', $nova_quantidade, $preco_custo, $preco_venda, '$fornecedor', '$categoria', '$descricao')";
                if (mysqli_query($conexao, $sql_inserir_produto)) {
                    $successMessage = "Novo produto '$produto' adicionado com sucesso.";
                } else {
                    $errorMessage = "Erro ao adicionar o novo produto: " . mysqli_error($conexao);
                }
            }
        }
    } else {
        $errorMessage = "Dados não recebidos. ";
    }
}

mysqli_close($conexao);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada de Produtos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/entradaEstoque.css">
</head>
<body>
<nav class="menu-lateral">
    <div>
        <div class="btn-expandir">
            <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"  class="btn btn-outline-light">
                <i class="bi bi-list"></i>
            </button>
        </div>
        <ul>
            <li class="item-menu">
                <a href="#" onclick="redirecionar('home.php')";>
                    <span class="icon"><i class="bi bi-house-door"></i></span>
                    <span class="txt-link">Início</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="#" onclick="redirecionar('cadastro.php');">
                    <span class="icon"><i class="bi bi-plus-circle"></i></span>
                    <span class="txt-link">Cadastro</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="#" onclick="redirecionar('saidaEstoque.php');">
                    <span class="icon"><i class="bi bi-arrow-down-circle"></i></span>
                    <span class="txt-link">Saída</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="#" onclick="redirecionar('entradaEstoque.php');">
                    <span class="icon"><i class="bi bi-archive"></i></span>
                    <span class="txt-link">Entrada</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="#" onclick="redirecionar('cadastroFornecedor.php');">
                    <span class="icon"><i class="bi bi-truck"></i></span>
                    <span class="txt-link">Cadastro Fornecedor</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="#" onclick="redirecionar('listaFornecedores.php');">
                    <span class="icon"><i class="bi bi-clipboard"></i></span>
                    <span class="txt-link">Fornecedores</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="#" onclick="redirecionar('cadastroCategorias.php')">
                    <span class="icon"><i class="bi bi-plus"></i></span>
                    <span class="txt-link">Cadastrar Categoria</span>
                </a>
            </li>
        </ul>
    </div>
    <div>
        <ul>
            <li class="item-menu">
                <a href="#" onclick="redirecionar('index.php');">
                    <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="txt-link">Sair</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="header">
        <h2>Entrada de Produtos</h2>
    </div>
    <div class="form">
        <form id="formEntrada" method="post" action="entradaEstoque.php">
            <div class="form-content">
                <label for="productCategory">Categoria</label>
                <select id="productCategory" name="productCategory" required>
                    <option value="">Selecione uma Categoria</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria; ?>"><?php echo $categoria; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-content">
                <label for="nome_produto">Nome do Produto</label>
                <select id="nome_produto" name="produto" required>
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $produto): ?>
                        <option value="<?php echo $produto; ?>"><?php echo $produto; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-content">
                <label for="productPriceCost"><i class="bi bi-cash"></i>Preço de Custo</label>
                <input type="number" id="productPriceCost" name="productPriceCost" placeholder="Digite o preço de Custo do produto" min="1" step="0.01" required value="<?php echo isset($_POST["productPriceCost"]) ? $_POST["productPriceCost"] : ''; ?>"/>
            </div>
            <div class="form-content">
                <label for="productPrice"><i class="bi bi-cash"></i>Preço de Venda</label>
                <input type="number" id="productPrice" name="productPrice" placeholder="Digite o preço de venda do produto" min="1" step="0.01" required value="<?php echo isset($_POST["productPrice"]) ? $_POST["productPrice"] : ''; ?>"/>
            </div>
            <div class="form-content">
                <label for="quantidade">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" placeholder="Informe uma quantidade a ser adicionada" min="1" required>
            </div>
            <div class="form-content">
                <label for="productDescription"><i class="bi bi-file-text"></i>Descrição</label>
                <input type="text" id="productDescription" name="productDescription" placeholder="Descrição" value="<?php echo isset($_POST["productDescription"]) ? $_POST["productDescription"] : ''; ?>"/>
            </div>
            <div class="form-content">
                <label for="productSupplier">Fornecedor</label>
                <select id="productSupplier" name="productSupplier" required>
                    <option value="">Selecione um fornecedor</option>
                    <?php foreach ($fornecedores as $fornecedor): ?>
                        <option value="<?php echo $fornecedor; ?>"><?php echo $fornecedor; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-submit">Adicionar Produto</button>
        </form>
        <?php if (!empty($errorMessage)): ?>
            <div class="mensagem erro" id="ErrorMessage">
                <?php echo $errorMessage; ?>
            </div>
        <?php elseif (!empty($successMessage)): ?>
            <div class="mensagem sucesso" id="SuccessMessage">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function redirecionar(url) {
        window.location.href = url;
    }

    function carregarProdutos() {
        const produtos = <?php echo json_encode($produtos); ?>;
        const select = document.getElementById('nome_produto');
        // Limpar opções existentes
        select.innerHTML = '<option value="">Selecione um produto</option>';
        // Adicionar novas opções
        produtos.forEach(produto => {
            const option = document.createElement('option');
            option.value = produto;
            option.textContent = produto;
            select.appendChild(option);
        });
    }

    document.getElementById('nome_produto').addEventListener('focus', carregarProdutos);

    // Script para ocultar a mensagem
    window.onload = function() {
        const errorMessage = document.getElementById('ErrorMessage');
        const successMessage = document.getElementById('SuccessMessage');
        if (errorMessage || successMessage) {
            setTimeout(() => {
                if (errorMessage) errorMessage.style.opacity = '0';
                if (successMessage) successMessage.style.opacity = '0';
            }, 3000); 
        }
    };
</script>
</body>
</html>
