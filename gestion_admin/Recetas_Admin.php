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
//en REcetas añadir una nueva receta.:
if(isset($_POST["newReceta"])){
//construct($dificultad_r,$link_video,$link_doc_receta,$id_plato,$precio_receta) {
   $dificultad_r=htmlspecialchars($_POST["dificultad"]);
   $link_video= htmlspecialchars($_POST["link"]); 
  // $link_doc_receta=htmlspecialchars($_POST["image"]);
   $id_plato=htmlspecialchars($_POST["id_plato"]);
   $precio=htmlspecialchars($_POST["precio"]);
   $precio_receta= floatval($precio); 
   $link_doc_receta=subirRecetaServidor();

 $Recetainsert=new receta($dificultad_r, $link_video, $link_doc_receta, $id_plato, $precio_receta);
 $men=$dao_receta->insertar($Recetainsert);
  $mensaje="<h2 class='orange'style='padding: 0.5em;'>".$men."</h2>";
}
//en REcetas selecionar recetas para la web:
 $tablaRecetasWEB=$dao_receta->listarRecetasWEb();
if (isset($_POST['seleccionarR'])) {
  
    if (!empty($_POST['check_list'])) {
        foreach ($_POST['check_list'] as $selected) {
         //  echo $selected;
           $borrar=$dao_receta->eliminarRecetaWEB($selected);
          // echo $borrar;
       $men=$dao_receta->insertarRecetasWEB($selected);
        }
    }else{
        $men="Ya se te olvido selecionar recetas .. anda ves a selecionar!!";
    }  
  $mensaje="<h2 class='orange'style='padding: 0.5em;'>".$men."</h2>";
  
}
if(isset($_POST["borrar_receta"])){
    $id_receta= filter_input(INPUT_POST, 'id_plato');
    $men=$dao_receta->eliminar($id_receta);
   // $dao_receta->eliminarRecetaWEB($id_receta);
    $mensaje= "<h2 class='orange'style='padding: 0.5em;'>".$men."</h2>";
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
                    <h1 class="orange text-center paddingbtn" > WEB ADMINISTRACIÓN kist`s</h1>
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
                        <a class="nav-link active" style="color:orange;" href="#"><strong>RECETAS</strong></a>
                        <a class="nav-link" href="Trabajadores.php">TRABAJADORES</a>
                        
                    </div>
                </div>
            </div>
        </nav>


        <div class="container paddingbtn2">
            <div class="row">
                <div class="col-4">
                    <form   id="formtra" action="" method="POST" >
                        <input class="btnapp" type="submit" name="btn" value='AÑADIR UNA RECETA'/>
                        <input class="btnapp" type="submit" name="btn" value='CAMBIAR RECETAS DE WEB'/>
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
                            echo isset($tabla_platos) ? $tabla_platos : "";
                        } else {
                            echo " <h2>GESTION DE RECETAS</h2>";
                            echo "<p> Elige entre las opciones de la izquierda</p>";
                            echo "<p> Puedes gestionar los platos a tu antojo, simpre con moderacion y cuidadin</p>";
                        }
                    }

                    if (isset($_POST['btn'])) {
                
                     if ($_POST["btn"] == "AÑADIR UNA RECETA"):
                    ?>

                    <form action="#" method="POST"  enctype="multipart/form-data">                
                        <div class="row">
                            <h2>Añadir Receta</h2>
                            <h4 class="paddingbt orange">Primero escoge el nombre de plato para la receta</h4>
                            <!--listar los nombre de los platos segun tipo(premero, segundo, postre)-->
                            <div class="col-4">                            
                                <p><b>Primeros</b></p>
                                <?php
                                $tipo = "1";
                                $lista = $dao_p->listarnombreplatos($tipo);
                                echo $lista;
                                ?>
                            </div>
                            <div class="col-4">
                                <p><b>Segundos</b></p>
                                <?php
                                $tipo = "2";
                                $lista = $dao_p->listarnombreplatos($tipo);
                                echo $lista;
                                ?>
                            </div>
                            <div class="col-4">
                                <p><b>Postres</b></p>
                                <?php
                                $tipo = "3";
                                $lista = $dao_p->listarnombreplatos($tipo);
                                echo $lista;
                                ?>
                            </div>
                        </div>                 
                        <div class="paddingbtn" style="background-color: whitesmoke">
                            <h2 class="orange">Datos sobre la receta</h2> 
                            <div class="row">
                                <div class="col-4"><p> Precio Plato </p><input type="text" name="precio" required="required"/><br/></div>
                                <div class="col-4"><p> Dificultad </p><input type="text" name="dificultad" required="required"/><br/></div>
                                <div class="col-4"><p>Documento receta </p><input type="file" name="image" required="required"/><br/></div>
                            </div>
                            <p> link video</p> <input type="text" size="80px"name="link" required="required"/><br/>                       
                        </div>

                        <button type="submit" class= "btnapp2" name="newReceta" >Nueva Receta</button>               
                    </form>
                    <?php
                    //cambiar recetas de la WEB
                    elseif ($_POST["btn"] == "CAMBIAR RECETAS DE WEB"):
                    ?>
                    <h2>Cambia la recetas de la web para KITS DE ALIMENTOS</h2>
                    <h5>Elige las recetas que van a salir en la web, seran las recetas que van a ver los ususarios</h5>
                    <p>Hac check sobre la receta y luego dale a sleccionar</p>
                    <?php
                    echo isset($tablaRecetasWEB)?$tablaRecetasWEB:"no hay recetas";
                    elseif ($_POST["btn"] == "BORRAR"):
                    ?>
                    <section>
                        <div class="text-center">
                            <h2> Borra con cuidado!!</h2>
                            <p>Listado ordenado</p>
                        </div>
                        <div>
                            <?php
                           $borrar="borrar";
                            $tabla = $dao_receta->listarRecetas($borrar);
                            echo $tabla;
                        endif;
                    }
                            ?>
                        </div>
                    </section>
                </div>
            </body>
</html>