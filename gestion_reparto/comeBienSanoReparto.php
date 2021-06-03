<?php
require '../clases/funciones.php';
spl_autoload_register(function ($clase) {
  require_once "../clases/$clase.php";
});

session_start();
$nombre=$_SESSION['loginReparto']['nombre'];
if(!isset(($_SESSION['loginReparto']))){
    header('location:../index.php');
    exit();
}
//si le doy al boton de cerrar session me lleva a la web inicio
if (isset($_POST['cerrar_session'])) {
    session_unset($_SESSION['loginReparto']);
    session_destroy();
    header('location:../index.php');
    exit();
}
 $mensaje="<p style='padding:3em'><b>aqui todos los pedidos para repartir. Imprime el albaran con los datos del pediodo y una vez entregado click en entregado</b></p></br/><h2 style='color:red;'> FACIL!</h2>";
$dao_pedido=new DAOpedido();
$dao_receta=new DAOreceta();
$dao_u=new DAOusuario();
$dao_p=new DAOplato();
$fechaActual = date('d-m-Y');       
$dia_manana = date('d',time()+84600);
$mes_manana = date('m',time()+84600);
$ano_manana = date('Y',time()+84600);
//$fechaMañana= $dia_manana."/".$mes_manana."/".$ano_manana;
//echo $fechaMañana;
//el primer listado sera la fecha de mañana
//$fechaMañana="28/05/2021";
$tablareparto=$dao_pedido->listarXestadoREparto();
if(isset($_POST['entregado'])){
    $id_pedido=$_POST['id_pedido'];
    $men=$dao_pedido->cambioEstadoFinal($id_pedido);
    $mensaje="<p style='padding:3em'><b>muy bien !!!  </b></p></br/><h2 style='color:red;'>'".$men."'</h2> <p style='padding:3em'><b>vamos a por otro !!</b></p>";
    $tablareparto=$dao_pedido->listarXestadoREparto();
    
}

if (isset($_POST['darId_Kit'])){
    $id_pedido=$_POST['id_pedido'];
    $link_doc_receta=$dao_receta->linKDocReceta($id_pedido);
   
    
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
        <title>Web ComeBienSano Repoarto </title>
    </head>
    <body>
        <header class=" banner">
         
            <div class="row justify-content-md-center">               
                <div class="col-4">                   
                <img  src="../imagenes/logoAppGestion.png" class="imgestion" alt="logo gestion comeBiensano" style="margin-top: 3em">
                </div>
                <div class="col-4">
                    <h1 class="orange text-center paddingbtn" > GESTION REPARTO</h1>
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
                <h1>Gestion de Reparto</h1>
                <p>aqui tines la lista de pedidos que tienen que repartir  <br/>
                </p>               
                <h2 style="float: right"> HOY - <?php echo $fechaActual; ?></h2><br/>
                <hr/>
            </div>  
            
                <div class="sombra_svg">
                    <?php
                    echo isset($mensaje)?$mensaje:""; 
                    
                ?>
            </div>
            <div class="paddingbtn2">
            <?php
            if(isset($link_doc_receta)){
                    echo $link_doc_receta;
                    }
            echo $tablareparto;
            ?>
            </div>
            
        </section>
        
    </body>
</html>
