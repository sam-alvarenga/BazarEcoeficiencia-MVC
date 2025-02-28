<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Programa Ecoeficiência</title>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <img src="imgs/ecoeficiencia.png" alt="Programa Ecoeficiência" class="logo">
            <?php echo isset($_COOKIE['user_name']) ? "Logado " . $_COOKIE['user_name'] : ""; ?>
            <nav>
                <ul>
                    <?php echo '<li><a href="?page=login">Login</a></li>' ?>
                    <?php if (isset($_COOKIE['perfil'])) { ?>
                        <?php echo $_COOKIE['perfil'] == "adm" || $_COOKIE['perfil'] == "colaborador" ? '<li><a href="?page=cadastro">Cadastro de Usuários</a></li>' : "" ?>
                        <?php echo $_COOKIE['perfil'] == "adm" || $_COOKIE['perfil'] == "colaborador" ? '<li><a href="?page=doacao">Cadastro de Doação</a></li>' : "" ?>
                        <?php echo $_COOKIE['perfil'] == "adm" || $_COOKIE['perfil'] == "colaborador" ? '<li><a href="?page=troca">Troca</a></li>' : "" ?>
                        <?php echo isset($_COOKIE['perfil']) ? '<li><a href="?page=verSaldo">Visualizar Saldo</a></li>' : "" ?>
                        <?php echo $_COOKIE['perfil'] == "adm" ? '<li><a href="?page=relatorio">Relatórios</a></li>' : "" ?>
                        <?php echo isset($_COOKIE['user_name']) ? '<li><a href="?page=logout">Sair</a></li>' : "" ?>
                    <?php } ?>
                </ul>
            </nav>
        </div>



        <div class="main-content">

            <?php

            // verifica se uma variável de query string chamada msg está presente na URL e, se estiver, se o valor dela é igual a 'OK'.
            //Ser ambas condições forem verdadeiras exibe a mensagem "Cadastro realizado com Sucesso"
            if (isset($_GET['msg'])) {
                if ($_GET['msg'] == 'OK') {
                    echo '<span class="msg msgok">Cadastro realizado com Sucesso</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '1') {
                    echo '<span class="msg msgerr">A senha deve ter exatamente 4 dígitos numéricos.</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '2') {
                    echo '<span class="msg msgerr">Perfil de usuário desconhecido!</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '3') {
                    echo '<span class="msg msgerr">Erro na execução!</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '4') {
                    echo '<span class="msg msgerr">Erro na preparação da declaração!</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '5') {
                    echo '<span class="msg msgerr">Login ou senha inválidos!</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '6') {
                    echo '<span class="msg msgerr">Doador não cadastrado!</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '7') {
                    echo '<span class="msg msgerr">Ops! Parece que seus coins acabaram ou são insuficientes para 
                    a compra!</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '8') {
                    echo '<span class="msg msgerr">Item indisponível em estoque!</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '9') {
                    echo '<span class="msg msgerr">Nenhum item selecionado!</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '10') {
                    echo '<span class="msg msgerr"> Senhas não coincidem</span>';
                } elseif ($_GET['msg'] == 'erro' && $_GET['num'] == '11') {
                    echo '<span class="msg msgerr"> Usuário já cadastrado!</span>';
                }
            }








            $page = isset($_GET['page']) ? $_GET['page'] : 'login';
            include "$page.php";
            ?>

        </div>
    </div>
</body>

</html>