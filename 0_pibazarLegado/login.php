<?php

include 'conexao.php';

session_start();


// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pega o email e a senha do formulário
    $login = strtoupper($_POST['login']);
    $senha = $_POST['senha'];


    // Proteção contra injeção SQL
    $login = $conn->real_escape_string($login);
    $senha = $conn->real_escape_string($senha);

    // Query para verificar o email
    $sql = "SELECT * FROM usuarios WHERE email = '$login'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Usuário encontrado
        $usuario = $result->fetch_assoc();

        // Verifica se a senha fornecida corresponde ao hash no banco de dados
         if (password_verify($senha, $usuario['senha'])) {
        // A senha está correta, redireciona conforme o perfil
        switch ($usuario['perfil']) {
            case 'colaborador':
                $_SESSION['usuario_id'] = ($usuario['idUsuario']);; // Armazena o ID do usuário na sessão
                $_SESSION['perfil'] = ($usuario['perfil']); // Armazena o perfil do usuário na sessão
                $_SESSION['login'] = ($usuario['email']); // Armazena o email (login) do usuário na sessão
                setcookie("user_name", $_SESSION['login'], time() + (86400 * 30));
                setcookie("perfil", $_SESSION['perfil'], time() + (86400 * 30));
                header("Location: index.php?page=doacao");
                exit();

            case 'adm':
                $_SESSION['usuario_id'] = ($usuario['idUsuario']);
                $_SESSION['perfil'] = ($usuario['perfil']);
                $_SESSION['login'] = ($usuario['email']);
                setcookie("user_name", $_SESSION['login'], time() + (86400 * 30));
                setcookie("perfil", $_SESSION['perfil'], time() + (86400 * 30));
                header("Location: index.php?page=relatorio");
                exit();
            case 'doador':

                $_SESSION['usuario_id'] = ($usuario['idUsuario']);
                $_SESSION['perfil'] = ($usuario['perfil']);
                $_SESSION['login'] = ($usuario['email']);
                setcookie("user_name", $_SESSION['login'], time() + (86400 * 30));
                setcookie("perfil", $_SESSION['perfil'], time() + (86400 * 30));
                header("Location: index.php?page=verSaldo");
                exit();
            default:
                header('Location: index.php?login=cadastro&msg=erro&num=2');
                exit();
        }


        } else {
            header('Location: index.php?page=login&msg=erro&num=5');
            exit();
        }
    } else {
        header('Location: index.php?page=login&msg=erro&num=5');
        exit();
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="forms.css">

    <title>Login Colaboradores</title>
</head>

<body>

    <div class="form-container">
        <h2>Login</h2>

        <form method="post">
            <label for="login"></label>
            <input placeholder="Digite usuário de login" id="login" name="login" required>
            <label for="senha"></label>
            <input type="password" placeholder="Senha" id="senha" name="senha" required>
            <button type="submit">Entrar</button>
        </form>

    </div>

    </div>
</body>


</html>