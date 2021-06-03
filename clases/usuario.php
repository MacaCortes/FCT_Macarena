<?php
//clase de ususarios
//equire"conexion.php";
spl_autoload_register(function ($clase) {
  require_once "$clase.php";
});
//session_start();
class usuario  {
//private $id_usuario;
private $dni;
private $nombre;
private $apellido;
private $direccion;
private $telefono;
private $email;
private $pass;
private $tipo_u;
const TABLA= 'usuarios';

public function __construct($dni,$nombre,$apellido,$direccion,$telefono,$email,$pass,$tipo_u){
    // $this->id_usuario=$id_usuario;
     $this->dni=$dni;
     $this->nombre=$nombre;
     $this->apellido=$apellido;
     $this->direccion=$direccion;
     $this->telefono=$telefono;
     $this->email=$email;
     $this->pass=$pass;
     $this->tipo_u= $tipo_u;
    }//fin metodo constructor
    public function getid_usuario() {
        return $this->id_usuario;
    }

    public function getDni() {
        return $this->dni;
    }

    public function getNombre(){
          return $this->nombre;
    }
     public function getApellido(){
          return $this->apellido;
    }
     public function getDireccion(){
          return $this->direccion;
    }
     public function getTelefono(){
          return $this->telefono;
    }
     public function getEmail(){
          return $this->email;
    }
     public function getPass(){
          return $this->pass;
    }
      public function gettipo_u(){
          return $this->tipo_u;
    }
    
    public function setid_usuario($dnid) {
        $this->id_usuario = $dnid;
    }
    public function setDni($dninew) {
        $this->dni = $dninew;
    }

    public function serNombre($nombrenew) {
        $this->nombre = $nombrenew;
    }

    public function setApellido($apellidonew) {
        $this->apellido = $apellidonew;
    }

    public function setDireccion($direccionnew) {
        $this->direccion = $direccionnew;
    }

    public function setTelefono($telefononew) {
        $this->telefono = $telefononew;
    }

    public function setEmail($emailnew) {
        $this->email = $emailnew;
    }

    public function setPass($passnew) {
        $this->pass = $passnew;
    }

public function __toString() {
    echo $this->nombre;    $this->tipo_u;
}
  
            
        
   
}//fin de class