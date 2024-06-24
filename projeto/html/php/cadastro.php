<?php
include_once('config.php');

// Variáveis para armazenar os valores dos campos após o envio do formulário
$productCategory = isset($_POST['productCategory']) ? $_POST['productCategory'] : '';
$nomeValue = isset($_POST['productName']) ? $_POST['productName'] : '';
$precoCustoValue = isset($_POST['productPriceCost']) ? $_POST['productPriceCost'] : '';
$precoVendaValue = isset($_POST['productPrice']) ? $_POST['productPrice'] : '';
$quantidadeValue = isset($_POST['productQuantity']) ? $_POST['productQuantity'] : '';
$descricaoValue = isset($_POST['productDescription']) ? $_POST['productDescription'] : '';
$fornecedorValue = isset($_POST['productSupplier']) ? $_POST['productSupplier'] : '';

// Consulta para obter categorias
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

// Consulta para obter fornecedores
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


$errorMessage = '';
$successMessage = '';

// Processamento do formulário quando submetido
if (isset($_POST['submit'])) {
    $categoria = $_POST['productCategory'];
    $nome = $_POST['productName'];
    $precoCusto = $_POST['productPriceCost']; 
    $precoVenda = $_POST['productPrice'];
    $quantidade = $_POST['productQuantity'];
    $descricao = $_POST['productDescription'];
    $fornecedor = $_POST['productSupplier'];

    // Validar entrada
    if (empty($categoria)) {
        $errorMessage .= "A categoria não pode ser vazia.<br>";
    }

    if (empty($nome)) {
        $errorMessage .= "O nome do produto não pode ser vazio.<br>";
    } elseif (strlen($nome) > 50) {
        $errorMessage .= "O nome do produto não pode exceder 50 caracteres.<br>";
    } elseif (strlen($nome) < 3) {
        $errorMessage .= "O nome do produto deve ter pelo menos 3 caracteres.<br>";
    }

    if (empty($precoCusto)) {
        $errorMessage .= "O preço de custo não pode ser vazio.<br>";
    } elseif (!is_numeric($precoCusto) || $precoCusto <= 0) {
        $errorMessage .= "O preço de custo deve ser um número positivo.<br>";
    }

    if (empty($precoVenda)) {
        $errorMessage .= "O preço de venda não pode ser vazio.<br>";
    } elseif (!is_numeric($precoVenda) || $precoVenda <= 0) {
        $errorMessage .= "O preço de venda deve ser um número positivo.<br>";
    } elseif ($precoVenda <= $precoCusto) {
        $errorMessage .= "O preço de venda deve ser superior ao preço de custo.<br>";
    }

    if (empty($quantidade)) {
        $errorMessage .= "A quantidade não pode ser vazia.<br>";
    } elseif (!is_numeric($quantidade) || intval($quantidade) < 0) {
        $errorMessage .= "A quantidade deve ser um número inteiro não negativo.<br>";
    }

    if (empty($descricao)) {
        $errorMessage .= "A descrição não pode ser vazia.<br>";
    } elseif (strlen($descricao) > 100) {
        $errorMessage .= "A descrição não pode exceder 100 caracteres.<br>";
    } elseif (strlen($descricao) < 10) {
        $errorMessage .= "A descrição deve ter pelo menos 10 caracteres.<br>";
    }

    if (empty($fornecedor)) {
        $errorMessage .= "O fornecedor não pode ser vazio.<br>";
    }

    // Se não houver erros de validação, proceder com a inserção
    if (empty($errorMessage)) {
        // Verificação se o produto já existe
        $sql_check_product = "SELECT * FROM products WHERE nome = ?";
        $stmt_check_product = mysqli_prepare($conexao, $sql_check_product);
        mysqli_stmt_bind_param($stmt_check_product, "s", $nome);
        mysqli_stmt_execute($stmt_check_product);
        mysqli_stmt_store_result($stmt_check_product);

        if (mysqli_stmt_num_rows($stmt_check_product) > 0) {
            $errorMessage = "Este produto já está cadastrado!";
        } else {
            // Inserção do produto utilizando prepared statement
            $sql_insert_product = "INSERT INTO products (nome, categoria, precoCusto, precoVenda, quantidade, descricao, fornecedor) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert_product = mysqli_prepare($conexao, $sql_insert_product);
            mysqli_stmt_bind_param($stmt_insert_product, "ssddiss", $nome, $categoria, $precoCusto, $precoVenda, $quantidade, $descricao, $fornecedor);
            
            if (mysqli_stmt_execute($stmt_insert_product)) {
                $successMessage = "Produto cadastrado com sucesso!";
                $nomeValue = $precoCustoValue = $precoVendaValue = $quantidadeValue = $descricaoValue = $fornecedorValue = '';
            } else {
                $errorMessage = "Erro ao inserir os dados na tabela de produtos.";
            }

            // Fechar statement de inserção
            mysqli_stmt_close($stmt_insert_product);
        }

        // Fechar statement de verificação
        mysqli_stmt_close($stmt_check_product);
    }
}

// Fechar conexão
mysqli_close($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container">
    <section class="header">
        <h2>Cadastro de Produtos</h2>
    </section>
    <form action="cadastro.php" method="POST" id="productForm" class="form">
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
                <label for="productName"><i class="bi bi-tag"></i> Nome do Produto</label>
                <input type="text" id="productName" name="productName" placeholder="Digite o nome do produto" required maxlength="100" value="<?php echo htmlspecialchars($nomeValue); ?>"/>
            </div>
            <div class="form-content">
                <label for="productPriceCost"><i class="bi bi-cash"></i>Preço de Custo</label>
                <input type="number" id="productPriceCost" name="productPriceCost" placeholder="Digite o preço de Custo do produto" min="1" step="0.01" required value="<?php echo $precoCustoValue; ?>"/>
            </div>
            <div class="form-content">
                <label for="productPrice"><i class="bi bi-cash"></i>Preço de Venda</label>
                <input type="number" id="productPrice" name="productPrice" placeholder="Digite o preço de venda do produto" min="1" step="0.01" required value="<?php echo $precoVendaValue; ?>"/>
            </div>
            <div class="form-content">
                <label for="productQuantity"><i class="bi bi-box"></i>Quantidade</label>
                <input type="number" id="productQuantity" name="productQuantity" placeholder="Quantidade" min="1" step="1" required value="<?php echo $quantidadeValue; ?>"/>
            </div>
            <div class="form-content">
                <label for="productDescription"><i class="bi bi-file-text"></i>Descrição</label>
                <input type="text" id="productDescription" name="productDescription" placeholder="Descrição" maxlength="255" value="<?php echo htmlspecialchars($descricaoValue); ?>"/>
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
            <button type="submit" name="submit" id="submit" value="1"><i class="bi bi-plus-circle"></i>Cadastrar produto</button>
            <?php if (!empty($errorMessage)): ?>
                <div class="mensagem erro">
                    <?php echo $errorMessage; ?>
                </div>
                <script>
                    setTimeout(function() {
                        document.querySelector('.mensagem.erro').style.display = 'none';
                    }, 3000);
                </script>
            <?php elseif (!empty($successMessage)): ?>
                <div class="mensagem sucesso">
                    <?php echo $successMessage; ?>
                </div>
                <script>
                    setTimeout(function() {
                        document.querySelector('.mensagem.sucesso').style.display = 'none';
                    }, 3000);
                </script>
            <?php endif; ?>
        </form>
        <nav class="menu-lateral">
            <div>
                <div class="btn-expandir">
                    <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" class="btn btn-outline-light">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
                <ul>
                    <li class="item-menu">
                        <a href="#" onclick="redirecionar('home.php')";>
                            <span class="icon"><i class="bi bi-house-door"></i></span>
                            <span class="                        txt-link">Início</span>
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
        <script>
            function redirecionar(url) {
                window.location.href = url;
            }
        </script>
    </div>
</body>
</html>

