<?php

include_once('config.php');


function validarEmail($email) {
   return filter_var($email, FILTER_VALIDATE_EMAIL);
}


function validarSenha($senha) {
     return strlen($senha) >= 8;
}


$emailValue = isset($_POST['email']) ? $_POST['email'] : '';
$loginValue = isset($_POST['login']) ? $_POST['login'] : '';


$errorMessage = '';
$successMessage = '';

if (isset($_POST['submit'])) {
    
    $email = $_POST['email'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmarSenha'];

    
    $sql_check_user = "SELECT * FROM usuarios WHERE email = '$email'";
    $result_check_user = mysqli_query($conexao, $sql_check_user);
    
    if (mysqli_num_rows($result_check_user) > 0) {
       
        $errorMessage = "Este email já está em uso!";
    } else {
        if (!validarEmail($email)) {
            $errorMessage = "Por favor, insira um endereço de e-mail válido!";
        } else {
            if (!validarSenha($senha)) {
                 $errorMessage = "A senha deve ter pelo menos 8 caracteres!";
            } else {
                if ($senha !== $confirmarSenha) {
                    
                    $errorMessage = "As senhas não são iguais!";
                } else {
                    $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
                    $sql_insert_user = "INSERT INTO usuarios (email, login, senha) VALUES ('$email', '$login', '$senhaCriptografada')";

                    if (mysqli_query($conexao, $sql_insert_user)) {
                        $successMessage = "Usuário cadastrado com sucesso!";
                        $emailValue = $loginValue = '';
                    } else {
                        $errorMessage = "Erro ao inserir os dados na tabela de usuários.";
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cadastroLogin.css">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <div class="main-login">
        <div class="left-login">
        <img src="animacao.svg" class="left-login-image" alt="ANIMAÇÃO">
        </div>
        <div class="right-login">
            <div class="card-login">
                <h1>Cadastro de Usuário</h1>
                <form class="form" action="cadastroLogin.php" method="POST">
                    <div class="text-field">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" placeholder="E-mail" required value="<?php echo $emailValue; ?>">
                    </div>
                    <div class="text-field">
                        <label for="login">Login</label>
                        <input type="text" name="login" placeholder="Login" required value="<?php echo $loginValue; ?>">
                    </div>
                    <div class="text-field password-field">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" placeholder="Senha" min="8" required>
                    </div>
                    <div class="text-field password-field">
                        <label for="confirmarSenha">Confirmar Senha</label>
                        <input type="password" name="confirmarSenha" placeholder="Confirmar Senha" required>
                    </div>
                    <button type="submit" name="submit" id="submit" class="btn-login" value="1"><i class="bi bi-plus-circle"></i> Cadastrar usuário</button>
                </form>
                <a href="index.php" class="signup-link">Voltar a página inicial</a>
               
                <?php if ($errorMessage !== ''): ?>
                    <div class="error-message"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
                <?php if ($successMessage !== ''): ?>
                    <div class="success-message"><?php echo $successMessage; ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
            function esconderMensagens() {
            // Seleciona todas as mensagens de erro e sucesso
            var errorMessages = document.querySelectorAll('.error-message');
            var successMessages = document.querySelectorAll('.success-message');
            
            // Itera sobre as mensagens de erro e as oculta
            errorMessages.forEach(function(message) {
                message.style.display = 'none';
            });

            // Itera sobre as mensagens de sucesso e as oculta
            successMessages.forEach(function(message) {
                message.style.display = 'none';
            });
        }
        setTimeout(esconderMensagens, 3000);
    </script>
    </script>
</body>
</html>