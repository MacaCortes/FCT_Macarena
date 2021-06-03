<?php
/*una vez comprobado por funciones de js que los datos introducidos son correctos pasamos a introducirlos a la base de datos
 */ 

spl_autoload_register(function ($clase) {
  require_once "$clase.php";
});

$dao_u=new DAOusuario();
$dni= filter_input(INPUT_POST, 'dni');
$nombre= filter_input(INPUT_POST, 'nombre');
$apellido= filter_input(INPUT_POST, 'apellido');
$direccion= filter_input(INPUT_POST, 'direccion');
$direccion.= " num " .filter_input(INPUT_POST, 'num');
$direccion.= " piso " .filter_input(INPUT_POST, 'piso');
$direccion.= " CP " .filter_input(INPUT_POST, 'codigo_postal');
$telefono= filter_input(INPUT_POST, 'telefono');
$pass= filter_input(INPUT_POST,'pass');
$email= filter_input(INPUT_POST, 'email');
$tipo_u= filter_input(INPUT_POST, 'tipo_u');
//el ususario comun no tiene la posivilidad de poter tipo de ususario por lo que se le asignara el 1 que es el usuario comun
 if(isset($_POST['enviar'])){  
        if($tipo_u==""){
        $tipo_u="1";
        $usuario=new usuario($dni, $nombre, $apellido, $direccion, $telefono,$email, $pass,$tipo_u);
        $mensaje=$dao_u->insertar($usuario);
        header("location:../index.php?mensaje=$mensaje");
          exit(); 
          // en caso de que el administrador acceda al formulario, el si que pone el tipo de ususario
        }else{
          $usuario=new usuario($dni, $nombre, $apellido, $direccion, $telefono,$email, $pass, $tipo_u);
          $mensaje=$dao_u->insertar($usuario);
          header("location:../gestion_admin/Trabajadores.php?mensaje=$mensaje");
          exit();
          
        }
   }  


if(isset($_POST['comprobar'])){    
    //llama al metodo de clase ususario par comprobar si ese ususario existe
    $dni=($_POST["dni1"]);
    $pass= ($_POST["pass1"]);  

    //$dao_u->comprueba($dni,$pass);
    
       $valor= $dao_u->comprueba($dni,$pass);  
    //si el ususario existe comprobamos que usuario es ya que podenos coger  ;      
        if( $valor===false){           
             $ok=false;
             $mensaje="usuario NO REGISTRADO : INCORRECTO";   
             header("location:../index.php?mensaje=$mensaje");
             exit(); 
                
        }elseif($valor===true){            
            if(isset($_SESSION['login'])){            
            $tipo_u=$_SESSION['login']['tipo_u'];
                      
            $dao_u->mandar($tipo_u);
            }else{
              header("location:../index.php");
             exit();   
            
            }
        }//fin de  if($usuario->comprueba($usuario)==true){  
        
}//fin de if(isset($_POST['comprobar'])){ 

 
    
if(isset($_POST['volver'])){
    header('location:../index.php');
    exit();
}

