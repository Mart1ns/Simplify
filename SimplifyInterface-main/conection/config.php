<?php
$host = 'Localhost';
$user = 'root';
$password = '';
$database = 'simplify';

$conexao = mysqli_connect($host, $user, $password, $database);

// Verificar se ocorreu um erro na conexão
if (mysqli_connect_errno()) {
    echo "Falha na conexão com o MySQL: " . mysqli_connect_error();
    exit();
}
?>
