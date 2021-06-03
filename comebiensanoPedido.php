<?php
require 'clases/funciones.php';
spl_autoload_register(function ($clase) {
    require_once "clases/$clase.php";
});

session_start();
if (!isset(($_SESSION['loginUsuario']))) {
    exit(header("location:index.php"));
}
$ok = true;
$dao_pedido = new DAOpedido();

if (isset($_POST['volver'])) {
    exit(header("location:index.php?ok=$ok"));
}

if (isset($_POST['cerrar_session'])) {
    //$mensaje = " CERRAR SESSION.";
    session_unset($_SESSION['login']);
    session_destroy();
    $ok = false;
    header("location:index.php?ok=$ok");
    exit();
}

$mensaje = filter_input(INPUT_GET, 'mensaje');
if($mensaje=="menu"){

$fecha = $_SESSION['menu']['fecha'];
$id_usuario = $_SESSION['loginUsuario']['id_usuario'];
$precioTotal = $_SESSION['menu']['PrecioT'];
$arrayId_plato = $_SESSION['menu']['ID_platos'];
$nombreapto=$_SESSION['menu']['nombreapto'];
$id_pedido= $_SESSION['menu']['id_pedido'];
$listaplatos= $dao_pedido->listardetallesPlatospedido($id_pedido);

}elseif ($mensaje=="receta") {
    $id_pedido= $_SESSION['pedido_receta']['id_pedido'];   
    $pedidoreceta=$dao_pedido->listapedidoReceta($id_pedido);
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta name="autor" content="macarena cortes" />
        <link rel="stylesheet" href="css/estilo.css" />
        <link rel="stylesheet" href="css/fontawesome/css/all.css" />     
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"/>
        <title>Elegir menú</title>
    </head>
    <body>
        <header>
        <div class="row banner"style=" height: 180px">   
          
                <div class="col-md-3">
                    <div id="minilogo2 paddingbtn"><img src="imagenes/logopngMini.png" alt="LOgo ComeBienSano mini"/></div>
                </div>    
                <div class="col-md-6" style="margin-top:-1em;">
                    <h1 style="margin:2em"> A COMERRRR!!! </h1>                 
                </div>
                <div class="col-md-3">                   
                    <form class="paddingbtn "  action="" method="POST">
                        <input class=" btnapp " type="submit" name="volver" value="VOLVER A LA WEB"/>              
                        <input class=" btnapp "type="submit" name="cerrar_session" value='Cerrar Sesión' />
                    </form> 
                </div>
          
        </div>  
        </header>
        <section>
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-12 " style="margin-left:20%">
                        <h1> Gracias por tu pedido!!!</h1> 
                        <p> Aquí tienes el resultado de tu pedido, si quieres puedes hacer otro pedido haz click en volver a la web.</p>
                        <p> Puedes ver el estado de tus pedidos en tu boton de configuracion de inicio.</p>
                     </div> 
                    <div class="row justify-content-md-center" >
                        <h2 class="text-center paddingbtn orange"> DATOS PEDIDO</h2> 
                          <?php
                                if($mensaje=="menu"){
                             ?>
                        <div class="col-4 sombra paddingbtn"> 
                            <div class="paddingbtn">
                                <i class="fas fa-user-check"></i> <p><b> <?php echo $_SESSION['loginUsuario']['nombre'] ?></b>  </p>                            
                                <i class="far fa-clock"></i><p> <?php echo $_SESSION['menu']['fecha'] ?></p>  
                                <i class="far fa-check-circle"></i> <p> <?php echo $nombreapto ?>  </p>
                                <i class="fas fa-euro-sign"></i><p> <?php echo $precioTotal ?>  </p>
                                <i class="fas fa-thumbs-up"></i> <p><?php echo $_SESSION['menu']['estado'] ?>  </p>
                            </div>
                        </div>  
                        <div class="col-4 sombra paddingbtn">                       
                            <div class="paddingbtn">
                               <?php
                               echo $listaplatos;
                               ?> 
                          </div>
                        </div>  
                         <?php
                                }elseif ($mensaje=="receta") {
                                 echo   $pedidoreceta;
                                }
                               ?> 
                    </div>
                        
                    </div>
            </div>          
        </section> 

        <div class="footer">
            <div class="row justify-content-md-center">
                <div class="col-3 paddingbtn">

                    <h4>ComeBiensano</h4>
                    <i class="fas fa-envelope-open-text"></i><p>comebiensano@comebiensano.com</p>
                    <p>puedes mandar un correo o si lo deseas puedes llamarnos por telefono</p>
                </div>

                <div class="col-3 paddingbtn">

                    <i class="fas fa-home"></i><p>Calle monasterio Nº5 Zaragoza 50004</p>
                    <i class="fas fa-phone-alt"></i><h3>655 581 921</h3>
                </div>

                <div class="col-3"> 
                    <i class="fas fa-laptop-code"></i><p>Macarena Cortés Díaz </p>
                    <i class="fas fa-at"></i><p>masmaca@gmail.com</p>
                    <img  class="tfoto_peque" src="imagenes/derechos.png" alt="imagen derechos reservados"/>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="js/javaScript.js"></script>
    </body>
</html>
