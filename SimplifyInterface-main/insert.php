<?php
// Arquivo insert.php

include_once('conection/config.php');

// Função para obter os gastos da tabela
function getGastos()
{
    global $conexao;

    $query = "SELECT * FROM gastos";
    $result = mysqli_query($conexao, $query);

    $gastos = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $gastos[] = $row;
    }

    return $gastos;
}

// Verificar se o formulário de novo gasto foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new-gasto'])) {
    // Obter os valores enviados via POST
    $nome = $_POST['nomeDoGasto'];
    $valor = floatval($_POST['valorDoGasto']);
    $categoria = $_POST['categoriaDoGasto'];
    $data = $_POST['dataDoGasto'];

    // Preparar a consulta SQL com prepared statements para evitar ataques de SQL injection
    $query = "INSERT INTO gastos (nome, valor, categoria, data_gasto) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $query);

    // Vincular os parâmetros aos valores obtidos do formulário
    mysqli_stmt_bind_param($stmt, "sdss", $nome, $valor, $categoria, $data);
    
    // Executar a consulta
    mysqli_stmt_execute($stmt);

    // Redirecionar o usuário para a mesma página
    header("Location: insert.php");
    exit(); // Certifique-se de sair do script após o redirecionamento
}

// Verificar se o ID de um gasto para exclusão foi fornecido
if (isset($_GET['delete_id'])) {
    // Obter o ID do gasto a ser excluído
    $id = $_GET['delete_id'];

    // Preparar a consulta SQL para excluir o gasto com o ID fornecido
    $query = "DELETE FROM gastos WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $query);

    // Vincular o parâmetro ao valor do ID
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    // Executar a consulta
    mysqli_stmt_execute($stmt);

    // Redirecionar o usuário para a mesma página
    header("Location: insert.php");
    exit(); // Certifique-se de sair do script após o redirecionamento
}

// Verificar se o ID de um gasto para edição foi fornecido
if (isset($_GET['edit_id'])) {
    // Obter o ID do gasto a ser editado
    $id = $_GET['edit_id'];

    // Obter o gasto com o ID fornecido
    $query = "SELECT * FROM gastos WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $gasto = mysqli_fetch_assoc($result);

    // Redirecionar o usuário para o formulário de edição
    if ($gasto) {
        header("Location: home.php?edit_form=true&id=$id");
        exit();
    }
}

// Verificar se o formulário de edição foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit-gasto'])) {
    // Obter os valores enviados via POST
    $id = $_POST['gastoId'];
    $nome = $_POST['nomeDoGasto'];
    $valor = floatval($_POST['valorDoGasto']);
    $categoria = $_POST['categoriaDoGasto'];
    $data = $_POST['dataDoGasto'];

    // Preparar a consulta SQL com prepared statements para evitar ataques de SQL injection
    $query = "UPDATE gastos SET nome = ?, valor = ?, categoria = ?, data_gasto = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $query);

    // Vincular os parâmetros aos valores obtidos do formulário
    mysqli_stmt_bind_param($stmt, "sdssi", $nome, $valor, $categoria, $data, $id);
    
    // Executar a consulta
    mysqli_stmt_execute($stmt);

    // Redirecionar o usuário para a mesma página
    header("Location: home.php");
    exit(); // Certifique-se de sair do script após o redirecionamento
}

// Obter os gastos da tabela
$gastos = getGastos();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simplify</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="styles/table.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="light-theme">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <header>
        <div class="logo-container">
            <img src="assets/images/logo.png" alt="Logo" class="logo-image">
            <span class="logo-text">Simplify</span>
        </div>
        <button class="log-out-btn" id="log-out-btn">Log Out</button>
    </header>
    <main>
        <button class="new-expense-btn" id="new-expense-btn">Novo Gasto +</button>
        <div class="graphics-container">
    <div class="graphic">
        <p class="graphic-title">Percentual de gastos</p>
        <canvas id="percent-chart"></canvas>
    </div>
    <div class="graphic">
        <p class="graphic-title">Gastos por Categoria</p>
        <canvas id="category-chart"></canvas>
    </div>
    <div class="graphic">
        <p class="graphic-title">Limite de Gastos</p>
        <!-- Gráfico aqui -->
        <div class="limmit-container">
            <label for="limmit-input" class="limmit-input">Limite:</label>
            <input type="text" id="limmit-input" class="form-field">                    
        </div>
    </div>
</div>

        <div class="table-container">
            <table id="expenses-table" class="expenses-table display">
                <thead>
                    <tr>
                        <th>Gasto</th>
                        <th>Valor</th>
                        <th>Categoria</th>
                        <th>Ação</th> <!-- Nova coluna para ação de exclusão -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gastos as $gasto) : ?>
                        <tr>
                            <td><?php echo $gasto['nome']; ?></td>
                            <td><?php echo $gasto['valor']; ?></td>
                            <td><?php echo $gasto['categoria']; ?></td>
                            <td>
                                <a href="?delete_id=<?php echo $gasto['id']; ?>" onclick="return confirm('Tem certeza de que deseja excluir este registro?')">Excluir</a>
                                <a href="?edit_id=<?php echo $gasto['id']; ?>">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <div class="new-expense-overlay hidden" id="new-expense-overlay">
        <div class="new-expense-form">
            <span class="form-title">Nova Despesa</span>
            <form method="POST" action="insert.php">
                <div class="form-row">
                    <label for="new-expense-name" class="form-label">Nome:</label>
                    <input type="text" id="new-expense-name" name="nomeDoGasto" class="form-field">
                </div>
                <div class="form-row">
                    <label for="new-expense-value" class="form-label">Valor:</label>
                    <input type="number" id="new-expense-value" name="valorDoGasto" class="form-field">
                </div>
                <div class="form-row">
                    <label for="new-expense-date" class="form-label">Data:</label>
                    <input type="date" id="new-expense-date" name="dataDoGasto" class="form-field">
                </div>
                <div class="form-row">
                    <label for="new-expense-types" class="form-label">Categoria</label>
                    <input type="text" id="new-expense-types" name="categoriaDoGasto" class="form-field">
                </div>
                <div class="form-row hidden-sizeless" id="expense-type-name-row">
                    <label for="new-expense-type-name" class="form-label form-label-wider">Nome da Categoria</label>
                    <input type="text" id="new-expense-type-name" class="form-field">                
                </div>
                <div class="form-row">
                    <button class="form-button new-expense-cancel" id="new-expense-cancel">Cancelar</button>
                    <button type="submit" class="form-button new-expense-confirm" id="new-expense-confirm" name="new-gasto">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($_GET['edit_form'])) : ?>
        <?php $editGastoId = $_GET['id']; ?>
        <div class="new-expense-overlay" id="edit-expense-overlay">
            <div class="new-expense-form">
                <span class="form-title">Editar Despesa</span>
                <form method="POST" action="home.php">
                    <input type="hidden" name="gastoId" value="<?php echo $editGastoId; ?>">
                    <div class="form-row">
                        <label for="edit-expense-name" class="form-label">Nome:</label>
                        <input type="text" id="edit-expense-name" name="nomeDoGasto" class="form-field" value="<?php echo $gasto['nome']; ?>">
                    </div>
                    <div class="form-row">
                        <label for="edit-expense-value" class="form-label">Valor:</label>
                        <input type="number" id="edit-expense-value" name="valorDoGasto" class="form-field" value="<?php echo $gasto['valor']; ?>">
                    </div>
                    <div class="form-row">
                        <label for="edit-expense-date" class="form-label">Data:</label>
                        <input type="date" id="edit-expense-date" name="dataDoGasto" class="form-field" value="<?php echo $gasto['data_gasto']; ?>">
                    </div>
                    <div class="form-row">
                        <label for="edit-expense-types" class="form-label">Categoria</label>
                        <input type="text" id="edit-expense-types" name="categoriaDoGasto" class="form-field" value="<?php echo $gasto['categoria']; ?>">
                    </div>
                    <div class="form-row">
                        <button class="form-button new-expense-cancel" id="edit-expense-cancel">Cancelar</button>
                        <button type="submit" class="form-button new-expense-confirm" id="edit-expense-confirm" name="edit-gasto">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script>
        $(document).ready(function() {
            $('#expenses-table').DataTable();
        });

        // Abre o formulário de novo gasto
        document.getElementById('new-expense-btn').addEventListener('click', function() {
            document.getElementById('new-expense-overlay').classList.remove('hidden');
        });

        // Fecha o formulário de novo gasto
        document.getElementById('new-expense-cancel').addEventListener('click', function() {
            document.getElementById('new-expense-overlay').classList.add('hidden');
        });

        // Fecha o formulário de edição de gasto
        document.getElementById('edit-expense-cancel').addEventListener('click', function() {
            document.getElementById('edit-expense-overlay').classList.add('hidden');
        });


        





    </script>
    <script>
    // Função para obter os valores dos gastos da tabela
    function getGastoValores() {
        var gastoValores = [];
        <?php foreach ($gastos as $gasto) : ?>
            gastoValores.push(<?php echo $gasto['valor']; ?>);
        <?php endforeach; ?>
        return gastoValores;
    }

    // Função para criar o gráfico de percentual de gastos
    function createPercentChart() {
        var gastoValores = getGastoValores();
        var totalGastos = gastoValores.reduce(function(a, b) {
            return a + b;
        }, 0);

        var percentuais = gastoValores.map(function(valor) {
            return ((valor / totalGastos) * 100).toFixed(2);
        });

        var ctx = document.getElementById('percent-chart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: percentuais,
                datasets: [{
                    label: 'Percentual de Gastos',
                    data: gastoValores,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#8d66b3',
                        '#ff9f40',
                        '#00a7e1',
                        '#f77e7e',
                        '#00c897',
                        '#ffba00',
                        '#935ea5'
                    ]
                }]
            }
        });
    }

    // Função para criar o gráfico de gastos por categoria
    function createCategoryChart() {
        var gastoCategorias = [];
        <?php foreach ($gastos as $gasto) : ?>
            var categoria = '<?php echo $gasto['categoria']; ?>';
            var valor = <?php echo $gasto['valor']; ?>;

            var existingCategory = gastoCategorias.find(function(item) {
                return item.categoria === categoria;
            });

            if (existingCategory) {
                existingCategory.valor += valor;
            } else {
                gastoCategorias.push({ categoria: categoria, valor: valor });
            }
        <?php endforeach; ?>

        var categorias = gastoCategorias.map(function(item) {
            return item.categoria;
        });

        var valoresPorCategoria = gastoCategorias.map(function(item) {
            return item.valor;
        });

        var ctx = document.getElementById('category-chart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: categorias,
                datasets: [{
                    label: 'Gastos por Categoria',
                    data: valoresPorCategoria,
                    backgroundColor: '#36A2EB'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Chamar as funções para criar os gráficos
    createPercentChart();
    createCategoryChart();
</script>

</body>
</html>
