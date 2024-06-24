<?php

$errorMessage = "";
$successMessage = "";

if (isset($_POST['submit'])) {
    // Incluir o arquivo de configuração do banco de dados
    include_once('config.php');

    // Validar e obter valores do formulário
    $nome = isset($_POST['supplierName']) ? $_POST['supplierName'] : '';
    $cnpj = $_POST['supplierCNPJ'];
    $contato = $_POST['supplierContact'];
    $endereco = isset($_POST['supplierAddress']) ? $_POST['supplierAddress'] : '';
    $cidade = isset($_POST['supplierCity']) ? $_POST['supplierCity'] : '';
    $cep = isset($_POST['supplierCEP']) ? $_POST['supplierCEP'] : '';

    // Verificar se o nome do fornecedor não está vazio e tem até 100 caracteres
    if (empty($nome)) {
        $errorMessage .= "O nome do fornecedor não pode estar vazio.<br>";
    } elseif (strlen($nome) > 100) {
        $errorMessage .= "O nome do fornecedor não pode ter mais de 100 caracteres.<br>";
    }

    // Verificar se o CNPJ não está vazio e tem 14 caracteres exatos
    if (empty($cnpj)) {
        $errorMessage .= "O CNPJ não pode estar vazio.<br>";
    } elseif (strlen($cnpj) != 14) {
        $errorMessage .= "O CNPJ deve ter exatamente 14 caracteres.<br>";
    }

    // Verificar se o número de contato não está vazio e tem entre 8 e 15 caracteres
    if (empty($contato)) {
        $errorMessage .= "O número de contato não pode estar vazio.<br>";
    } elseif (strlen($contato) < 8 || strlen($contato) > 15) {
        $errorMessage .= "O número de contato deve ter entre 8 e 15 caracteres.<br>";
    }

    // Verificar se o endereço não está vazio e tem até 200 caracteres
    if (empty($endereco)) {
        $errorMessage .= "O endereço não pode estar vazio.<br>";
    } elseif (strlen($endereco) > 200) {
        $errorMessage .= "O endereço não pode ter mais de 200 caracteres.<br>";
    }

    // Verificar se a cidade não está vazia e tem até 100 caracteres
    if (empty($cidade)) {
        $errorMessage .= "A cidade não pode estar vazia.<br>";
    } elseif (strlen($cidade) > 100) {
        $errorMessage .= "A cidade não pode ter mais de 100 caracteres.<br>";
    }

    // Verificar se o CEP não está vazio e tem 8 caracteres exatos
    if (empty($cep)) {
        $errorMessage .= "O CEP não pode estar vazio.<br>";
    } elseif (strlen($cep) != 8) {
        $errorMessage .= "O CEP deve ter exatamente 8 caracteres.<br>";
    }

    // Verificar se já existe fornecedor com o mesmo CNPJ
    $sql_check_cnpj = "SELECT * FROM fornecedor WHERE cnpj = '$cnpj'";
    $result_check_cnpj = mysqli_query($conexao, $sql_check_cnpj);

    if (mysqli_num_rows($result_check_cnpj) > 0) {
        $errorMessage .= "Este CNPJ já está cadastrado!<br>";
    }

    // Verificar se já existe fornecedor com o mesmo número de contato
    $sql_check_contato = "SELECT * FROM fornecedor WHERE contato = '$contato'";
    $result_check_contato = mysqli_query($conexao, $sql_check_contato);

    if (mysqli_num_rows($result_check_contato) > 0) {
        $errorMessage .= "Este número de contato já está cadastrado!<br>";
    }

    // Verificar se já existe fornecedor com o mesmo nome
    $sql_check_nome = "SELECT * FROM fornecedor WHERE nome = '$nome'";
    $result_check_nome = mysqli_query($conexao, $sql_check_nome);

    if (mysqli_num_rows($result_check_nome) > 0) {
        $errorMessage .= "Nome de Fornecedor já cadastrado!<br>";
    }

    // Verificar se já existe fornecedor com o mesmo endereço
    $sql_check_endereco = "SELECT * FROM fornecedor WHERE endereco = '$endereco'";
    $result_check_endereco = mysqli_query($conexao, $sql_check_endereco);

    if (mysqli_num_rows($result_check_endereco) > 0) {
        $errorMessage .= "Este endereço já está cadastrado para outro fornecedor!<br>";
    }

    // Se não houver mensagens de erro, inserir o fornecedor
    if (empty($errorMessage)) {
        $sql_insert_supplier = "INSERT INTO fornecedor (nome, cnpj, contato, endereco, cidade, cep)
        VALUES ('$nome', '$cnpj', '$contato', '$endereco', '$cidade', '$cep')";

        if (mysqli_query($conexao, $sql_insert_supplier)) {
            $successMessage = "Fornecedor cadastrado com sucesso!";
        } else {
            $errorMessage = "Erro ao inserir os dados na tabela de fornecedores.";
        }
    }
    mysqli_close($conexao);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Fornecedores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            <h2>Cadastro de Fornecedores</h2>
        </section>
        <form action="cadastroFornecedor.php" method="POST" id="supplierForm" class="form" onsubmit="return submitForm()">
            <div class="form-content">
                <label for="supplierName"><i class="bi bi-people"></i> Nome do Fornecedor</label>
                <input type="text" id="supplierName" name="supplierName" placeholder="Digite o nome do fornecedor" required/>
            </div>
            <div class="form-content">
                <label for="supplierCNPJ"><i class="bi bi-file-earmark-text"></i> CNPJ</label>
                <input type="number" id="supplierCNPJ" name="supplierCNPJ" min='0' placeholder="Digite o CNPJ do fornecedor" required/>
            </div>
            <div class="form-content">
                <label for="supplierContact"><i class="bi bi-telephone"></i> Contato</label>
                <input type="number" id="supplierContact" name="supplierContact" placeholder="Digite o contato do fornecedor" required/>
            </div>
            <div class="form-content">
                <label for="supplierAddress"><i class="bi bi-geo-alt"></i> Endereço</label>
                <input type="text" id="supplierAddress" name="supplierAddress" placeholder="Digite o endereço do fornecedor" required/>
            </div>
            <div class="form-content">
                <label for="supplierCity"><i class="bi bi-building"></i> Município</label>
                <input type="text" id="supplierCity" name="supplierCity" placeholder="Digite o município do fornecedor" required/>
            </div>
            <div class="form-content">
                <label for="supplierCEP"><i class="bi bi-mailbox"></i> CEP</label>
                <input type="number" id="supplierCEP" name="supplierCEP" placeholder="Digite o CEP do fornecedor" required/>
            </div>
            <button type="submit" name="submit" id="submit" value="1"><i class="bi bi-plus-circle"></i> Cadastrar Fornecedor</button>
            <?php if (isset($errorMessage)): ?>
                <div class="mensagem erro">
                    <?php echo $errorMessage; ?>
                </div>
                <script>
                    setTimeout(function() {
                        document.querySelector('.mensagem.erro').style.opacity = '0';
                    }, 3000);
                </script>
            <?php elseif (isset($successMessage)): ?>
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

        function submitForm() {
            // Adicione a lógica de validação adicional aqui, se necessário
            return true;
        }
    </script>
</body>
</html>
