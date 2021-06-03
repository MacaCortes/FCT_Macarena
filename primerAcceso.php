<?php
require 'clases/funciones.php';

?>

<!DOCTYPE html>
<!--
En este primer acceso mostramos un formulario u otro dependiendo de lo que elige el ususario.
o cerrarmos la sesion del ususario si elige cerrar sesion.
-->
<html>
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <meta name="autor" content="macarena cortes" />       
        <link rel="stylesheet" href="css/estilo.css "/>
        <link rel="stylesheet" href="css/fontawesome/css/all.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"/>
        <title>Primer Acceso</title>
    </head>
    <body class="banner_vertical">

        <?php
       
        /* Si el ususario no esta registrado y elige registrarse le aparecera un formulario para 
         * que introduzca todos los datos, estos datos se almacernaran en la base de datos siempre y cuando sean correctos */
        if (isset($_POST['nuevo'])):
            ?>

            <div class="formulario">
                <form  action="index.php" method="POST">
                    <input class="btnapp2" style="margin-left:15%;width: 70%" type="submit" name="volver" value="VOLVER A LA WEB"/>
                </form>
                <img src="imagenes/logo.png" alt="Logo Come_biensano"/> 
                <h1>Rellena el formulario para nuevo Usuario</h1>
                <!--- clases/insertar_registro.php       onsubmit="return validarForm()"-->

                <form action="clases/insertar_registro.php" method="POST" onsubmit="return validarForm()" id="form"  name="form1" >
                    <div class="form" >
                        <h4>Datos personales </h4>
                        <label>DNI
                            <input type="text" maxlength="10" id="dni"  pattern="[0-9]{8}[A-Za-z]{1}"  name="dni" required="required"/>
                        </label> <br/>
                        <label>Nombre
                            <input type="text"  name="nombre" />
                        </label><br/>
                        <label>Apellido
                            <input type="text"  name="apellido" />
                        </label><br/>
                        <label>Direccion:
                            <input type="text" size="70" placeholder="Calle, Avd, .." name="direccion" />
                        </label><br/>
                        <label>Numero:
                            <input type="text" size="5" placeholder="" name="num" />
                        </label>
                        <label>Piso:
                            <input type="text" size="5" placeholder="" name="piso" />
                        </label>
                        <label>Código Postal:
                            <select name="codigo_postal">
                                <option value=""> Seleccione un CP</option>
                                <option value="50001"> 50001</option>
                                <option value="50002">50002</option>
                                <option value="50003"> 50003</option>
                                <option value="50004">50004</option> 
                                <option value="50005"> 50005</option>
                                <option value="50006">50006</option> 
                                <option value="50007"> 50007</option>
                                <option value="50008">50008</option> 
                                <option value="50009"> 50009</option>
                                <option value="50010">50010</option>
                                <option value="50011"> 50011</option>
                                <option value="50012">50012</option>
                                <option value="50013"> 50013</option>
                                <option value="50014">50014</option>
                                <option value="50015"> 50015</option>
                                <option value="50016">50016</option>
                                <option value="50017"> 50017</option>
                                <option value="50018">50018</option>
                                <option value="50019"> 50019</option>

                            </select>

                        </label><br/>
                        <label>Telefono
                            <input type="number"  name="telefono" />
                        </label><br/>
                        <label for="email">Email : 
                            <input type="email" id="email"  size="70" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"name="email">
                        </label><br/>
                        <label>PassWord
                            <input type="password" name="pass" />
                        </label><br/>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <input class="btnapp2" style="margin-left: 15%; width: 70%" type="submit" name="enviar" value="Enviar Datos"/> 
                        </div>
                    </div>
                </form>
            </div>



            <?php
        elseif (isset($_POST['viejo'])):
            ?>
            <div class="formulario">
                <form   action="index.php" method="POST">
                    <input class="btnapp2" style="margin-left: 15%; width: 70%" type="submit" name="volver" value="VOLVER A LA WEB"/>
                </form>
                <img src="imagenes/logo.png" alt="Logo Come_biensano"/> 
                <h1>Iniciar session</h1>
                <form  id="form" action="clases/insertar_registro.php" method="POST" name="form2" >
                    <div class="form">
                        <h4>Datos personales</h4>  
                        <label>Usuario: 
                            <input type="text" maxlength="10" id="dni"  pattern="[0-9]{8}[A-Za-z]{1}" required name="dni1"/>
                        </label> <br/>
                        <label>PassWord: 
                            <input type="password" name="pass1" required="required"/>
                        </label><br/>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <input class="btnapp2" style="margin-left: 15%; width: 70%" type="submit" name="comprobar" value="Enviar Datos"/> 
                      
                        </div>
                    </div>
                     
                </form>
                        
            </div>

    <?php
elseif (!isset($_POST['viejo']) && (!isset($_POST['nuevo']))):
    header('location:index.php');
    exit();
    ?>


    <?php
endif;
if (isset($_POST['cerrar_session'])) {
    $mensaje = " CERRAR SESSION.";
    session_unset($_SESSION['login']);
    session_destroy();
    $ok = false;
    header("location:index.php?ok=$ok");
    exit();
}
?>
 
        <div class="txtCooki">
        
            <i class="fas fa-cookie-bite"></i><p><b>El acceso a este Sitio Web puede implicar la utilización de cookies. Las cookies son pequeñas cantidades
                                    de información que se almacenan en el navegador utilizado por cada Usuario —en los distintos dispositivos
                                    que pueda utilizar para navegar— para que el servidor recuerde cierta información que posteriormente y
                                    únicamente el servidor que la implementó leerá. Las cookies facilitan la navegación, la hacen más
                                    amigable, y no dañan el dispositivo de navegación.
                                    Las cookies son procedimientos automáticos de recogida de información relativa a las preferencias
                                    determinadas por el Usuario durante su visita al Sitio Web con el fin de reconocerlo como Usuario, y
                                    personalizar su experiencia y el uso del Sitio Web, y pueden también, por ejemplo, ayudar a identificar y
                                    resolver errores.
                                    La información recabada a través de las cookies puede incluir la fecha y hora de visitas al Sitio Web, las
                                    páginas visionadas, el tiempo que ha estado en el Sitio Web y los sitios visitados justo antes y después del
                                    mismo. Sin embargo, ninguna cookie permite que esta misma pueda contactarse con el número de
                                    teléfono del Usuario o con cualquier otro medio de contacto personal. Ninguna cookie puede extraer
                                    información del disco duro del Usuario o robar información personal. La única manera de que la
                                    información privada del Usuario forme parte del archivo Cookie es que el usuario dé personalmente esa
                                    información al servidor.</b></p>
            </div>
        <script type="text/javascript" src="js/validar.js"></script>
    </body>

</html>
