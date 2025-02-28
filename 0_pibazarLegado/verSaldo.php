<?php
// Incluindo a conexão com o banco de dados
include('conexao.php');

// Iniciar a sessão para pegar o ID do usuário, se necessário
session_start();

// Variável para armazenar o saldo
$saldo = 0;

// Verificar o email do usuário logado (ou outra identificação)
if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];

    // Consulta para buscar o saldo (coin) do usuário
    $query = "SELECT coin FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->bind_result($saldo);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Usuário não está logado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="forms.css">
    <title>Visualizar Saldo</title>
</head>
<body>
    <div class="form-container">
        <h2>Saldo Atual</h2>
        
        <img src="imgs/senacoin.webp" alt="Imagem de uma Moeda">

        <p>Seu saldo atual é:</p>
        <h3><?php echo $saldo; ?> SenaCoins</h3>

        <button type="button" onclick="location.reload();">Atualizar Saldo</button> 
    </div>
</body>
</html>
