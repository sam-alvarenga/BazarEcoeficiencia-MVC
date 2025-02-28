<?php
include 'conexao.php';
session_start();

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['perfil'])) {

    header("Location: index.php?page=login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'verificar') {
            // Pega o doador do formulário
            $doador = strtoupper($_POST['doador']);

            // Proteção contra injeção SQL
            $doador = $conn->real_escape_string($doador);

            // Query para verificar o doador
            $sql = "SELECT * FROM usuarios WHERE email = '$doador'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Usuário encontrado                
                $usuario = $result->fetch_assoc();
                $doador = $usuario['email'];
                $coin = $usuario['coin'];
                $idDoador = $usuario['idUsuario'];
                $dadosDoador = "Doador: " . $doador . "<br>Quantidade atual de SenaCoins: " . $coin;
                setcookie("dadosDoador", $dadosDoador, time() + 1);
                setcookie("doador", $doador, time() + 3600);
                setcookie("coin", $coin, time() + 3660);
                setcookie("idDoador", $idDoador, time() + 3600);
                header('Location: index.php?page=doacao');
            } else {
                header('Location: index.php?page=doacao&msg=erro&num=6');
                exit;
            }
        } else if ($_POST['action'] === 'doar') {

            if ($_POST['categorias']!="") {                
                $idCategorias = $_POST['categorias'];
            }else{
                header('Location: index.php?page=doacao&msg=erro&num=9');
                exit;
            }
            $quantidade = $_POST['quantidade'];

            var_dump("veio do post" . $_POST['categorias'] . $_POST['quantidade']);

            // Depuração: Verifique se os dados estão sendo recebidos corretamente
            // echo "Nome: $nome, Email: $email, Senha: $senha"; exit;


            // Usar uma consulta preparada para evitar SQL Injection
            $stmt = $conn->prepare("INSERT INTO doacao (idUsuario) VALUES (?)");
            if ($stmt) {
                $stmt->bind_param("i", $_COOKIE['idDoador']);

                // Execute a consulta e verifique se foi bem-sucedida
                if ($stmt->execute()) {
                    /* echo "Cadastro realizado com sucesso!"; */
                    $idDoacao = $conn->insert_id; //pega ultimo id inserido

                    var_dump("ids para tabela doacao - doador e doacao" . $idDoacao . "  e  " . $_COOKIE['idDoador']);
                    /* Acrescenta doações - tab doacao e doacao produto*/
                    var_dump("ids para tabela doacaoProduto - doaacao produto e qtd" . $idDoacao . "  e  " . $idCategorias . "  e  " . $quantidade);
                    $stmt = $conn->prepare("INSERT INTO doacaoProduto (idDoacao,idProduto, quantidade) VALUES (?,?,?)");
                    $stmt->bind_param("iii", $idDoacao, $idCategorias, $quantidade);
                    $stmt->execute();
                    /*Ler o que tem no estoque */
                    $query = "SELECT quantidade,coin FROM produtos WHERE idProduto='$idCategorias'";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        // Usuário encontrado                
                        $produto = $result->fetch_assoc();
                        $quantidadeEmEstoque = $produto['quantidade'];
                        $coinProduto = $produto['coin'];
                        var_dump("tabela produto quanto tem no estoque?" . $quantidadeEmEstoque);
                    }
                    /*Alterando coin do doador*/
                    $stmt = $conn->prepare("UPDATE usuarios SET coin=(?) WHERE idUsuario=(?)");
                    var_dump("coinproduto" . $coinProduto . "qtd doada" . $quantidade);
                    $_COOKIE['coin'] += ($quantidade * $coinProduto);
                    var_dump("novo coin " . $_COOKIE['coin'] . "idUsuario doador" . $_COOKIE['idDoador']);
                    $stmt->bind_param("ii", $_COOKIE['coin'], $_COOKIE['idDoador']);
                    $stmt->execute();

                    /*Acrescentar produto no estoque de produto*/
                    $stmt = $conn->prepare("UPDATE produtos SET quantidade=(?) WHERE idProduto=(?)");
                    /*somando quantidade atual a antiga*/
                    $quantidade += $quantidadeEmEstoque;
                    $stmt->bind_param("ii", $quantidade, $idCategorias);
                    var_dump("nova quantidade que vai pra produto e o id do produto?" . $quantidade . "  e  " . $idCategorias);
                    $stmt->execute();

                    $stmt->close();
                    //Limpando os cookies
                    setcookie("dadosDoador", "", time() - 3600);
                    setcookie("doador", "", time() - 3600);
                    setcookie("coin", "", time() - 3660);
                    setcookie("idDoador", "", time() - 3600);

                    header('Location: index.php?page=doacao&msg=OK');
                    //Aqui, garantimos que o redirecionamento só ocorra após a execução com sucesso
                    exit;
                    // Garanta que o script seja interrompido após o redirecionamento */
                } else {
                    // Exibe um erro se a execução falhar
                    // echo "Erro na execução: " . $stmt->error;
                    $stmt->close();
                    header('Location: index.php?page=doacao&msg=erro&num=3');
                    exit;
                }
            }
        }
    }
}





// $coin = mysqli_real_escape_string($conn, $_POST["coin"]);
// $categorias = mysqli_real_escape_string($conn, $_POST["categorias"]);
// $quantidade = mysqli_real_escape_string($conn, $_POST["quantidade"]);

// $stmt = $conn->prepare("INSERT INTO produtos (coin, categorias, quantidade) VALUES (?, ?, ?)");
// $stmt->bind_param("ssi", $coin, $categorias, $quantidade);
// if ($stmt->execute()) {
//     echo "Novo registro criado com sucesso";
// } else {
//     echo "Erro: " . $stmt->error;
// }

// $stmt->close();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doação</title>
    <link rel="stylesheet" href="forms.css">
</head>

<body>
    <div class="form-container">

        <h2>Doação</h2>

        <form action="doacao.php" method="post">
            <div class="doador-area">
                <label for="login-doador">Login do doador:</label>
                <?php if (isset($_COOKIE['dadosDoador'])) { ?>
                    <input type="text" id="login-doador" name="doador" required value=<?php echo $_COOKIE['doador']; ?>
                        disabled>
                    <button type="submit" name="action" value="verificar">Verificar Doador</button>
                <?php } else {
                    ?>
                    <input type="text" id="login-doador" name="doador" required>
                    <button type="submit" name="action" value="verificar">Verificar Doador</button>
                <?php } ?>
            </div>

            <?php
            if (isset($_COOKIE['dadosDoador'])) {
                echo '<div class="doador-found">';
                echo $_COOKIE['dadosDoador'];
                echo "</div>";
                ?>
                <label for="classificacao">Classificação do Objeto Doado:</label>
                <select name="categorias" id="categorias">
                    <option selected disable value=""> Selecionar categoria</option>
                    <option value="1">Acessórios - $ 3</option>
                    <option value="2">Livros | DVD | CD | Disco - $ 3</option>
                    <option value="3">Utensílios de Cozinha - $ 3</option>
                    <option value="4">Artigos de Decoração - $ 3</option>
                    <option value="5">Vestuário e Calçados - $ 5</option>
                    <option value="6">Brinquedos e Jogos - $ 5</option>
                    <option value="7">Artigos Automotivos - $ 6</option>
                    <option value="8">Eletrônicos e Eletrodomésticos - $ 10</option>
                    <option value="9">Mochilas - $ 10</option>
                </select>

                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" min="1" value="1" required>
                <button type="submit" name="action" value="doar">Doar</button>
            <?php } ?>



        </form>


    </div>
</body>

</html>