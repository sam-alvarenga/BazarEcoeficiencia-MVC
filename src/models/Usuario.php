<?php

namespace src\models;
use \core\Model;

class Usuario extends Model{

    public int $idUsuario;
    public string $nome;
    public string $email;
    public string $senha;
    public string $perfil;
    public int $senaCoins;

}