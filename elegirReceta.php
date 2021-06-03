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

if (isset($_POST['volver'])) {
    exit(header("location:index.php?ok=$ok"));
}

if (isset($_POST['cerrar_session'])) {
    $mensaje = " CERRAR SESSION.";
    session_unset($_SESSION['login']);
    session_destroy();
    $ok = false;
    header("location:index.php?ok=$ok");
    exit();
}
//decllasro variables que voy a usar.
$dao_receta=new DAOreceta();
$dao_pedido=new DAOpedido();
$nombre = $_SESSION['loginUsuario']['nombre'];
$id = $_SESSION['loginUsuario']['id_usuario'];
$mensaje = "";
if(isset($_POST['receta'])){
  //  $listaRecetas=$dao_receta->selectRecetaWeb($tipo);
   $tipo=$_POST['receta'];
   $nombretipo= darnombreTipo($tipo);
}
$mensajeOK = false;
if(isset($_POST['continuar_pedido'])){    
    $id_receta=$_POST['receta'];
    
    if($id_receta==""){
     $mensaje="<h2 class='orange paddingbtn'> uppp, se te olvido elegir receta !! ÁNIMO  ELEGIR UNA RECETA</h2>"; 
      $mensajeOK = false;
    }else{
    $fecha_envio=$_POST['fecha'];
    $_SESSION['pedido_receta']['fecha_envio']=$fecha_envio;
    $precio_total=$_POST['precio'];
    $_SESSION['pedido_receta']['precio_total']=$precio_total;   
    $id_usuario = $_SESSION['loginUsuario']['id_usuario']; 
    $_SESSION['pedido_receta']['id_receta']=$id_receta;
    
    $pedido_receta = new pedido($fecha_envio, $id_usuario, $precio_total,$id_receta);
    $mensajeOK = true;
    }
}

    if (isset($_POST['pagar'])) {   
    //inserto el pedido y me devuelve la id;
    $fecha_envio=$_SESSION['pedido_receta']['fecha_envio'];
    $precio_total=$_SESSION['pedido_receta']['precio_total'];   
    $id_usuario = $_SESSION['loginUsuario']['id_usuario']; 
    $id_receta=$_SESSION['pedido_receta']['id_receta'];
    $estado="en reparto";
    $pedido_receta = new pedido($fecha_envio, $id_usuario, $precio_total,$id_receta,$estado);
    $id_pedido = $dao_pedido->insertar_pedido($pedido_receta);
    $_SESSION['pedido_receta']['id_pedido']=$id_pedido;
    //echo $id_pedido;
    $mensaje="receta";
    header("location:comebiensanoPedido.php?mensaje=$mensaje");
    exit();
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
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <link rel="stylesheet" href="css/fontawesome/css/all.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script> 

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"/>
        <title>Elegir menú</title>
    </head>
    <body>

        <div class="banner"style=" height: 180px">   
            <div class="row">

                <div class="col-3 ">
                    <div id="minilogo2 paddingbtn"><img src="imagenes/logopngMini.png" alt="LOgo ComeBienSano mini"/></div>
                </div>    
                <div class="col-6" style="margin-top:-1em;">
                    <h1 style="margin:2em">Elige tu Receta   <?php echo  $nombre ?> </h1>                 
                </div>
                <div class="col-3">                   
                    <form class="paddingbtn "  action="" method="POST">
                        <input class=" btnapp " type="submit" name="volver" value="VOLVER A LA WEB"/>              
                        <input class=" btnapp "type="submit" name="cerrar_session" value='Cerrar Sesión' />
                    </form> 
                </div>
            </div>
        </div>           
        <?php
      //  if ($_SESSION['menu']['apto'] == "1"):
            ?>  
            <section class="container paddingbtn" >
                <div class="row paddingbtn justify-content-md-center">
                    <div class="col-6">
                        <h2 class="font orange text-center paddingbtn">Variedad de recetas<br/>' BienSanas' </h2>
                        <div class="paddingbtn" >
                        <p>Todos nuestras verduras y hortalizas proceden de cultivos ecológicos.<br/>
                            Gran calidad con todos los nutrientes que necesitas para una alimentacion sana.<br/>
                        Te mandaremos a casa todo lo necesario para elavorar la ceceta que elijas.</p> 
                        </div>
                    </div>
                    <div class="col-6 text-center ">
                        <h2 class="font orange text-center paddingbtn ">Elige la receta que más te guste</h2>
                          <div class="paddingbtn">
                        <p class="">Marcamos nuestra principal prioridad en una alimentación sana y de calidad.<br/>
                            Por eso tenemos una cuidadosa seleción de todos los alimentos.<br/>
                        Todos los productos que vas a recibir han pasado un control de calidad.</p>                   
                    </div>
                    </div>
                    <div class="col-12 text-center paddingbtn">
                        <?php
                        if ($mensaje != "") {
                            ?>
                            <div class="sombra_svg">
                                <?php
                                echo "<span>" . isset($mensaje) ? $mensaje : "" . "</span>";
                            }
                            ?>
                        </div>
                    </div>  
                    <?php
                    if ($mensajeOK == false) {
                        ?>
                        <form  action="" method="POST">
                             
                            <div style="text-align: center"> 
                               <p>Tienes que selecionar la fecha que deseas para envio </p>
                              <h4> Fecha de entrega: </h4><input type='text' name="fecha" id='txtDate' required="required"/>                               
                            </div>
                            <div class="d-inline p-2 orange paddingbtn"> 
                                <h2 class="text-center">Recetas disponibles</h2> 
                            </div>
                                <div class=" row justify-content-center">

                                    <div class="celda col_12 col-sm-6 col-lg-4">
                                        <h3>Primeros</h3> 
                                        <?php
                                        $tipo = 1;
                                        $listaRecetas = $dao_receta->selectRecetaWeb($tipo);
                                        echo $listaRecetas;
                                        ?>
                                    </div>
                                    <div class="celda col_12 col-sm-6 col-lg-4">
                                        <h3>Segundos</h3> 
                                        <?php
                                        $tipo = 2;
                                        $listaRecetas = $dao_receta->selectRecetaWeb($tipo);
                                        echo $listaRecetas;
                                        ?>
                                    </div>
                                    <div class="celda col_12 col-sm-6 col-lg-4">
                                        <h3>Postres</h3> 
                                        <?php
                                        $tipo = 3;
                                        $listaRecetas = $dao_receta->selectRecetaWeb($tipo);
                                        echo $listaRecetas;
                                        ?>
                                    </div>                                                   
                                    <input type="submit"  class="btnapp" name="continuar_pedido" value="CONTINUAR CON EL PEDIDO"/>
                            </div>
                        </form>
                    </div>
               </section>
                      <?php
                } elseif ($mensajeOK == true) {
                    ?>
        <div class="row justify-content-md-center" >
                <h2 class="text-center paddingbtn">ya casi lo tenemos</h2>                     
                <div class="col-4 sombra paddingbtn"> 
                    <div class="paddingbtn">

                        <i class="fas fa-user-check"></i> <p><b> <?php echo $_SESSION['loginUsuario']['nombre'] ?></b>  </p>                            
                        <i class="far fa-clock"></i><p> <?php echo $fecha_envio  ?></p>  
                      
                        <i class="fas fa-euro-sign"></i><p> <?php echo $precio_total ?>  </p>
                        <i class="fas fa-thumbs-up"></i> <p><?php echo $pedido_receta->getestado() ?>  </p>
                        
                    </div>
                </div>  
              
                <div class="col-6 sombra paddingbtn">
                    <i class="fas fa-money-check-alt"></i><h2>datos tarjeta</h2>       

                    <div class="sombra paddingbtn">
                        <form action="" method="POST">
                            <label class="paddingbtn">Nombre completo</label>
                            <input class="inputpago " type="text" name="" required="" />
                            <label class="paddingbtn">Numbero tarjeta</label>
                            <input type="text" class="inputpago "required="" />
                            <div class="row paddingbtn">
                                <div class=" col-4 paddingbtn">
                                    <label>Fecha de Expiracion</label>                                 
                                    <input class="" type="text" size="5px" name="cc_month" placeholder="MM"  minlength="2" maxlength="2" required="" />
                                    <input class="" type="text" size="5px" name="cc_year" placeholder="YY"  minlength="2" maxlength="2" required="" />
                                </div>
                                <div class=" col-3 paddingbtn">
                                    <label> CVC</label><i class="fas fa-question-circle"></i>
                                    <input type="text" class="inputpago" name="cc_cvc" size="5px"placeholder="123"  minlength="3" maxlength="4" required="" />
                                </div>
                            </div>

                            <label class="paddingbtn">
                                <input type="checkbox" name="save_cc" checked="checked">
                                guardar para futuras compras
                            </label>

                            <button type="submit" name="pagar" class="btnapp">pagar ahora</button>

                        </form>
                    </div>
                </div>
            </div>                

        
        
                   <?php
                }
                   ?>
    
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
        <script type="text/javascript" src="js/calendar.js"></script>
    </body>
</html>
