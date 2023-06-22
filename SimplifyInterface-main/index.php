<?php
// Verifica se os campos de login e senha foram enviados pelo formulário
if (isset($_GET['name']) && isset($_GET['senha'])) {
    $login = $_GET['name'];
    $senha = $_GET['senha'];

    // Conectar ao banco de dados (substitua com suas credenciais)
    $host = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'simplify';

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    // Verifica se a conexão foi estabelecida corretamente
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Consulta SQL para verificar se o login e senha existem no banco de dados
    $sql = "SELECT * FROM usuario WHERE usuario = '$login' AND senha = '$senha'";
    $result = $conn->query($sql);

    // Verifica se encontrou algum registro correspondente
    if ($result->num_rows > 0) {
        // Login válido, redireciona para a página desejada
        header("Location: insert.php");
        exit(); // Importante: encerra o script para garantir o redirecionamento
    } else {
        // Login inválido, exibe uma mensagem de erro
        echo "Login ou senha inválidos.";
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simplify</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body class="light-theme">
    <header>
        <img src="assets/images/logo.png" alt="Logo" class="logo-image">
        <span class="logo-text">Simplify</span>
    </header>
    <main>


        <div class="new-expense-form">
            <form action="" method="get"action=""> <!-- Método que irá salvar no banco de dados -->
            <span class="form-title">Log In</span>
            <div class="form-row">
                <label for="login-username" class="form-label">Usuário:</label>
                <input type="text" id="login-username" class="form-field" name="name"> <!-- Entrada de dados -->
            </div>
            <div class="form-row">
                <label for="login-password" class="form-label">Senha:</label>
                <input type="password" id="login-password" class="form-field" name="senha"> <!-- Entrada de dados -->
            </div>
            <div class="form-row justify-right">
                <button type="submit" class="form-button new-expense-confirm" id="login-btn">Entrar</button> <!-- Botão de confirmação -->
            </div>
            <div class="form-row">
                <a href="signin.php" class="credentials-link">Não possui uma conta ainda?</a>
            </div>
        </form>
        </div>


    </main>
    <footer>
        <a href="credits.html" class="credits-link" target="_blank">Criadores</a>
    </footer>
</body>
<script src="js/index.js"></script>
</html>