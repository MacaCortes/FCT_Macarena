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
//decllasro variablaes que voy a usar.
$mensaje = "";
$mensajeOK = false;
$dao_p = new DAOplato(); //interaciones con bbdd  tabla plato
$nombre = $_SESSION['loginUsuario']['nombre'];
$id = $_SESSION['loginUsuario']['id_usuario'];
//reocojo los datos al dar elegi menu de la selecion de la pag index
if (isset($_POST['elegirMenu'])) {
    $fecha = $_POST['fecha'];
    $apto = $_POST['tipo_apto'];
    $_SESSION['menu']['apto'] = $apto;
    $_SESSION['menu']['fecha'] = $fecha;
    $dias = $_POST['m'];
    $_SESSION['menu']['dias'] = $dias;
}
if ($_SESSION['menu']['dias']) {
    $dias = $_SESSION['menu']['dias'];
    if ($dias == 'md' || $dias == 'mdc') {
        $menuD = "Dia";
        $_SESSION['menu']['menuD'] = $menuD;
    } elseif ($dias == 'ms' || $dias == 'msc') {
        $menuD = "Semanal";
        $_SESSION['menu']['menuD'] = $menuD;
    }
    if ($dias == 'md' || $dias == 'ms') {
        $menuC = "sincena";
        $_SESSION['menu']['menuC'] = $menuC;
    } elseif ($dias == 'mdc' || $dias == 'msc') {
        $menuC = "concena";
        $_SESSION['menu']['menuC'] = $menuC;
    }
}
$arrayId_plato = [];
$arraynombresplatos = [];
$nombreapto = darnombreApto($_SESSION['menu']['apto']);
$_SESSION['menu']['nombreapto']=$nombreapto;
// md =>menu dia mdc=>menudia cena ms=>mesemana msc=Meu semana cena

if (isset($_POST['continuar_pedido'])) {

    if (!empty($_POST['check_list'])) {
        foreach ($_POST['check_list'] as $selected) {

            //el selected tiene la id del plato que escogido;
            //recojo todos las id_plato los guardo en un array 
            array_push($arrayId_plato, $selected);
        }
        $num = count($arrayId_plato);
        if ($_SESSION['menu']['menuD'] == 'Semanal' && $_SESSION['menu']['menuC'] == 'sincena') {
            if ($num == 10) {
                $mensajeOK = true;
            } else {
                $mensaje = " <p style='padding:2em'><b>Tu menu es Semanal sin cenas asi que:</b></p></br/><h2 style='color:red;'>TIENES QUE ELEGIR UN MAXIMO DE 10 PLATOS</h2>";
            }
        }
        if ($_SESSION['menu']['menuD'] == 'Semanal' && $_SESSION['menu']['menuC'] == 'concena') {
            if ($num == 15) {
                $mensajeOK = true;
            } else {
                $mensajeOK = false;
                $mensaje = "<p style='padding:2em'><b>Tu menu es Semanal con cenas asi que:</b></p><br/><h2 style='color:red;'> TIENES QUE ELEGIR UN MAXIMO DE 15 PLATOS </h2>";
            }
        }
        if ($_SESSION['menu']['menuD'] == 'Dia' && $_SESSION['menu']['menuC'] == 'sincena') {
            if ($num == 2) {
                $mensajeOK = true;
            } else {
                $mensajeOK = false;
                $mensaje = "<p style='padding:2em'><b>Tu menu es de un Dia sin cenas asi que:</b></p></br><h2 style='color:red;'> TIENES QUE ELEGIR UN MAXIMO DE 2 PLATOS</h2>";
            }
        }
        if ($_SESSION['menu']['menuD'] == 'Dia' && $_SESSION['menu']['menuC'] == 'concena') {
            if ($num == 3) {
                $mensajeOK = true;
            } else {
                $mensajeOK = false;
                $mensaje = "<p style='padding:2em'><b>Tu menu es de un Dia con cenas asi que:</b></p><br/><h2 style='color:red;'> TIENES QUE ELEGIR UN MAXIMO DE 3 PLATOS</h2>";
            }
        }
        //si no ha hecho ningun check
    } else {
        $mensaje = "<h2 style='color:red; padding:2em; margin-left:2em;'>Tienes que seleccionar los platos que quieres para tu pedido </h2>";
        $mensajeOK = false;
    }

    if ($mensajeOK == true) {
        $precioTotal = $dao_p->precioTotal($arrayId_plato);
        $_SESSION['menu']['PrecioT']=$precioTotal;
        $_SESSION['menu']['ID_platos']=$arrayId_plato;
        //como ya tengo el precio total puedo insertar el pedido;
        $fecha = $_SESSION['menu']['fecha'];
        $id_usuario = $_SESSION['loginUsuario']['id_usuario'];
        //come es un pedido de menu la id_receta es null
        $id_receta=null;
        $pedido_receta = new pedido($fecha, $id_usuario, $precioTotal,$id_receta);      
        // recojo en un arary lo nombre de los platos elegidos
        $arraynombresplatos = $dao_p->nombresPlatos($arrayId_plato);
        
    }   
     
    }
    if (isset($_POST['pagar'])) {
    $dao_pedido = new DAOpedido(); //interaciones con bbdd con tabal pedidos
    $fecha = $_SESSION['menu']['fecha'];
    $id_usuario = $_SESSION['loginUsuario']['id_usuario'];
    $precioTotal = $_SESSION['menu']['PrecioT'];
    $arrayId_plato = $_SESSION['menu']['ID_platos'];
    $nombreapto = $_SESSION['menu']['nombreapto'];
    $id_receta="";
    $pedido_receta = new pedido($fecha, $id_usuario, $precioTotal,$id_receta);    
    $estado=$pedido_receta->getestado();
    $_SESSION['menu']['estado']=$estado;
//inserto el pedido y me devuelve la id;
    $id_pedido = $dao_pedido->insertar_pedido($pedido_receta);
    $_SESSION['menu']['id_pedido']=$id_pedido;
    echo id_pedido;
    $dao_pedido->insertar_detalle_pedido($id_pedido, $arrayId_plato);
    $mensaje="menu";
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

                <div class="col-3">
                    <div id="minilogo2 paddingbtn"><img src="imagenes/logopngMini.png" alt="LOgo ComeBienSano mini"/></div>
                </div>    
                <div class="col-6" style="margin-top:-1em;">
                    <h1 style="margin:2em">Elige tu menu de  <?php echo $menuD . "<br/> " . $nombre ?> </h1>                 
                </div>
                <div class="col-3">                   
                    <form class="paddingbtn "  action="" method="POST">
                        <input class=" btnapp " type="submit" name="volver" value="VOLVER A LA WEB"/>              
                        <input class=" btnapp "type="submit" name="cerrar_session" value='Cerrar Sesión' />
                    </form> 
                </div>
            </div>
        </div>           
    
            <section class="container paddingbtn" >
                <div class="row paddingbtn">
                    <div class="col-6 text-center">
                        <h2 class="font orange">Menú <?php echo $nombreapto ?><br/> nosotros cocinamos por ti</h2>
                        <p>Todos nuestras verduras y hortalizas proceden de cultivos ecológicos.<br/> Gran calidad con todos los nutrientes que necesitas para una alimentacion sana.</p> 

                    </div>
                    <div class="col-6 text-center">
                        <h2 class="font orange">Aqui tienes el menu <br/> de esta semana</h2>
                        <p>Marcamos nuestra principal prioridad<br/> en una alimentación sana y de calidad.<br/>Por eso tenemos una cuidadosa seleción de <br/>todos los alimentos.</p>                   
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
            </section>     
                    <?php
                    if ($mensajeOK == false) {
                        ?>
        <section class="container paddingbtn" >
                        <form class="row" action="" method="POST"> 
                            <div class="row">
                            <div class="col-4  text-center ">                        
                                <h2>Primeros</h2>
                                <?php
                                $tipo = "1";
                                $apto = $_SESSION['menu']['apto'];
                                $tabla = $dao_p->selecPlatoWEB($tipo, $apto);
                                echo $tabla;
                                ?>
                            </div> 
                            <div class="col-4 text-center">
                                <h2>Segundos</h2>
                                <?php
                                $tipo = "2";
                                $apto =  $_SESSION['menu']['apto'];
                                $tabla = $dao_p->selecPlatoWEB($tipo, $apto);
                                echo $tabla;
                                ?>
                            </div> 
                            <div class="col-4 text-center">
                                <?php
                                if ($menuC == 'concena') {
                                    echo"<h2>Cena</h2>";
                                    $tipo = "4";
                                    $apto =  $_SESSION['menu']['apto'];
                                    $tabla = $dao_p->selecPlatoWEB($tipo, $apto);
                                    echo $tabla;
                                } elseif ($menuC == 'sincena') {
                                    echo"<h2>tu eleccion es sin cena";
                                }
                                ?>
                            </div> 
                            
                            <div class="col-12" >
                            <input type="submit"  class="btnapp" style="width: 100%" name="continuar_pedido" value="CONTINUAR CON EL PEDIDO"/>
                            </div>
                            </div>
                        </form>
                    </div>
               </section>
        
                    <?php
                } elseif ($mensajeOK == true) {
                    ?>
        <section class="container paddingbtn" >
                    <div class="row justify-content-md-center" >
                        <h2 class="text-center paddingbtn">ya casi lo tenemos</h2>                     
                        <div class="col-2 sombra paddingbtn"> 
                            <div class="paddingbtn">

                                <i class="fas fa-user-check"></i> <p><b> <?php echo $_SESSION['loginUsuario']['nombre'] ?></b>  </p>                            
                                <i class="far fa-clock"></i><p> <?php echo $_SESSION['menu']['fecha'] ?></p>  
                                <i class="far fa-check-circle"></i> <p> <?php echo $nombreapto ?>  </p>
                                <i class="fas fa-euro-sign"></i><p> <?php echo $precioTotal ?>  </p>
                                <i class="fas fa-thumbs-up"></i> <p><?php echo $pedido_receta->getestado() ?>  </p>
                            </div>
                        </div>  
                        <div class="col-3 sombra paddingbtn">                       
                            <div class="paddingbtn">
        <?php
        foreach ($arraynombresplatos as $pos => $valor) {
            echo'<i class="fas fa-utensils"></i> <p>  <b>' . $valor . '</b></p>';
        }
        ?>  
                            </div>
                        </div>  
                        <div class="col-6 sombra paddingbtn">
                            <i class="fas fa-money-check-alt"></i><h2>datos tarjeta</h2>       

                            <div class="sombra paddingbtn">
                                <form action="" method="POST">
                                    <label class="paddingbtn">Nombre completo titular</label>
                                    <input class="inputpago " type="text" name="" required="" />
                                    <label class="paddingbtn">Numero tarjeta</label>
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
        </section>
        <?php
    }   //fin de ese $mensajeOK=true
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

    </body>
</html>
