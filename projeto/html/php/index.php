<?php

include 'config.php';


$errorMessage = '';


$loginValue = '';
$senhaValue = '';


if (isset($_POST['submit'])) {
    
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    
    $loginValue = htmlspecialchars($login);
    $senhaValue = htmlspecialchars($senha);

    
    $sql_check_user = "SELECT * FROM usuarios WHERE login = '$login'";
    $result_check_user = mysqli_query($conexao, $sql_check_user);
    
    
    if (mysqli_num_rows($result_check_user) == 0) {
        
        $errorMessage = "Usuário ou senha incorretos.";
    } else {
        // Obtém as informações do usuário
        $row = mysqli_fetch_assoc($result_check_user);
        $hashed_password = $row['senha'];

        // Verifica se a senha inserida corresponde à senha armazenada no banco de dados
        if (password_verify($senha, $hashed_password)) {
            header("Location: home.php");
            exit; 
        } else {
            $errorMessage = "Usuário ou senha incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Tela de Login</title>
    <style>
        .password-wrapper {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            top: 75%; /* Ajuste a posição conforme necessário */
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.5em;
            color: #ccc;
        }
        .toggle-password.active {
            color: #00b894;
        }
        .error-message {
            color: #ff0000; /* Cor vermelha */
            font-size: 14px; /* Tamanho da fonte */
            margin-top: 5px; /* Margem superior para separar do botão */
            text-align: center; /* Alinhamento central */
            opacity: 1;
            transition: opacity 1s ease-in-out; /* Transição suave */
        }
        .error-message.fade-out {
            opacity: 0;
        }
    </style>
</head>
<body>
    <div class="main-login">
        <div class="left-login">
            <img src="animacao.svg" class="left-login-image" alt="ANIMAÇÃO">
        </div>
        <div class="right-login">
            <div class="card-login">
                <h1>LOGIN</h1>
                <form action="index.php" method="post">
                    <div class="text-field">
                        <label for="login">Login</label>
                        <input type="text" name="login" id="login" placeholder="Login" required value="<?php echo $loginValue; ?>">
                    </div>
                    <div class="text-field password-wrapper">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" id="senha" placeholder="Senha" required value="<?php echo $senhaValue; ?>">
                        <i class="bi bi-eye-slash toggle-password" onclick="togglePassword()"></i>
                    </div>
                    <button type="submit" name="submit" class="btn-login">Login</button>
                    <!-- Exibição da mensagem de erro -->
                    <?php if ($errorMessage !== ''): ?>
                        <div class="error-message" id="error-message"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>
                </form>
                <a href="cadastroLogin.php" class="signup-link">Não possuo uma conta</a>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("senha");
            var toggleIcon = document.querySelector(".toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }

        // Função para ocultar a mensagem de erro com uma suavização
        function hideErrorMessage() {
            var errorMessage = document.getElementById("error-message");
            if (errorMessage) {
                setTimeout(function() {
                    errorMessage.classList.add("fade-out");
                    setTimeout(function() {
                        errorMessage.style.display = "none";
                    }, 1000); // Espera pela duração da transição antes de esconder
                }, 3000); // Mostra a mensagem por 3 segundos antes de iniciar a transição
            }
        }

        // Chama a função para ocultar a mensagem de erro quando a página é carregada
        window.onload = hideErrorMessage;
    </script>
</body>
</html>