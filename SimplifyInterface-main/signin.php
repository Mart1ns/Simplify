
<?php
if (isset($_GET['login']) && isset($_GET['senha'])) {
    $login = $_GET['login'];
    $senha = $_GET['senha'];

    // Conectar ao banco de dados (substitua com suas próprias credenciais)
    $host = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'simplify';

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    // Verificar se a conexão foi estabelecida corretamente
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Consulta SQL para verificar se o login já existe
    $checkQuery = "SELECT * FROM usuario WHERE usuario = '$login'";
    $checkResult = $conn->query($checkQuery);

    // Verificar se o login já existe no banco de dados
    if ($checkResult->num_rows > 0) {
        // Login já existe, exibir alerta
        echo "<script>alert('O login informado já está em uso.');</script>";
    } else {
        // Login não existe, salvar no banco de dados
        $insertQuery = "INSERT INTO usuario (usuario, senha) VALUES ('$login', '$senha')";
        if ($conn->query($insertQuery) === true) {
            // Registro salvo com sucesso, redirecionar para a página desejada
            header("Location: insert.php");
            exit();
        } else {
            // Ocorreu um erro ao salvar o registro
            echo "Erro ao salvar o registro: " . $conn->error;
        }
    }

    // Fechar a conexão com o banco de dados
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

        <form action="" method="get">
        <div class="new-expense-form">
            <span class="form-title">Sign In</span>
            <div class="form-row">
                <label for="signin-username" class="form-label">Usuário:</label>
                <input type="text" id="signin-username" class="form-field" name="login"> 
            </div>
            <div class="form-row">
                <label for="v-password" class="form-label">Senha:</label>
                <input type="password" id="signin-password" class="form-field" name="senha">
            </div>
            <div class="form-row justify-right">
                <button class="form-button new-expense-confirm" id="signin-btn">Criar conta</button>
            </div>
            <div class="form-row">
                <a href="index.php" class="credentials-link">Ja tem uma conta?</a>
            </div>
        </div>
        </form>
    </main>
    <footer>
        <a href="credits.php" class="credits-link" target="_blank">Criadores</a>
    </footer>
</body>
<script src="js/signin.js"></script>
</html>