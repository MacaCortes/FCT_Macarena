<?php

class SesionLogin{   
   private $dni;
   private $pass;  
   public function __construct($dni,$pass){
       $this->dni=$dni;
       $this->pass=$pass;
   }
   
   public function iniciarSesion(){
       session_start();
       $_SESSION['login']=array($this->dni=> $this->pass);
   }
   
   public function cerrarSesion(){
       session_unset($_SESSION['login']);
       session_destroy();
       
       header("location:../index.php");
       exit();
       
   }
   
    
}//fin cerrar Session

