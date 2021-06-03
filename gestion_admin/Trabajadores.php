<?php

spl_autoload_register(function ($clase) {
    require_once "../clases/$clase.php";
});
require '../clases/funciones.php';
$mensaje="";
$men = filter_input(INPUT_GET, 'mensaje'); 
if(isset($men)){
$mensaje= "<h2 class='orange'>".$men."</h2>";
}
$dao_p= new DAOplato();
$dao_receta=new DAOreceta();
$dao_u=new DAOusuario();
$dao_pedido=new DAOpedido();
session_start();
$nombre = $_SESSION['loginAdmin']['nombre'];
//compruebo si la sesion de admin se ha iniciado.. si no no deberia estar aqui

if (!isset($_SESSION['loginAdmin'])) {
    header('location:../index.php');
    exit();
}

//si le deoy al boton de cerrar session me lleva a la web inicio
if (isset($_POST['cerrar_session'])) {
    session_unset($_SESSION['loginAdmin']);
    session_destroy();
    header('location:../index.php');
    exit();
}
// funciones con trbajadores :
$listatrabajadores=$dao_u->listarTrabajador();

if(isset($_POST['seleccionarU'])){
    $id_user=$_POST['usuario'];
    $modificar = "modificar";
    $tabla_mod = $dao_u->mostrarTablaDatos($id_user,$modificar);    
}
if (isset($_POST['modificar'])) { 
    $id_user=$_POST['id_usuario'];
    $nombre =$_POST['nombre']; 
   // $_SESSION['loginUsuario']['nombre']=$nombre;
    $apellidos = $_POST['apellido'];
   // $_SESSION['loginUsuario']['apellidos']=$apellidos;
    $direccion = $_POST['direccion'];
   // $_SESSION['loginUsuario']['direccion']=$direccion;
    $email = $_POST['email'];
   /// $_SESSION['loginUsuario']['email']=$email;
     $telefono = $_POST['telefono'];
   // $_SESSION['loginUsuario']['telefono']=$telefono;
  //  var_dump($_SESSION['loginUsuario']);   
    $men=$dao_u->modificar($id_user, $nombre,$apellidos,$direccion,$email,$telefono);
    $mensaje= "<h2 class='orange'>".$men."</h2>";
}
if (isset($_POST['borrar'])) { 
      $id_user=$_POST['id_usuario'];
      echo $id_user;
      if($id_user!='16'){
      $men=$dao_u->eliminarUsuario($id_user);
      $mensaje= "<h2 class='orange'style='padding: 0.5em;'>".$men."</h2>";
      }else{
         $mensaje= "<h2 class='orange'style='padding: 0.5em;'> El administrador pricipal no se puede eliminar Mambrú</h2>";  
      }
      
}
?>

<!DOCTYPE html>

<!--

-->
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta name="autor" content="macarena cortes" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lucida Handwriting">
        <link rel="stylesheet" href="../css/estilo.css" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"/>
        <title>Web ComeBienSano Administracion </title>
    </head>
    <body>
        <header class=" banner">
         
            <div class="row justify-content-md-center">               
                <div class="col-4">                   
                <img  src="../imagenes/logoAppGestion.png" class="imgestion" alt="logo gestion comeBiensano" style="margin-top: 3em">
                </div>
                <div class="col-4">
                    <h1 class="orange text-center paddingbtn" > WEB ADMINISTRACIÓN TRABAJADORES</h1>
                    <h2 class="text-center"><?php echo $nombre ?></h2>
                </div>
               <div class="col-3"  style="margin-top:9em">
                <form class="paddingbtn "  action="" method="POST">                    
                    <input class=" btnapp " type="submit" name="cerrar_session" value="Cerrar Sesion" />
                </form>
               </div>
                
            </div>
        </header>
        
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="comeBienSanoAdministracion.php">Administración</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link"  aria-current="page" href="Platos_Admin.php">PLATOS</a>
                        <a  class="nav-link" href="Recetas_Admin.php">RECETAS</a>
                        <a class="nav-link active" style="color:orange;" href="#"><strong>TRABAJADORES</strong></a>
                        
                    </div>
                </div>
            </div>
        </nav>


        <div class="container paddingbtn2">
            <div class="row">
                <div class="col-4">
                    <form   id="formtra" action="" method="POST" >
                        <input class="btnapp" type="submit" name="btn" value='AÑADIR TRABAJADOR'/>
                        <input class="btnapp" type="submit" name="btn" value='MODIFICAR / BORRAR'/>
                                                               
                    </form>
                </div>
                
                <div class="col-8">
                    <?php
                    if (!isset($_POST['btn'])) {
                        if (($mensaje != "") && (!isset($tabla_mod))) {
                            echo" <div class='sombra_svg'>";
                            echo "<span>" . isset($mensaje) ? $mensaje : "" . "</span> </div>";
                            echo "</div>";
                          
                        } else {
                            echo " <h2>GESTION DE TRABAJADORES</h2>";
                            echo "<p> Elige entre las opciones de la izquierda</p>";
                            echo "<p> Puedes gestionar los platos a tu antojo, simpre con moderacion y cuidadin</p>";
                             echo isset($tabla_mod) ? $tabla_mod : "";
                        }
                        
                    }

                    if (isset($_POST['btn'])) {               
                     if ($_POST["btn"] == "AÑADIR TRABAJADOR"):
                         ?>                          
                    <h1>añadir trabajador</h1>
                    <p>Rellena el formulario con los datos del nuevo trabajador</p>
                    <p>Recuerdale luego que se tiene que pagar una caña en el bar!!<br/>
                    es una norma de la empresa!!</p>
                          
                      <?php
                            $tipo = "si";
                            crearform($tipo);
                       ?>
                    <?php
                     elseif ($_POST["btn"] == "MODIFICAR / BORRAR"):
                            ?>
                            <h3 class="orange">Elige trabajador para cambiar datos?</h3>
                            <?php
                            echo $listatrabajadores;
                           
                            
                     endif;
                    }
                    ?>
                </div>
        
            </div>
    </body>
</html>
