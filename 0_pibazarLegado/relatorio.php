<?php
include 'conexao.php';
 
session_start();
 
if(!isset($_SESSION['usuario_id']) || !isset($_SESSION['perfil'])) {
    header("Location: index.php?page=login");
    exit;
}
 
if ($_SESSION['perfil'] != "adm"){
    header("Location: index.php?page=verSaldo");
    exit;
}
 
// Consultas SQL
$query1 = "SELECT
        u.nome AS nome_usuario,
        SUM(dp.quantidade) AS total_doado
    FROM
        usuarios u
    JOIN
        doacao d ON u.idUsuario = d.idUsuario
    JOIN
        doacaoProduto dp ON d.idDoacao = dp.idDoacao
    GROUP BY
        u.idUsuario, u.nome
    ORDER BY
        total_doado DESC
    LIMIT 3;";
 
$query2 = "SELECT SUM(dp.quantidade) AS total_quantidade_doada FROM doacaoProduto dp";
 
$query3 = "SELECT SUM(tp.quantidade) AS total_trocas FROM trocaProduto tp";
 
$query4 = "SELECT
    categorias AS categoria,
    SUM(quantidade) AS total_quantidade
    FROM
    produtos
    GROUP BY
    categorias";
 
$query5 = "SELECT
    p.categorias,
    SUM(tp.quantidade) AS total_itens_trocados
    FROM
    troca t
    JOIN trocaProduto tp ON t.idTroca = tp.idTroca
    JOIN produtos p ON tp.idProduto = p.idProduto
    GROUP BY
    p.categorias";
 
// Executa as consultas e verifica erros
$result1 = mysqli_query($conn, $query1) or die("Erro na consulta 1: " . mysqli_error($conn));
$result2 = mysqli_query($conn, $query2) or die("Erro na consulta 2: " . mysqli_error($conn));
$result3 = mysqli_query($conn, $query3) or die("Erro na consulta 3: " . mysqli_error($conn));
$result4 = mysqli_query($conn, $query4) or die("Erro na consulta 4: " . mysqli_error($conn));
$result5 = mysqli_query($conn, $query5) or die("Erro na consulta 5: " . mysqli_error($conn));
 
// Processa os resultados
$maisDoaram = [];
while ($row = mysqli_fetch_assoc($result1)) {
    $maisDoaram[] = $row;
}
 
$totalDoacoes = mysqli_fetch_assoc($result2)['total_quantidade_doada'] ?? 0;
$totalTrocados = mysqli_fetch_assoc($result3)['total_trocas'] ?? 0;
 
$produtosSobra = []; // Armazena dados para lista
while ($row = mysqli_fetch_assoc($result4)) {
    $produtosSobra[] = $row;
}
 
$categorias = []; // Armazena as categorias e quantidades de troca
while ($row = mysqli_fetch_assoc($result5)) {
    $categorias[] = $row;
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Ecoeficiência</title>
    <link rel="stylesheet" href="relatorio.css">
</head>
 
<body>
    <div class="report-container">
        <h1>Relatórios</h1>
        <ul class="report-list">
            <li><strong>Top 3 pessoas que mais doaram:</strong>
                <ul>
                    <?php
                    foreach ($maisDoaram as $doador) {
                        echo "<li>" . $doador['nome_usuario'] . " (" . $doador['total_doado'] . " doações)</li>";
                    }
                    ?>
                </ul>
            </li>
            <li><strong>Quantidade total de produtos doados: </strong><?php echo $totalDoacoes; ?></li>
            <li><strong>Quantidade total de produtos trocados: </strong><?php echo $totalTrocados; ?></li>
            <li><strong>Quantidade de produtos em sobra:</strong>
                <ul>
                    <?php
                    foreach ($produtosSobra as $produto) {
                        echo "<li>" . $produto['categoria'] . ": " . $produto['total_quantidade'] . "</li>";
                    }
                    ?>
                </ul>
            </li>
            <li><strong>Quantidade de trocas por categoria:</strong>
                <ul>
                    <?php
                    foreach ($categorias as $categoria) {
                        echo "<li>" . $categoria['categorias'] . ": " . $categoria['total_itens_trocados'] . " trocas</li>";
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </div>
</body>
 
</html>
 
<?php
mysqli_close($conn);
?>
 