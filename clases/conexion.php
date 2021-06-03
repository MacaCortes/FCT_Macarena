<?php
class Conexion extends PDO { 
   private $tipo_de_base = 'mysql';
   private $host = 'localhost';
   private $nombre_de_base = 'comebiensano';
   private $usuario = 'comebiensano';
   private $contrasena = 'comebiensano'; 
   
   public function __construct() {
      //Sobreescribo el método constructor de la clase PDO.
      try{
         parent::__construct("{$this->tipo_de_base}:dbname={$this->nombre_de_base};host={$this->host};charset=utf8", $this->usuario, $this->contrasena);
      }catch(PDOException $e){
         echo 'Ha surgido un error y no se puede conectar a la base de datos. Detalle: ' . $e->getMessage();
         exit;
      }
   } 
 } 



/*
require("config.php");

class Conexion{
    protected $conexion_db;
    
    
    //creamos el constructor de esta clase
    public function __construct(){
       /*
        $this-> conexion_db= new mysqli(DB_HOST, DB_USUARIO,DB_CONTRA,DB_NOMBRE);
        //si la conexion no tiene exito
        if($this->conexion_bd->connect_errno){
            echo "Fallo en la conexion a MySql". $this->conexion_bd->connect_error;
            return;
        }
        $this->conexion_bd->set_charset(DB_CHARSET);
    
    *//*
    //conexion PDO
    try{
      $this->conexion_db=new PDO ('mysql:host=localhost ; db_name=datos_usuarios','root','');
      $this->conexion_db->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
      $this->conexion_db->exec("SET CHARACTER SET utf8");
      return $this->conexion_db;
    } catch (Exception $ex) {
  echo "No se ha conectado(Class conexion ):la linea de error de conexion es: " . $ex->getLine();
    }
    
    
    } 
} //fin class

*/