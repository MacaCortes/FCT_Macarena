<?php
require '../clases/funciones.php';
spl_autoload_register(function ($clase) {
  require_once "../clases/$clase.php";
});
session_start();
$nombre=$_SESSION['loginCocina']['nombre'];
if(!isset(($_SESSION['loginCocina']))){
    header('location:../index.php');
    exit();
}
//si le doy al boton de cerrar session me lleva a la web inicio
if (isset($_POST['cerrar_session'])) {
    session_unset($_SESSION['loginCocina']);
    session_destroy();
    header('location:../index.php');
    exit();
}
$dao_pedido=new DAOpedido();

$fechaActual = date('d-m-Y');       
$dia_manana = date('d',time()+84600);
$mes_manana = date('m',time()+84600);
$ano_manana = date('Y',time()+84600);
//$fechaMañana= $dia_manana."/".$mes_manana."/".$ano_manana;
$fechaMañana="03/06/2021";
$tablacocinar=$dao_pedido->listarXfechaSelecionada($fechaMañana);
if(isset($_POST['aReparto'])){
        $nombre_plato=$_POST['nombre_plato'];
        $fecha_envio=$_POST['fecha_envio'];       
      //  echo $nombre_plato;
        //cambio el estado de los pedidos
     $id_pedido= $dao_pedido->cambioEstado($fecha_envio, $nombre_plato);       
      // cambioEstadoPedido($id_pedido);
     $dao_pedido->cambioestadoPedio($id_pedido);
        $estado="en reparto";
        $tablacocinar=$dao_pedido->listarXfechaSelecionada($fechaMañana);
}
if (isset($_POST["proximo"]))  {
    $fechaMañana = $_POST['fecha'];
    $tablacocinar = $dao_pedido->listarXfechaSelecionada($fechaMañana);
    //echo $tablacocinar;
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
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"/>
        <title>Web ComeBienSano Cocina </title>
    </head>
    <body>
        <header class=" banner">
         
            <div class="row justify-content-md-center">               
                <div class="col-4">                   
                <img  src="../imagenes/logoAppGestion.png" class="imgestion" alt="logo gestion comeBiensano" style="margin-top: 3em">
                </div>
                <div class="col-4">
                    <h1 class="orange text-center paddingbtn" > GESTION COCINA</h1>
                    <h2 class="text-center"><?php echo $nombre ?></h2>
                </div>
               <div class="col-3"  style="margin-top:9em">
                <form class="paddingbtn "  action="" method="POST">                    
                    <input class=" btnapp " type="submit" name="cerrar_session" value="Cerrar Sesion" />
                </form>
               </div>
                
            </div>
        </header>
        <section class="container">
           
            <div class="col-12">
                <h1>Gestion de Cocina</h1>
                <p>Por defecto se van a listar los platos que tienen que estar preparados hoy, para pasar a reparto,<br/>
                puedes ver los platos para preparar de proximas fechas y asi hacerte un composicion de lugar y puedes ir adelantando trabajo si lo deseas.</p>
                <h4>en proceso significa que es para cocinar una vez cocinado tiene que llevarlo a reparto haciendo click sobre el plato.</h4>
                <h2 style="float: right"> HOY - <?php echo  $fechaActual ;?></h2><br/>
                <hr/>
            </div>  
                <div class="row">
                <div class="col-4">
                    <form   id="formtra" action="" method="POST" >
                        <input class="btnapp" type="submit" name="btn" value='PLATOS A PREPARAR'/>
                        <input class="btnapp" type="submit" name="btn" value='PRÓXIMOS PLATOS'/>                   
                    </form>
                </div>
                 
                <div class="col-8">                   
                    <?php 
                    if(!isset($_POST['btn'])){
                       // echo $tablacocinar  ; 
                        echo isset($tablacocinar)?$tablacocinar:"";
                    }
                    if (isset($_POST["btn"])) {
                        if ($_POST["btn"] == "PLATOS A PREPARAR"):                            
                          echo $tablacocinar  ;
                        ?>
                    
                    <?php
                        elseif ($_POST["btn"] == "PRÓXIMOS PLATOS"):
                     ?>   
                
                    <p>Selecciona la fecha para ver los próximos platos a realizar,<br/>
                        donde el <strong>estado final</strong> del pedido esta <i> 'en proceso'</i></p>
                       <div class="orange paddingbtn">                  
                       <h4> Fecha de entrega: </h4>
                       <?php
                       
                       $lista=$dao_pedido->listarFechaspedidos();
                       echo $lista;                      
                       ?>
                        </div>  
                     
                       <?php     
                        endif;
                        }
                      ?>
                </div>
        </div>
        </section>
        <?php

        ?>
        <script type="text/javascript" src="../js/javaScript.js"></script>
        <script type="text/javascript" src="../js/calendar.js"></script>
    </body>
</html>
