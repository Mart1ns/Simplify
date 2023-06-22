<?php
// Arquivo get_expenses.php

include_once('conection/config.php');

// Função para obter os gastos do banco de dados
function getGastos() {
    global $conexao;

    $query = "SELECT * FROM gastos";
    $result = mysqli_query($conexao, $query);

    $gastos = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $gastos[] = $row;
    }

    return $gastos;
}

// Obtém os gastos do banco de dados
$gastos = getGastos();

// Retorna os gastos no formato JSON
echo json_encode($gastos);
?>
