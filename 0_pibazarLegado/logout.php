<?php
session_start();
session_destroy();
setcookie("user_name", "", time() - 3600);
setcookie("perfil", "", time() - 3600);
header('Location: index.php?page=login');
exit();


?>