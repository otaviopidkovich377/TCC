<?php
if (isset($_POST['submit'])) {
    $errors = array(); // Array para armazenar mensagens de erro

    // Validar cada campo individualmente
    if (empty($_POST['productName'])) {
        $errors[] = "Favor, coloque o nome do produto";
    }

    if (empty($_POST['productPrice'])) {
        $errors[] = "Informe um preço";
    }

    if (empty($_POST['productQuantity'])) {
        $errors[] = "Informe a quantidade";
    }

    if (empty($_POST['productSupplier'])) {
        $errors[] = "Informe o fornecedor do produto";
    }

    // Se não houver erros, processar o formulário
    if (empty($errors)) {
        $nome = $_POST['productName'];
        $preco = $_POST['productPrice'];
        $quantidade = $_POST['productQuantity'];
        $descricao = $_POST['productDescription'];
        $fornecedor = $_POST['productSupplier'];

        // Conexão com o banco de dados e consulta SQL
        include_once('config.php');
       
        $sql = mysqli_query($conexao,  "INSERT INTO products (nome, preco, quantidade, descricao, fornecedor)
        VALUES ('$nome', '$preco', '$quantidade', '$descricao', '$fornecedor')");
        
      }  
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/cadastro.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Cadastro de Produtos</title>
</head>
<body>
    <div class="container">
        <section class="header">
            <h2>Cadastro de Produtos</h2>
        </section>
        <form action="cadastro.php" method="POST" id="productForm" class="form">
            <div class="form-content">
                <label for="productName"><i class="bi bi-tag"></i> Nome do Produto</label>
                <input type="text" id="productName" name="productName" placeholder="Digite o nome do produto" required/>
                <a>Aqui vai a mensagem de erro</a>
            </div>
            <div class="form-content">
                <label for="productPrice"><i class="bi bi-cash"></i>Preço</label>
                <input type="number" id="productPrice" name="productPrice" placeholder="Digite o preço do produto" min="0" step="0.01" required/>
                <a>Aqui vai a mensagem de erro</a>
            </div>
            <div class="form-content">
                <label for="productQuantity"><i class="bi bi-box"></i>Quantidade</label>
                <input type="number" id="productQuantity" name="productQuantity" placeholder="Quantidade" min="0" step="1" required/>
                <a>Aqui vai a mensagem de erro</a>
            </div>
            <div class="form-content">
                <label for="productDescription"><i class="bi bi-file-text"></i>Descrição</label>
                <input type="text" id="productDescription" name="productDescription" placeholder="Descrição" />
                <a>Aqui vai a mensagem de erro</a>
            </div>
            <div class="form-content">
                <label for="productSupplier"><i class="bi bi-people"></i>Fornecedor</label>
                <input type="text" id="productSupplier" name="productSupplier" placeholder="Fornecedor" required/>
                <a>Aqui vai a mensagem de erro</a>
            </div>
            <button type="submit" name="submit" id="submit" value="1"><i class="bi bi-plus-circle"></i>Cadastrar produto</button>
        </form>
        <div id="productData" class="product-data"></div>
        <nav class="menu-lateral">
            <div class="btn-expandir">
                <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" type="button" class="btn btn-outline-light"><i class="bi bi-list"></i></button>
            </div>
            <ul>
                <li class="item-menu">
                    <a href="#" onclick="redirecionar('home.html');">
                        <span class="icon"><i class="bi bi-house-door"></i></span>
                        <span class="txt-link">Início</span>
                    </a>
                </li>
                <li class="item-menu">
                    <a href="#" onclick="redirecionar('cadastro.php');">
                        <span class="icon"><i class="bi bi-plus-circle"></i></span>
                        <span class="txt-link">Entrada</span>
                    </a>
                </li>
                <li class="item-menu">
                    <a href="#" onclick="redirecionar('saidaEstoque.html');">
                        <span class="icon"><i class="bi bi-arrow-down-circle"></i></span>
                        <span class="txt-link">Saída</span>
                    </a>
                </li>
                <li class="item-menu">
                    <a href="#" onclick="redirecionar('estoque.html');">
                        <span class="icon"><i class="bi bi-archive"></i></span>
                        <span class="txt-link">Estoque</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <script>
function redirecionar(url) {
    window.location.href = url;
}

    </script>
</body>
</html>