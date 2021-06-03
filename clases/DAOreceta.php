<?php


spl_autoload_register(function ($clase) {
  require_once "$clase.php";
});
include 'insertar_registro.php';
class DAOreceta{
 private $con;
//constructor
public function DAOreceta(){
    
}  
public function insertar($objeto_receta){
        try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia= "INSERT INTO recetas(id_receta,dificultad_r,link_video,link_doc_receta,id_plato,precio_receta)"
                . "VALUES (:id_receta, :dificultad_r , :link_video ,:link_doc_receta ,:id_plato,:precio_plato)";
        $id_receta=$objeto_receta->getid_receta();
        $dificultad_r=$objeto_receta->getdificultad();
        $link_video=$objeto_receta->getlink_video();
        $link_doc_receta=$objeto_receta->getlink_doc_receta();
        $id_plato=$objeto_receta-> getid_plato();
        $precio_receta=$objeto_receta->getprecio_receta();

        $resultado= $conexion->prepare($sqlSentencia);
      
        $resultado->execute(array(":id_receta"=> $id_receta,":dificultad_r"=> $dificultad_r,":link_video"=> $link_video,":link_doc_receta"=> $link_doc_receta,"id_plato"=> $id_plato,":precio_plato"=> $precio_receta));
      
     
        $mensaje= "El registro se ha efectuado satisfactoriamente, Gracias";           
        } catch (Exception $ex) {
           
           //el error 23000 hace referencia a la clave primaria (DNI) no puede haber dos iguales
            if($ex->getCode()==23000){
            $mensaje=" Error usuario .Este usuario  ya existe !!";
             
            }else{
            die('El Error excepcoion estoy en la clase DAOreceta     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
              }
} finally {
 $resultado->closeCursor();
}
        return $mensaje; 
    }//fin de funcion guardar
 
     //listado de todos las recetas
    //podemos añadir si select no es null los botones de borrar 
    public function listarRecetas($select=null){
       
      try {
            $conexion = new Conexion();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');
            $sentancia = "SELECT pl.nombre_plato, r.id_receta,r.dificultad_r,r.precio_receta FROM recetas r
               JOIN platos pl ON (r.id_plato=pl.id_plato) ORDER BY pl.nombre_plato;";
            $resultado = $conexion->prepare($sentancia);
            $resultado->execute();
            $tabla = "<table   class='table  table-hover'>"
                    . "<th class='bg-warning' scope='col'>Id</th>"
                    . "<th class='bg-warning' scope='col'>nombre Plato</th>";
            $tabla .= "<th class='bg-warning' scope='col'>precio </th>";
            $tabla .= "<th class='bg-warning' scope='col'>dificultad </th>";
                
            if ($select != null) {
                $tabla .= "<th class='bg-warning' scope='col'>Opcion</th>";
            }
            while ($muestra = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $tabla .= '<tr>';
                $tabla .= '<td >' . $muestra['id_receta'] . '</td>';
                $tabla .= '<td >' . $muestra['nombre_plato'] . '</td>';
                $tabla .= '<td >' . $muestra['precio_receta'] . '</td>';
                $tabla .= '<td >' . $muestra['dificultad_r'] . '</td>';
                
             if($select!=null){
                 $tabla.= "<td><form action='' method='POST'>"
                    . "<input type='hidden' name='id_plato' value='".$muestra['id_receta']."'/>"                         
                     ."<button type='submit' class='btn-danger btn-xs'  name='borrar_receta' value='$select'>BORRAR</button></form>"
                 . "</td>";                 
                 }
        
        $tabla.= ' </tr>';   
             }
           $tabla.= "</table>";
           $resultado->closeCursor(); 
         } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoReceta     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
 
}
        return $tabla; 
    }//fin de listar
     
    //elimina el plato cuya id pasamos por parametro
    public function eliminar($id_receta){        
         try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia="DELETE FROM recetas WHERE id_receta='".$id_receta."';"; 
        $resultado = $conexion->prepare($sentancia);
            $resultado->execute();

            if ($resultado) {
                $mensaje = "¡El registro ha sido eliminado!";
            }
            $resultado->closeCursor();
        } catch (Exception $ex) {
            $mensaje = $ex;
            die('El Error excepcoion estoy en la clase DaoPlatos     :' . $ex->getCode() . " en la linea  " . $ex->getFile() . " nºlinea " . $ex->getLine());
            $mensaje = "la linea de error : " . $ex->getLine() . " en la linea  " . $ex->getFile() . " nºlinea " . $ex->getLine();
        } finally {
    $base=null;
}
        return $mensaje;         
    }//fin de eliminar

    
    //listar todas las recetas con check para que pueda cambiarlas en la web el ADMINISTRADOR
    public function listarRecetasWEb(){
        try {
            $conexion = new Conexion();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');   
            $sentancia = "SELECT pl.nombre_plato, r.id_receta FROM recetas r
               JOIN platos pl ON (r.id_plato=pl.id_plato) ;";
            $resultado = $conexion->prepare($sentancia);           
            $resultado->execute();
             $numeroregistro=$resultado->rowCount();
             if($numeroregistro==0){
                 $mensaje="No hay platos de este tipo disponibles";
                 return $mensaje;
             }
             
              $tabla = "<table class='table table-hover sombra paddingbtn'>"; 
              $tabla.="<form action='' method='POST'>";
              while($row = $resultado->fetch(PDO::FETCH_ASSOC)){ 
                $tabla .= '<tr>';            
                $tabla .= '<td ><input type="checkbox" name="check_list[]" value="'.$row['id_receta'].'" checked/></td>';                           
                $tabla .= '<td >' . $row['nombre_plato'] . '</td>'; 
                $tabla .= ' </tr>';
            } 
            $tabla .= "<button type='submit' class='btn-success btn-xs paddingbtn' name='seleccionarR'> MANDAR SELECCION</button></form>";
            $tabla .= '</table>';
            $resultado->closeCursor();
            
             return $tabla;
      } catch (Exception $ex) {           
            die('El Error excepcoion estoy en la clase DAOreceta    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
              
} finally {

}
            
     }
    
       /* funcion va a eliminar las recetas  EL ADMINISTRADOR de la tabla platosWEB que exitan con un tipo  determinados
     * para luego poder meter otros , que seran los que se vean en la web */
     
    public function eliminarRecetaWEB($id){        
         try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia1="Select * FROM recetasweb WHERE id_receta='".$id."';"; 
        $resultado1 = $conexion->prepare($sentancia1);
        $resultado1->execute();
        $numeroregistro=$resultado1->rowCount();
        if ($numeroregistro !=0){
        $sentancia="DELETE FROM recetasweb WHERE id_receta='".$id."';"; 
        $resultado = $conexion->prepare($sentancia);
            $resultado->execute();
            $mensaje = "¡El registro ha sido eliminado!";
        }else{
        }
        
        } catch (Exception $ex) {
            $mensaje = $ex;
            die('El Error excepcoion estoy en la clase DaoRecetas     :' . $ex->getCode() . " en la linea  " . $ex->getFile() . " nºlinea " . $ex->getLine());
            echo "la linea de error : " . $ex->getLine() . " en la linea  " . $ex->getFile() . " nºlinea " . $ex->getLine();
        } finally {
    
}
               
    }//fin de eliminar
      
    //funcion de adminstrado para insertar las recetas en la web
     public function insertarRecetasWEB($id){
      try {
            $conexion = new Conexion();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');           
            $sentancia = "SELECT * FROM recetas WHERE id_receta='".$id ."' ;";
            $resultado = $conexion->prepare($sentancia);
            $resultado->execute();
            $sqlSentencia= "INSERT INTO recetasweb (id_receta,dificultad_r,link_video,link_doc_receta,id_plato,precio_receta)  VALUES  "
                    . "(:id_receta,:dificultad_r,:link_video,:link_doc_receta ,:id_plato ,:precio_receta);";
            $resultado2= $conexion->prepare($sqlSentencia); 
            while($row = $resultado->fetch(PDO::FETCH_ASSOC)){
            $resultado2->execute(array(":id_receta"=>$row['id_receta'],"dificultad_r"=>$row['dificultad_r'] ,":link_video"=> $row['link_video'],":link_doc_receta"=>$row['link_doc_receta'],":id_plato"=>$row['id_plato'],":precio_receta"=>$row['precio_receta']));                                                                   
            }
            $resultado2->closeCursor();
            $resultado->closeCursor();
            $mensaje="Las recetas ya las tienes en la web";           
            return $mensaje;
      } catch (Exception $ex) {

            die('El Error excepcoion estoy en la clase DAOrEceta     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
              
} finally {
 $resultado->closeCursor();
}
        
     }  //fin de insertar en web
     
     
     public function selectRecetaWeb($tipo){
         try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia="SELECT rw.id_receta, pl.nombre_plato, rw.precio_receta, pl.foto_p, rw.dificultad_r "
                . " FROM recetasweb rw JOIN platos pl ON (rw.id_plato= pl.id_plato) WHERE pl.tipo_p='".$tipo ."';";
        
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
        
           $tabla = "<table class='table table-hover sombra paddingbtn'>";
            $tabla .= "<th class='bg-warning' scope='col'>select</th>";  
            $tabla .= "<th class='bg-warning' scope='col'>nombre</th>";
            $tabla .= "<th class='bg-warning' scope='col'>Dificultad</th>";
            $tabla .= "<th class='bg-warning' scope='col'>Precio</th>";
            $tabla .= "<th class='bg-warning' scope='col'></th>";
            while ($muestra = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $tabla .= '<tr>';            
                $tabla .= '<td ><input type="radio" name="receta" value="'.$muestra['id_receta'].'"/></td>';                           
                $tabla .= '<td >'. $muestra['nombre_plato'] . '</td>';
                $tabla .= '<td >' . $muestra['dificultad_r'] . ' </td>';
                $tabla .= '<td >' . $muestra['precio_receta'] . ' €</td>';
                $tabla .= '<td > <img class="tfoto_peque" style="aling:center;" src="' . $muestra['foto_p'] . '"/></td>';
                $tabla .= ' </tr>'; 
                $tabla.="";
                $tabla.="<input type='hidden' name='precio' value='".$muestra['precio_receta'] ."'/>";
         
         
            }            
             $tabla .="</table>";
         } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoRecetas     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
    $base=null;
}
        return $tabla; 
     }
     //funcion para listar al ususario los links que dispone segun su pedido
     function listarLinkreceta($id_usuario){
         try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia=" SELECT pl.nombre_plato ,r.link_video ,r.link_doc_receta FROM recetas r JOIN platos pl ON (r.id_plato=pl.id_plato)
            JOIN pedidos p ON (p.id_receta =r.id_receta) 
            JOIN usuarios u on (p.id_usuario= u.id_usuario)
            where u.id_usuario='".$id_usuario."';";
        
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
        $tabla = "<table   class='table  table-hover'><tr>";
        while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){
         $tabla .='<tr><td>' .$muestra['nombre_plato'] .'</td>';
         $tabla .= '<td> <a class="" href="'.$muestra['link_video'] .' "> Enlace al video </a></td>' ;
         $tabla .= '<td><a class="paddingbtn" download href="'.$muestra['link_doc_receta'].'"> DESCARGA TU RECETA</a></td></tr>' ;       
        }
                 
       $tabla .= '</tr></table>';
    
              $resultado->closeCursor();     
                
          } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoRecetas     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
    $base=null;
}
        return $tabla; 
     }
     
     //funcion que le proporciona al reparto el doc de receta para poder decargarlo y preparar pedido
  public function linKDocReceta($id_pedido){
         try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia=" SELECT r.link_doc_receta FROM recetas r "
                . "JOIN pedidos p ON (r.id_receta=p.id_receta) where p.id_pedido='".$id_pedido."';";        
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
         $tabla = "<table   class='table  table-hover'>";
         $tabla .= "<tr> descarga listado de ingredientes a preparar del pedido ".$id_pedido."</tr>";
        while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){
           $tabla .= '<tr><td><a class="paddingbtn" download href="'.$muestra['link_doc_receta'].'"> DESCARGA TU RECETA</a></td></tr>' ;  
        }
         $tabla .= '</table>';
        
          $resultado->closeCursor();       
      
          } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoRecetas     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
    $base=null;
}
        return $tabla; 
     }
     
}//fin de la clase DAOreceta