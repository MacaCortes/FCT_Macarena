<?php
require 'clases/funciones.php';
spl_autoload_register(function ($clase) {
  require_once "clases/$clase.php";
});
session_start();
$dao_u=new DAOusuario();
$dao_receta=new DAOreceta();
if(!isset(($_SESSION['loginUsuario']))){
   header('location:../index.php');
   exit(); 
}

$dni = $_SESSION['loginUsuario']['DNI'];
$id_usuario = $_SESSION['loginUsuario']['id_usuario'];
$dao_pedido = new DAOpedido();
$modificar = "modificar";
$tabla_mod = $dao_u->mostrarTablaDatos($id_usuario,$modificar);
$tabla=$dao_pedido->listarPedido($id_usuario);
$tablaconLinks=$dao_receta->listarLinkreceta($id_usuario);
if (isset($_POST['modificar'])) {    
    $nombre =$_POST['nombre']; 
    $_SESSION['loginUsuario']['nombre']=$nombre;
    $apellidos = $_POST['apellido'];
    $_SESSION['loginUsuario']['apellidos']=$apellidos;
    $direccion = $_POST['direccion'];
    $_SESSION['loginUsuario']['direccion']=$direccion;
    $email = $_POST['email'];
    $_SESSION['loginUsuario']['email']=$email;
    $telefono = $_POST['telefono'];
    $_SESSION['loginUsuario']['telefono']=$telefono;
  //  var_dump($_SESSION['loginUsuario']);   
    $mensaje=$dao_u->modificar($id_usuario, $nombre,$apellidos,$direccion,$email,$telefono);
   
}
if (isset($_POST['borrar'])) { 
      $id_user=$_POST['id_usuario'];
     // echo $id_user;
      $pedidos=$dao_pedido->comprobarPedidos($id_usuario);
      if($pedidos== true){
      $men=$dao_u->eliminarUsuario($id_user);
      $mensaje= "<h2 class='orange'>".$men."</h2> <p>Esperamos que te vaya bonito </p>";
       $borrar="borrado";
       $_SESSION['borrar']=$borrar;
      }else{
          $mensaje ="Tienes pedidos pendiente de momento no te puedes dar de Baja";
      }
      
}
 

?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="autor" content="macarena cortes" /> 

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lucida Handwriting">
    <link rel="stylesheet" href="/css/estilo.css" />
    <link rel="stylesheet" href="css/fontawesome/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"/>
    <title>Datos de ususario </title>
    </head>
    <body>
        <div class="container-fluid">

            <nav class="navbar navbar-light bg-light">
                
                    <div class="col-4">
                        <img src="imagenes/logopngMini.png" width="200" height="100" class="d-inline-block align-top" alt="">
                    </div>
                    <div class="col-8">
                        <h2 class="orange"> Datos de Usuario </h2>   
                        <h2>Muy buenas <?php echo $_SESSION['loginUsuario']['nombre'] ?>
                    </div>  
               
            </nav>
            <div class="conten3 paddingbtn1">

                <div class="row">
                    <div class="col-3 justify-content-md-center paddingbtn" style="margin: 2em;">                
                        <form class="paddingbtn " action="" method="POST">
                            <button type="button" class="btn btn-outline-warning  paddingbtn2" name="cerrarVentana" onclick="closeWin()" >Cerrrar</button><br/><br/>
                            <?php if(!isset($_SESSION['borrar'])){ ?>
                            <input type="submit" class="btn btn-outline-info paddingbtn2" name="btn" value="Cambiar datos / Dar de Baja"><br/>
                            <input type="submit" class="btn btn-outline-warning paddingbtn2 "  name="btn" value="Últimos pedidos">  <br/>      
                           <?php  } ?>
                        </form>
                    </div>
                    <div class="col-8 paddingbtn">
                        <div class="text-justify paddingbtn2">
                            <div class="conten3 paddingbtn">                   
                                <p>Estos son tus datos que insertarte al registrarte,<br/>
                                    puedes cambiar algun dato si no son correctos o habido algún cambio en tu vida.
                                </p>
                            </div>
                            <?php
                            if (!isset(($_POST['btn']))) {
                                if (!isset($_POST['btn']) && (!isset($mensaje))):
                                    $tabla = $dao_u->mostrarTablaDatos($id_usuario);
                                    echo $tabla;
                                elseif (isset($mensaje) && $mensaje == "Actualizado !"):
                                    isset($mensaje) ? $mensaje : "";
                                    echo "<h1>$mensaje</h1>";
                                    $tabla = $dao_u->mostrarTablaDatos($id_usuario);
                                    echo $tabla;
                                  elseif (isset($mensaje) && $mensaje != "Actualizado !"):
                                        echo "<h1>$mensaje</h1>";
                                endif;
                            } elseif (isset(($_POST['btn']))) {
                                if (($_POST["btn"] == "Cambiar datos / Dar de Baja")):
                                    ?>
                                    <div class="conten3 paddingbtn">                           
                                        <h2>Modificar datos</h2>                                          
                                        <p>Puedes modificar tus datos personales,si cambias de direccion o teléfono....<br/>
                                            Porfavor asegurate que tus cambios son correctos antes de mandar los nuevos datos. </p>
                                    </div>
                                    <?php
                                    echo $tabla_mod;
                                    echo isset($mensaje) ? $mensaje : "";
                                elseif ($_POST["btn"] == "Últimos pedidos"):
                                    ?>
                                    <h2>Pedidos</h2>                         
                                    <?php
                                    echo $tabla;
                                    echo $tablaconLinks;
                                endif;
                            } 
                           
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
        function closeWin(){        
                window.close();
            }
          
            </script>

        </body>
        </html>