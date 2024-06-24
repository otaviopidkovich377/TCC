<?php

include_once('config.php');

// Inicialização das variáveis de mensagem
$errorMessage = '';
$successMessage = '';

// Verifica se o formulário foi enviado
if (isset($_POST['submit'])) {
    // Obtém os valores enviados pelo formulário
    $categoria = isset($_POST['categoryName']) ? $_POST['categoryName'] : '';
    $descricao = isset($_POST['categoryDescription']) ? $_POST['categoryDescription'] : '';

    // Validação dos campos
    if (empty($categoria)) {
        $errorMessage = "Por favor, preencha o campo de categoria.";
    } elseif (strlen($categoria) > 30) {
        $errorMessage = "O nome da categoria não pode exceder 30 caracteres.";
    } elseif (strlen($categoria) < 3) {
        $errorMessage = "O nome da categoria deve ter pelo menos 3 caracteres.";
    } else {
        // Verifica se a categoria já está cadastrada usando prepared statement
        $sql_check_categoria = "SELECT * FROM categorias WHERE categoria = ?";
        $stmt_check_categoria = mysqli_prepare($conexao, $sql_check_categoria);
        mysqli_stmt_bind_param($stmt_check_categoria, "s", $categoria);
        mysqli_stmt_execute($stmt_check_categoria);
        mysqli_stmt_store_result($stmt_check_categoria);

        if (mysqli_stmt_num_rows($stmt_check_categoria) > 0) {
            $errorMessage = "Categoria já cadastrada!";
        } else {
            // Insere os dados na tabela de categorias usando prepared statement
            $sql_insert_categoria = "INSERT INTO categorias (categoria, descricao) VALUES (?, ?)";
            $stmt_insert_categoria = mysqli_prepare($conexao, $sql_insert_categoria);
            mysqli_stmt_bind_param($stmt_insert_categoria, "ss", $categoria, $descricao);

            if (mysqli_stmt_execute($stmt_insert_categoria)) {
                $successMessage = "Categoria cadastrada com sucesso!";
            } else {
                $errorMessage = "Erro ao cadastrar a categoria: " . mysqli_error($conexao);
            }

            // Fechar statement de inserção
            mysqli_stmt_close($stmt_insert_categoria);
        }

        // Fechar statement de verificação
        mysqli_stmt_close($stmt_check_categoria);
    }
}

// Fechar conexão
mysqli_close($conexao);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/cadastro.css">
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
    <section class="header">
        <h2>Cadastro de Categoria</h2>
    </section>
    <form action="cadastroCategorias.php" method="POST" id="categoryForm" class="form">
        <div class="form-content">
            <label for="categoryName"><i class="bi bi-people"></i>Tipo de Categoria</label>
            <input type="text" id="categoryName" name="categoryName" placeholder="Digite o nome do categoria" required/>
        </div>
        <div class="form-content">
            <label for="categoryDescription"><i class="bi bi-file-earmark-text"></i> Descrição dessa categoria</label>
            <input type="text" id="categoryDescription" name="categoryDescription" placeholder="Digite a Descrição" required/>
        </div>
        <button type="submit" name="submit" id="submit" value="1"><i class="bi bi-plus-circle"></i> Cadastrar Categoria</button>
        <?php if (!empty($errorMessage)): ?>
            <div class="mensagem erro">
                <?php echo $errorMessage; ?>
            </div>
        <script>
        setTimeout(function() {
            document.querySelector('.mensagem.erro').style.opacity = '0';
        }, 3000);
        </script>
        <?php endif; ?>
        <?php if (!empty($successMessage)): ?>
            <div class="mensagem sucesso">
                <?php echo $successMessage; ?>
            </div>
        <script>
        setTimeout(function() {
            document.querySelector('.mensagem.sucesso').style.opacity = '0';
        }, 3000);
        </script>
        <?php endif; ?>
    </form>
</div>
<script>
    function redirecionar(url) {
        window.location.href = url;
    }
</script>
</body>
</html>

