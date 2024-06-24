<?php
    

    include_once('config.php');

    $sql = "SELECT * FROM  fornecedor ORDER BY id ";

    if(!empty($_GET['search'])) {
        $data = $_GET['search'];
        $sql = "SELECT * FROM fornecedor WHERE nome LIKE '%$data%' ORDER BY id";
    }

    $result = $conexao->query($sql);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Fornecedores</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/listaFornecedores.css">
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
        <div class="box-search">
            <input type="search" class="form-control w-25" placeholder="Pesquisar Fornecedor" id="pesquisar">
            <button onclick="searchData()" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                </svg>
            </button>
        </div>
        <div class="table-container">
            <table table table-dark table-striped>
                <thead>
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nome</th>
                        <th scope="col">CNPJ</th>
                        <th scope="col">Contato</th>
                        <th scope="col">Endereço</th>
                        <th scope="col">Cidade</th>
                        <th scope="col">CEP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($user_data = mysqli_fetch_assoc($result)){
                        echo "<tr>";
                        echo "<td>".$user_data['id']."</td>";
                        echo "<td>".$user_data['nome']."</td>";
                        echo "<td>".$user_data['cnpj']."</td>";
                        echo "<td>".$user_data['contato']."</td>";
                        echo "<td>".$user_data['endereco']."</td>";
                        echo "<td>".$user_data['cidade']."</td>";
                        echo "<td>".$user_data['cep']."</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function redirecionar(url) {
            window.location.href = url;
        }
    </script>
    <script>
        var search = document.getElementById('pesquisar');

        search.addEventListener("keydown", function(event) {
            if (event.key === "Enter") 
            {
                searchData();
            }
        });

        function searchData()
        {
            window.location = 'listaFornecedores.php?search='+search.value;
        }
    </script>
</body>
</html>
