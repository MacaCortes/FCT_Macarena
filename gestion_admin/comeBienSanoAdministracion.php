<?php

spl_autoload_register(function ($clase) {
    require_once "../clases/$clase.php";
});
require '../clases/funciones.php';


//este mensaje llega al insertar un nuevo trabajador
$men = filter_input(INPUT_GET, 'mensaje');
$mensaje="<h2 class='orange'style='padding: 0.5em;'>".$men."</h2>";
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


//lista tabla de todas los platos , todas las recetas y todos los pedidos
$tabla = $dao_p->listar();
$tablaR=$dao_receta->listarRecetas();
$tablaP=$dao_pedido->listarPedidoCompleto();


//$mensaje="<h2 class='orange'style='padding: 0.5em;'>revisa los contenidos que salen aqui!!</h2>";
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
                <div class="col-md-4">                   
                <img  src="../imagenes/logoAppGestion.png" class="imgestion" alt="logo gestion comeBiensano" style="margin-top: 3em">
                </div>
                <div class="col-md-4">
                    <h1 class="orange text-center paddingbtn" > WEB ADMINISTRACIÓN</h1>
                    <h2 class="text-center"><?php echo $nombre ?></h2>
                </div>
               <div class="col-md-3"  style="margin-top:9em">
                <form class="paddingbtn "  action="" method="POST">                    
                    <input class=" btnapp " type="submit" name="cerrar_session" value="Cerrar Sesion" />
                </form>
               </div>
                
            </div>
        </header>
         <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" style="color:orange;" href="comeBienSanoAdministracion.php"><strong>Administración</strong></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a  class="nav-link" href="Platos_Admin.php">PLATOS</a>
                        <a class="nav-link" href="Recetas_Admin.php">RECETAS</a>
                        <a class="nav-link" href="Trabajadores.php" style="margin-right:5em;">TRABAJADORES</a>
                    </div>
                </div>
            </div>
        </nav>      
        
        <div class="container paddingbtn2">
            <div class="row"> 
                <div class="col-4">
                 <form   action="" method="POST" >
                            <input class="btnapp3" type="submit" style="height:2em; margin-left: 0.5em" name="btn" value='TODOS PLATOS'/>
                            <input class="btnapp3" type="submit" style="height:2em; margin-left: 0.5em"name="btn" value='TODAS RECETAS'/>
                            <input class="btnapp3" type="submit" style="height:2em; margin-left: 0.5em" name="btn" value='TODOS PEDIDOS'/>
                        </form>
                </div>
                    <div class="col-8">  
                 <?php
                    if (!isset($_POST['btn'])) { ?>
                    <h2>EL PODER DEL ADMINISTRADOR</h2>
                    <ul>
                        <li>Gestionar todos los platos</li>
                        <li>Gestionar todos las recetas</li>
                        <li>Gestionar todos los trabajadores</li><br/>
                        <h3 class="orange">También puede ver listado de:</h3>
                        <li> Todos platos</li>
                        <li>Todas la recetas</li>
                        <li>Todos los pedidos</li>
                    </ul>

                    </div>
                   <div class="col-8">  
                    <?php 
                    }
                    if (isset($_POST["btn"])) {
                                                
                       if ($_POST["btn"] == "ESTADISTICAS"):
                            ?>
                            <p>Estadisticas</p>

                            <?php
                        elseif ($_POST["btn"] == "TODOS PLATOS"):
                            echo "<h1>Listado de todos los PLATOS</h1>";
                            echo $tabla;
                         elseif ($_POST["btn"] == "TODAS RECETAS"):
                               echo "<h1>Listado de todas las  RECETAS</h1>";
                           echo $tablaR;
                         elseif ($_POST["btn"] == "TODOS PEDIDOS"):
                             echo "<h1>Listado de todos los PEDIDOS</h1>";
                            echo $tablaP;
                            ?>
                            <?php
                        endif;
                    }
                    ?>
                </div>
            </div>
        </div> 
        
        
        <script type="text/javascript" src="../js/javaScript.js"></script>
    </body>
</html>
