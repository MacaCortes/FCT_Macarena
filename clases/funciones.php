<?php
include 'conexion.php';

/*
 * Mostrar los nombre de tipos de platos que tengo para insertarlo en el menu para insertar (y otros menus)platos 
 * y poder elegir el tipo de plato por le nombre y no por id. guardo la id en el valor del input para luego
 * poder usarla
 *  */
function mostrarTipos(){
      try {
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia="SELECT id_tipo,tipo FROM tipos_platos ;";
        $resultado= $conexion->query($sqlSentencia);
        $resultado->setFetchMode(PDO::FETCH_ASSOC);
         //$arraytipos=$resultado->fetch(PDO::FETCH_ASSOC);  
         while ($row =  $resultado->fetch()) {
            
             echo ' <input type="radio"  name="tipo" value="'.$row['id_tipo'].'" checked="chequed"/>'. $row['tipo'].'<br> ';
        
         }

} catch (PDOException $err) {
    // Mostramos un mensaje genérico de error.
	echo "Error: ejecutando consulta SQL.";
}
   $resultado->closeCursor();
}

function darnombreTipo($tipo){
   try {
        $conexion = new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia = "SELECT tipo FROM tipos_platos WHERE id_tipo = '" . $tipo . "';";
        $resultado = $conexion->query($sqlSentencia);
        $resultado->setFetchMode(PDO::FETCH_ASSOC);
        while ($row =  $resultado->fetch()) {
        $tipo= $row['tipo'];
        }
    } catch (PDOException $err) {
        // Mostramos un mensaje genérico de error.
        echo "Error: ejecutando consulta SQL.";
    }
    $resultado->closeCursor();
    return $tipo;
} 

//funcion al igual que los tipos, tengo predefinidos aptos que define para quien son apto cada plato.
function mostrarAptos(){
     try {
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia="SELECT id_apto,apto FROM platos_aptos ;";
        $resultado= $conexion->query($sqlSentencia);
        $resultado->setFetchMode(PDO::FETCH_ASSOC);        
         while ($row =  $resultado->fetch()) {        
             echo ' <input type="radio" name="apto"  value="'.$row['id_apto'].'" checked="chequed"/>'. $row['apto'].'<br> ';      
         }
} catch (PDOException $err) {
    // Mostramos un mensaje genérico de error.
	echo "Error: ejecutando consulta SQL.";
}
   $resultado->closeCursor();
}

function darnombreApto($apto){
   try {
        $conexion = new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia = "SELECT apto FROM platos_aptos WHERE id_apto = '" . $apto . "';";
        $resultado = $conexion->query($sqlSentencia);
        $resultado->setFetchMode(PDO::FETCH_ASSOC);
        while ($row =  $resultado->fetch()) {
        $apto= $row['apto'];
        }
    } catch (PDOException $err) {
        // Mostramos un mensaje genérico de error.
        echo "Error: ejecutando consulta SQL.";
    }
    
    $resultado->closeCursor();
    return $apto;
}

//subira la imagen seleccionada en el formulario de inserter plato o receta a la carpeta del servidor de upload
function subirImgServidor(){
 $nombre_img=$_FILES['image']['name'];
 $tipo_img=$_FILES['image']['type'];
 $size_img=$_FILES['image']['size'];
 ///espacifico el tamaño maximo de imagen
 if($size_img<=3000000){
     //la imagen solo puede ser jpg o png
     if($tipo_img='image/jpg' || $tipo_img='image/png' ){
 //destino de carpata donde guardo las imagenes en el servidor root=C:/xampp/htdocs
// CUIDADIN !!!!! SI NO SUBEN LA IMG MIRA QUE SE ACOMEBIENSANO --> _1 , _2 ...
 $carpeta_destino=$_SERVER['DOCUMENT_ROOT'].'/ComeBienSano_Maca/upload/img_platos/';

 //mover de la carpeta temporal a al upload (directorio escogido
  move_uploaded_file($_FILES['image']['tmp_name'],$carpeta_destino.$nombre_img);
//echo $carpeta_destino.$nombre_img;

 }else{
     $mensaje="ERROR la img no es valida, por tipo formato solo admite jpg o png";
     return $mensaje;
 } 
 }else{
     $mensaje="ERROr tamaño de la imagen no permitido";
     return $mensaje;
}
$link="upload/img_platos/".$nombre_img;
return $link;
}

function subirRecetaServidor(){
 $nombre_img=$_FILES['image']['name'];
 $tipo_img=$_FILES['image']['type'];
 $size_img=$_FILES['image']['size'];
 ///espacifico el tamaño maximo de imagen
 if($size_img<=3000000){
     //la imagen solo puede ser jpg o png
     if($tipo_img='image/jpg' || $tipo_img='image/png' || $tipo_img='image/pdf' || $tipo_img='image/doc'  ){
 //destino de carpata donde guardo las imagenes en el servidor root=C:/xampp/htdocs
// CUIDADIN !!!!! SI NO SUBEN LA IMG MIRA QUE SE ACOMEBIENSANO --> _1 , _2 ...
 $carpeta_destino=$_SERVER['DOCUMENT_ROOT'].'/ComeBienSano_Maca/upload/img_recetas/';

 //mover de la carpeta temporal a al upload (directorio escogido
  move_uploaded_file($_FILES['image']['tmp_name'],$carpeta_destino.$nombre_img);
//echo $carpeta_destino.$nombre_img;

 }else{
     $mensaje="ERROR la img no es valida, por tipo formato solo admite jpg o png";
     return $mensaje;
 } 
 }else{
     $mensaje="ERROr tamaño de la imagen no permitido";
     return $mensaje;
}
$link="upload/img_recetas/".$nombre_img;
return $link;
}
// funcion para mostrar las imagenes que tengo en la carperta de upload
//el uso por estetica. para dibujar en la web
function mostrar_imagenes($directory=null){
    //$directory="../upload/img_platos/";
    $directory="imagenes/platos/";
    $dirint = dir($directory);
 
    while (($archivo = $dirint->read()) !== false)
    {
        if (strpos($archivo,'jpg') || strpos($archivo,'jpeg') || strpos($archivo, 'tiff') || strpos($archivo, 'png') ){
            $image = $directory. $archivo;       
            echo"<img class='tfoto' title='foto plato' alt='fotos de platos de galeria de servidor' src='$image '>";
        }        
    }
    $dirint->close();

}

function  fotos_platos($directory=null){    
$folder_path = 'imagenes/platos/'; 
$num_files = glob($folder_path . "*.{JPG,jpeg,gif,png,bmp}", GLOB_BRACE);
$folder = opendir($folder_path); 
if($num_files > 0){
 while(false !== ($file = readdir($folder)))  {
  $file_path = $folder_path.$file;
  $extension = strtolower(pathinfo($file ,PATHINFO_EXTENSION));
  if($extension=='jpg' || $extension =='png' || $extension == 'gif' || $extension == 'bmp') {

   echo "<img class='tfoto' src='  $file_path ' alt='Imagen de ejemplo' title='Imagen de ejemplo'/>";
    
  }}}

closedir($folder);  
return $file_path;


}

// funcio que me crea el formulario de insettar un trabajador
//es el mismo que el de usuario comun. y la accion se ejecuta igual
function crearform(){

    echo'         
        <form class="form" style="width: auto;" action="../clases/insertar_registro.php" method="POST" onsubmit="return validarForm() " id="form"  name="form1" >
           <label>DNI:
                <input type="text" maxlength="10" id="dni"  pattern="[0-9]{8}[A-Za-z]{1}"  name="dni" required/>
            </label> <br/>
            <label>Nombre:
                <input type="text"  name="nombre" required/>
            </label><br/>
            <label>Apellido:
                <input type="text"  name="apellido" required/>
            </label><br/>
            <label>Direccion:
                <input type="text" size="70" placeholder="Calle, Avd, .." name="direccion" required/>
            </label><br/>
             <label>Numero:
                <input type="text" size="5" placeholder="" name="num" required />
            </label>
             <label>Piso:
                <input type="text" size="5" placeholder="" name="piso" required/>
            </label>
             <label>Código Postal:
                  <select name="codigo_postal" required>
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
                <input type="number"  name="telefono" required/>
            </label><br/>
                <label for="email">Email : 
                <input type="email" id="email"  size="70" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"name="email" required>
                </label><br/>
                <label>PassWord
                    <input type="password" name="pass" required/>
                </label><br/>';
      
               echo'tipo de trabajador:<br/>';

               echo '<input type="radio" name="tipo_u" value="2"/> Administrador <br/>
                   <input type="radio" name="tipo_u" value="3" checked/> Cocina <br/>
                   <input type="radio" name="tipo_u" value="4"/> Reparto <br/>';
                   
              
           echo' <button class="btnapp2"  type="submit" name="enviar" >ENVIAR DATOS</button><br/>
           
            
        </form>';
    
}

