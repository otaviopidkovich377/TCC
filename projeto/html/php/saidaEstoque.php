<?php

include_once('config.php');

$errors = array();
$success = false;

// Verificar se há uma ação específica a ser executada (como buscar o nome do produto)
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'get_product_name' && isset($_GET['idproducts'])) {
        $idProduto = $_GET['idproducts'];
        
        // Consulta SQL para buscar o nome do produto pelo idproducts
        $sql_nome_produto = "SELECT nome FROM products WHERE idproducts = '$idProduto'";
        $resultado_nome_produto = mysqli_query($conexao, $sql_nome_produto);

        if ($resultado_nome_produto) {
            if (mysqli_num_rows($resultado_nome_produto) > 0) {
                $row = mysqli_fetch_assoc($resultado_nome_produto);
                $nomeproduto = $row['nome'];

                // Retornar o nome do produto como resposta em formato JSON
                echo json_encode(array('nome' => $nomeproduto));
                exit;
            } else {
                // Se não encontrar o produto, retornar uma mensagem de erro
                echo json_encode(array('error' => 'Produto não encontrado.'));
                exit;
            }
        } else {
            // Se houver erro na consulta, retornar uma mensagem de erro
            echo json_encode(array('error' => 'Erro ao buscar o nome do produto: ' . mysqli_error($conexao)));
            exit;
        }
    }
}

// Consulta SQL para selecionar todos os produtos
$sql_select_produtos = "SELECT idproducts, nome FROM products";
$resultado_produtos = mysqli_query($conexao, $sql_select_produtos);

// Array para armazenar os produtos
$produtos = array();

// Verificar se a consulta de produtos foi bem-sucedida
if ($resultado_produtos === false) {
    die("Erro na consulta de produtos: " . mysqli_error($conexao));
} else {
    // Se houver resultados, armazenar os produtos no array
    if (mysqli_num_rows($resultado_produtos) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado_produtos)) {
            $produtos[] = $linha;
        }
    }
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar os dados do formulário
    $idproduto = $_POST['idproducts'];
    $nomeproduto = $_POST['produto']; // Alterado para 'produto' para corresponder ao campo correto
    $quantidade = $_POST['quantity'];
    $motivo = $_POST['reason'];
    $observacao = $_POST['observation'];

    // Validação do comprimento da observação
    if (strlen($observacao) > 100) {
        $errors[] = "A observação não pode ter mais de 100 caracteres.";
    } else {
        // Consulta SQL para verificar a quantidade disponível do produto no banco de dados
        $sql_quantidade_disponivel = "SELECT quantidade FROM products WHERE idproducts = '$idproduto'";
        $result_quantidade = mysqli_query($conexao, $sql_quantidade_disponivel);

        if ($result_quantidade) {
            if (mysqli_num_rows($result_quantidade) > 0) {
                $row = mysqli_fetch_assoc($result_quantidade);
                $quantidade_disponivel = $row['quantidade'];

                // Verificar se a quantidade de saída é menor ou igual à quantidade disponível
                if ($quantidade <= $quantidade_disponivel) {
                    // Inserir os dados na tabela de saída de produtos
                    $sql_insert = "INSERT INTO saida_produtos (nomeproduto, quantidade, motivo, observacao) VALUES ('$nomeproduto', $quantidade, '$motivo', '$observacao')";
                    $result_insert = mysqli_query($conexao, $sql_insert);

                    if ($result_insert) {
                        $nova_quantidade = $quantidade_disponivel - $quantidade;
                        $sql_update = "UPDATE products SET quantidade = $nova_quantidade WHERE idproducts = '$idproduto'";
                        $result_update = mysqli_query($conexao, $sql_update);

                        if ($result_update) {
                            $success = true;
                        } else {
                            $errors[] = "Erro ao atualizar a quantidade disponível.";
                        }
                    } else {
                        $errors[] = "Erro ao inserir os dados na tabela de saída de produtos.";
                    }
                } else {
                    $errors[] = "Quantidade indisponível! A quantidade disponível é de $quantidade_disponivel unidade(s).";
                }
            } else {
                $errors[] = "Produto não encontrado.";
            }
        } else {
            $errors[] = "Erro na consulta do banco de dados: " . mysqli_error($conexao);
        }
    }

    mysqli_close($conexao);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Saída de Produtos</title>
</head>
<body>
    <div class="container">
        <section class="header">
            <h2>Saída de Produtos</h2>
        </section>
        <form action="saidaEstoque.php" method="POST" id="productForm" class="form">
            <div class="form-content">
                <label for="idproducts">Código do Produto</label>
                <input type="text" id="idproducts" name="idproducts" placeholder="Digite o código do produto" required value="<?php echo !$success && isset($_POST['idproducts']) ? htmlspecialchars($_POST['idproducts']) : ''; ?>"/>
                <a id="idproductsError" class="error-message"></a>
            </div>
            <div class="form-content">
                <label for="productName"><i class="bi bi-tag"></i> Produto</label>
                <input type="text" id="productName" name="produto" readonly value="<?php echo !$success && isset($_POST['produto']) ? htmlspecialchars($_POST['produto']) : ''; ?>"/>
                <a id="productNameError" class="error-message"></a>
            </div>

            <div class="form-content">
                <label for="quantity"><i class="bi bi-box"></i> Quantidade</label>
                <input type="number" id="quantity" name="quantity" placeholder="Quantidade" min="1" step="1" required value="<?php echo !$success && isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>"/>
                <a id="quantityError" class="error-message"></a>
            </div>

            <div class="form-content">
                <label for="reason"><i class="bi bi-emoji-frown"></i> Motivo</label>
                <input type="text" id="reason" name="reason" placeholder="Motivo" required value="<?php echo !$success && isset($_POST['reason']) ? htmlspecialchars($_POST['reason']) : ''; ?>"/>
                <a id="reasonError" class="error-message"></a>
            </div>

            <div class="form-content">
                <label for="observation"><i class="bi bi-chat-left"></i> Observação</label>
                <input type="text" id="observation" name="observation" placeholder="Observação" value="<?php echo !$success && isset($_POST['observation']) ? htmlspecialchars($_POST['observation']) : ''; ?>">
                <a id="observationError" class="error-message"></a>
            </div>

            <button type="submit" id="submitButton"><i class="bi bi-arrow-right-circle"></i> Registrar Saída</button>
        </form>

        <?php if (!empty($errors)): ?>
            <div class="mensagem erro">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
            <script>
                setTimeout(function() {
                    document.querySelector('.mensagem.erro').style.opacity = '0';
                }, 3000); // 3 segundos
            </script>
        <?php elseif ($success): ?>
            <div class="mensagem sucesso">
                <p>Produto removido com sucesso.</p>
            </div>
            <script>
                setTimeout(function() {
                    document.querySelector('.mensagem.sucesso').style.opacity = '0';
                }, 3000); // 3 segundos
                document.getElementById('productForm').reset(); 
            </script>
        <?php endif; ?>
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
    </div>
    <script>
        function redirecionar(url) {
            window.location.href = url;
        }

        document.getElementById('idproducts').addEventListener('input', function() {
            var idProduto = this.value;
            if (idProduto) {
                fetch('saidaEstoque.php?action=get_product_name&idproducts=' + idProduto)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                        } else {
                            document.getElementById('productName').value = data.nome;
                        }
                    })
                    .catch(error => console.error('Erro:', error));
            } else {
                document.getElementById('productName').value = '';
            }
        });
    </script>
</body>
</html>
