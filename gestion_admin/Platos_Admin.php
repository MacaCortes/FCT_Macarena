<?php

spl_autoload_register(function ($clase) {
    require_once "../clases/$clase.php";
});
require '../clases/funciones.php';
$mensaje="";
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
//si estoy en platos añadir platos despues de relllenar el formulario :
if(isset($_POST["newPlato"])){
//construct($tipo_p,$apto_p,$precio_plato,$link_foto_p,$nombre_plato) {
 $nombre_plato= htmlspecialchars($_POST["nombre_p"]);
 $tipo_p=htmlspecialchars($_POST["tipo"]);
 $apto_p=htmlspecialchars($_POST["apto"]);
 $precio_p=htmlspecialchars($_POST["precio"]);
 $precio_plato= floatval($precio_p);
 //datos de la imagen
 $link_foto_p= subirImgServidor();

 $platoinsert=new plato($tipo_p, $apto_p, $precio_plato, $link_foto_p, $nombre_plato);

 $men=$dao_p->insertar($platoinsert);

 $mensaje= "<h2 class='orange'style='padding: 0.5em;'>".$men."</h2>";
 
}
//en platos borrar: 
$borrar="borrar";
if(isset($_POST["borrar_plato"])){
    $id_plato= filter_input(INPUT_POST, 'id_plato');
    //compuebo que ese plato no esta en pedido, si esta no se podra eliminar hasta que no este
    $esta=$dao_pedido->platoEnPedidos($id_plato);

    if($esta=="si"){
    $mensaje= "<h2 class='orange'style='padding: 0.5em;'>El plato no se puede eliminar de momento; ESTA EN PEDIDOS</h2>";   
    }else{
    $men=$dao_p->eliminar($id_plato);
    //$dao_p->eliminarWEB($tipo, $apto); 
    $mensaje= "<h2 class='orange'style='padding: 0.5em;'>".$men."</h2>";
    }
}
//para modificar platos de la web:
$tipo="";
$apto="";
//lista tabla de todas los platos , todas las recetas y todos los pedidos
$tabla = $dao_p->listar();


// aqui compruebo si le ha dado en la opcion de modificar los platos de la web
//le doy un menu para que elija el tipo  y apto del plato
if (isset($_POST['tipoApto'])) {
    $tipo = $_POST['tipo'];
    $apto = $_POST['apto'];   
    //una vez selecionado el tipo y apto, elimino los de esas caracteristicas de la web , para luego insertar los nuevos selecionados
   $dao_p->eliminarWEB($tipo, $apto); 
   $tabla_platos = $dao_p->selecMenu($tipo,$apto);  
}

/*recojo los check de platos selecionados para subir a la web y los inserto en la tabla de platos web,
 *  determinados por tipo y apto
 */
if (isset($_POST['seleccionar'])) {
    //$dao_p->eliminarWEB($tipo, $apto); 
    
    if (!empty($_POST['check_list'])) {
        foreach ($_POST['check_list'] as $selected) {
          $men=$dao_p->insertar_platosWEB($selected);
        }
    } else{
       $men="Tienes que selecionar algún plato";
    }  
 $mensaje="<h2 class='orange'style='padding: 0.5em;'>".$men."</h2>";
  
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
                    <h1 class="orange text-center paddingbtn" > WEB ADMINISTRACIÓN PLATOS</h1>
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
                        <a class="nav-link active" aria-current="page"  style="color:orange;" href="#"><strong>PLATOS</strong></a>
                        <a class="nav-link" href="Recetas_Admin.php">RECETAS</a>
                        <a class="nav-link" href="Trabajadores.php">TRABAJADORES</a>
                        
                    </div>
                </div>
            </div>
        </nav>


        <div class="container paddingbtn2">
            <div class="row">
                <div class="col-4">
                    <form   id="formtra" action="" method="POST" >
                        <input class="btnapp" type="submit" name="btn" value='AÑADIR UN PLATO'/>
                        <input class="btnapp" type="submit" name="btn" value='CAMBIAR PLATOS DE WEB'/>
                        <input class="btnapp" type="submit" name="btn" value='BORRAR'/>                                            
                    </form>
                </div>
                <div class="col-8">

                    <?php
                    if (!isset($_POST['btn'])) {
                        if ($mensaje != "") {
                            echo" <div class='sombra_svg'>";
                            echo "<span>" . isset($mensaje) ? $mensaje : "" . "</span> </div>";
                            echo "</div>";
                           
                        } else {
                            echo " <h2>GESTION DE PLATOS</h2>";
                            echo "<p> Elige entre las opciones de la izquierda</p>";
                            echo "<p> Puedes gestionar los platos a tu antojo, simpre con moderacion y cuidadin</p>";
                        }
                         echo isset($tabla_platos) ? $tabla_platos : "";
                    }

                    if (isset($_POST['btn'])) {
                        if ($_POST['btn'] == "AÑADIR UN PLATO"):
                            ?>
                            <h2>Añadir plato</h2>

                            <form action="#" method="POST"  enctype="multipart/form-data">
                                    <fieldset>
                                    <legend>Datos para nuevo plato </legend>
                                    <div class="row ">
                                        <div class="col-6">
                                            <p> Nombre Plato <input type="text" name="nombre_p" required="required"/></p><br/>
                                        </div>
                                        <div class="row">                                                    
                                            <div class="col-3">
                                                <b>Plato APTO para:</b><br/>
                                                <?php
                                                mostrarAptos();
                                                ?>
                                            </div>
                                            <div class="col-3">
                                                <b>TIPO de plato:</b><br/>
                                                <?php
                                                mostrarTipos();
                                                ?>
                                            </div>
                                            <div class="col-3">
                                                Precio Plato <input type="text" name="precio" required="required"/><br/>                                                                                         
                                                foto del plato <input type="file" name="image" required="required"/><br/>
                                             </div>
                                             <div class="col-4">       
                                            <button type="submit" class="btnapp2" name="newPlato" > Nuevo Plato</button>
                                             </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                    <?php
                    //borrar un plato
                    elseif ($_POST['btn'] == "BORRAR"):
                    ?>
                         <section>
                                <div class="text-center">
                                    <h2> Borra con cuidado!!</h2>
                                    <p>Listado ordenado</p>
                                </div>
                                <div>
                                    <?php
                                    echo isset($mensaje) ? $mensaje : "";
                                    $tabla = $dao_p->listar($borrar);
                                    echo $tabla;
                                    ?>
                                </div>
                            </section>

                                    
                            <?php
                        elseif ($_POST['btn'] == "CAMBIAR PLATOS DE WEB"):
                            echo "<h2 class='orange'> Primero selecciona que tipos de platos son los que  vas a subir a la WEB  </h2>";
                            ?>  
                            <secction class="container">                            
                                <form class="form2"actio=""method="POST">
                                <div class="row  paddingbtn">
                                <div class="col-3">
                            <p><b>Selecciona el tipo de plato</b></p>
                            <?php mostrarTipos() ?>
                                 </div>
                               <div class="col-3">
                               <p><b>Selecciona aptos</b></p>
                            <?php mostrarAptos() ?>
                                 </div>
                                  <div class="col-3">
                                  <button type="submit" class="btn btn-primary btn-lg " name="tipoApto" value="muestra datos">muestra platos</button>
                                  </div>
                                  </div> 
                                 </form> 
                                 <div class="paddingbtn text-center">
                                
                            <?php                         
                            endif;
                         }
                            ?>
                               </div>
                            </secction>               
                </div>
   
        
    </body>
</html>
