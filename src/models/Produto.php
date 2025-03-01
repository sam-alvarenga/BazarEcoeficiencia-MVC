<?php
namespace src\models;
use \core\Model;

class Produto extends Model {  

    public int $idProduto;
    public int $coin;
    public string $categorias;
    public int $quantidade;


}

?>