<?php

/*
El pateron DAo por cada clase que hay debe haber una clase DAO que es la que se encarga 
 * de las transaciones con la base de datos para una tabla especifica
 * debe ser una clase que haga CRUD para la tabla especifica, por eso este archivo lleva el mismo nombre
 * que DAOplatos
 *  */
//incluimos la clase a la que hace referencia y la conexion

spl_autoload_register(function ($clase) {
  require_once "$clase.php";
});
class DAOplato{
 private $con;
//constructor
public function DAOplato(){
    
}  

//$objeto_plato es objeto creado:
    public function insertar($objeto_plato){
         try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia= "INSERT INTO platos ( tipo_p, apto_p, precio_plato, foto_p, nombre_plato) VALUES (:tipo_p, :apto_p, :precio_plato , :link_foto_p , :nombre_plato);";
            $nombre_plato = $objeto_plato->getnombre_plato();
            $tipo_p = $objeto_plato->gettipo_p();
            $apto_p = $objeto_plato->getapto_p();
            $link_foto_p = $objeto_plato->getlink_foto_p();
            $precio_plato = $objeto_plato->getprecio_plato();           
            $resultado= $conexion->prepare($sqlSentencia);
            $resultado->execute(array(":tipo_p"=>$tipo_p ,":apto_p"=> $apto_p,":precio_plato"=>$precio_plato,":link_foto_p"=>$link_foto_p,":nombre_plato"=> $nombre_plato));                
          
        $mensaje= "El registro se ha efectuado satisfactoriamente, Gracias";
         $resultado->closeCursor();
        } catch (Exception $ex) {
              echo $ex;
           //el error 23000 hace referencia a la clave primaria  no puede haber dos iguales
            if($ex->getCode()==23000){
                
            $mensaje=" este plato ya existe !!";
             
            }else{
            die('El Error excepcoion estoy en la clase DaoPlatos     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
           // echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    }
        } finally {
            $base = null;
        }
        return $mensaje;
    }

    //elimina el plato cuya id pasamos por parametro
    public function eliminar($id_plato){        
         try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia="DELETE FROM platos WHERE id_plato='".$id_plato."';"; 
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
    
     /* funcion va a eliminar los platos de la tabla platosWEB que exitan con un tipo y apto determinados
     * para luego poder meter otros , que seran los que se vean en la web
     */
    public function eliminarWEB($tipo_p,$apto_p){        
         try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia="DELETE FROM platosWEB WHERE tipo_p='".$tipo_p."' AND apto_p='".$apto_p ."';"; 
        $resultado = $conexion->prepare($sentancia);
            $resultado->execute();
            if ($resultado) {
             $mensaje= "¡El registro ha sido eliminado!";
            }
        } catch (Exception $ex) {
            $mensaje = $ex;
            die('El Error excepcoion estoy en la clase DaoPlatos     :' . $ex->getCode() . " en la linea  " . $ex->getFile() . " nºlinea " . $ex->getLine());
            echo "la linea de error : " . $ex->getLine() . " en la linea  " . $ex->getFile() . " nºlinea " . $ex->getLine();
        } finally {
    
}
        return $mensaje;         
    }//fin de eliminar
    
   
    public function modificar($objeto_plato){
     $c= conectar();
     $id_plato=$objeto_plato->getid_plato();
     $nombre_plato=$objeto_plato->getnombre_plato();
     $tipo_p=$objeto_plato->gettipo_p();
     $apto_p=$objeto_plato->getapto_p();
     $link_foto_p=$objeto_plato->getlink_foto_p();
     $precio_plato=$objeto_plato->getprecio_plato();
     //ahora creo la sql que ejecuta para insertar datos   
      $sql= "UPDATE platos set nombre_plato='$nombre_plato', tipo_p='$tipo_p',apto_p= '$apto_p' ,precio_plato='$precio_plato' , foto_p='$link_foto_p' ; ";
      if(!$c->query($sql)){
          print "error al modificar";
          
      }else{
          //mostramos un mensaje de correcto en forma de alert
          print'<script languaje="JavaScript"> alert("Modificado correctamente");</script>';
       
      }
      // cerramos la conexion
      mysqli_close($c);
    }// fin de funcion modificar
    
    //listado de todos los platos,
    //podemos añadir si select no es null los botones de borrar
    public function listar($select=null){
       
      try {
            $conexion = new Conexion();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');
            $sentancia = "SELECT p.id_plato , p.nombre_plato, a.apto , t.tipo ,p.precio_plato ,p.foto_p FROM platos p , platos_aptos a ,tipos_platos t 
             WHERE p.tipo_p = t.id_tipo AND p.apto_p = a.id_apto  ORDER BY p.nombre_plato;";
            $resultado = $conexion->prepare($sentancia);
            $resultado->execute();

            $tabla = "<table class='table  table-hover'>"
                    . "<th class='bg-warning' scope='col'>Id</th>"
                    . "<th class='bg-warning' scope='col'>nombre Plato</th>";
            $tabla .= "<th class='bg-warning' scope='col'>precio </th>";
            $tabla .= "<th class='bg-warning' scope='col'>Tipo </th>";
            $tabla .= "<th class='bg-warning' scope='col'>Apto </th>";
            if($select==null){
            $tabla .= "<th class='bg-warning' scope='col'>Foto </th>";
            }
            if ($select != null) {
                $tabla .= "<th class='bg-warning' scope='col'>Opcion</th>";
            }
            while ($muestra = $resultado->fetch(PDO::FETCH_ASSOC)) {

                $tabla .= '<tr>';

                $tabla .= '<td >' . $muestra['id_plato'] . '</td>';
                $tabla .= '<td >' . $muestra['nombre_plato'] . '</td>';
                $tabla .= '<td >' . $muestra['precio_plato'] . '</td>';
                $tabla .= '<td >' . $muestra['tipo'] . '</td>';
                $tabla .= '<td >' . $muestra['apto'] . '</td>';
              if($select==null){
                $tabla .= '<td > <img class="tfoto_peque" style="aling:center;" src="../' . $muestra['foto_p'] . '"/></td>';
              }

                if($select!=null){
         $tabla.= "<td><form action='' method='POST'>"
                    . "<input type='hidden' name='id_plato' value='".$muestra['id_plato']."'/>" 
                        
                        ."<button type='submit' class='btn-danger btn-xs'  name='borrar_plato' value='$select'>BORRAR</button></form>"
                      //   ."<button type='submit' class='btn-success btn-xs' name='modificar' value='$select'> MODIFICAR</button></form>"
                 . "</td>"; 
                 
        }
        
        $tabla.= ' </tr>';   
             }
           $tabla.= "</table>";
           $resultado->closeCursor();
         } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoPlatos     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
    
}
        return $tabla; 
    }//fin de listar
     
    //esta funcion nos devolvera una tabla con todos los platos que tengo dependiendo del tipo y de apto;
    //ademas incluye un check para poder seleccionar las id's del plato para luego poder enseñarla  en la web
    public function selecMenu($tipo,$apto){
     try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia="SELECT id_plato , nombre_plato, apto_p , tipo_p ,precio_plato ,foto_p FROM platos  
            WHERE tipo_p = $tipo AND apto_p = $apto ;";
        
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
        
        $tabla = "<table   class='table  table-hover'>"
                    . "<th class='bg-warning' scope='col'>Id</th>"
                    . "<th class='bg-warning' scope='col'>nombre Plato</th>";
            $tabla .= "<th class='bg-warning' scope='col'>precio </th>";
            $tabla .= "<th class='bg-warning' scope='col'>Foto </th>";
             $tabla .= "<form action='' method='POST'>";
            while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){             
                $tabla .= '<tr>';
                $tabla .= '<td ><input type="checkbox" name="check_list[]" value="'.$muestra['id_plato'].'" checked/>  ' . $muestra['id_plato'] . '</td>';
                $tabla .= '<td >' . $muestra['nombre_plato'] . '</td>';
                $tabla .= '<td >' . $muestra['precio_plato'] . '</td>';
                $tabla .= '<td > <img class="tfoto_peque" style="aling:center;" src="../' . $muestra['foto_p'] . '"/></td>';
                $tabla .= ' </tr>'; 
               
            }
             
              $tabla .= "<button type='submit' class='btn-success btn-xs paddingbtn' name='seleccionar'> MANDAR LO SELECIONADO</button></form></table>";
              $resultado->closeCursor();
              
         } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoPlatos     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
    $base=null;
}
        return $tabla; 
 
    }//fin de dselec menu
    
    
    
    /* funcion que selecionara un plato cuya id tipo ya apto  pasamos por parametro
     * es para mostrar en la web, con lo cual solo nos interes mostrar el nombre, precio y foto
     * tendra un check para marcar con la id hidden, que sera el plato que compremos
     */
        public function selecPlatoWEB($tipo,$apto){
     try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia="SELECT id_plato, nombre_plato,precio_plato ,foto_p FROM platosWEB  
            WHERE tipo_p='".$tipo."' AND apto_p='".$apto."' ;";
        
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
        
        $tabla = "<table class='table table-hover sombra paddingbtn'>";
                  
            while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){        
                $tabla .= '<tr>';            
                $tabla .= '<td ><input type="checkbox" name="check_list[]" value="'.$muestra['id_plato'].'"/></td>';                           
                $tabla .= '<td >' . $muestra['nombre_plato'] . '</td>';
                $tabla .= '<td >' . $muestra['precio_plato'] . ' €</td>';
                $tabla .= '<td > <img class="tfoto_peque" style="aling:center;" src="' . $muestra['foto_p'] . '"/></td>';
                $tabla .= ' </tr>'; 
         
         
            }            
             $tabla .="</table>";
         } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoPlatos     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
    $base=null;
}
        return $tabla; 
 
    }//fin de dselec menu
    
    /**insertaremos en la tabla platos web los elementos seleccionadossegun id_plato*/
     public function insertar_platosWEB($id){
       
      try {
            $conexion = new Conexion();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');
            $sentancia = "SELECT * FROM platos  WHERE id_plato='".$id ."' ;";
            $resultado = $conexion->prepare($sentancia);
            $resultado->execute();
            $sqlSentencia= "INSERT INTO platosweb (id_plato,tipo_p,apto_p,precio_plato,foto_p,nombre_plato) VALUES (:id_plato,:tipo_p, :apto_p, :precio_plato , :link_foto_p , :nombre_plato);";
            $resultado2= $conexion->prepare($sqlSentencia); 
            while($row = $resultado->fetch(PDO::FETCH_ASSOC)){
            $resultado2->execute(array(":id_plato"=>$row['id_plato'],":tipo_p"=>$row['tipo_p'] ,":apto_p"=> $row['apto_p'],":precio_plato"=>$row['precio_plato'],":link_foto_p"=>$row['foto_p'],":nombre_plato"=>$row['nombre_plato']));                                
              }
             $resultado->closeCursor();
             $resultado2->closeCursor();
             $mensaje="Tu elección de platos ya esta en al web ";
            return $mensaje;
      } catch (Exception $ex) {

            die('El Error excepcoion estoy en la clase Daoplato     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
              
} finally {
 
}
        
     }  //fin de insertar en web
     
     
     /*
en esta funcion calculo el precio total de los platos escogidos en el menu
           */     
     public function precioTotal($arrayId_plato){
        $arrayprecio=[];
         try {
            $conexion = new Conexion();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');
           
            foreach ($arrayId_plato as $precio=>$valor){
             $sentancia = "SELECT precio_plato FROM platos  WHERE id_plato='". $valor."' ;";
            $resultado = $conexion->prepare($sentancia);           
             $resultado->execute();
              while($row = $resultado->fetch(PDO::FETCH_ASSOC)){               
                array_push($arrayprecio, $row['precio_plato']);                
            }            
            }
             $resultado->closeCursor();
          $precioTotal= array_sum($arrayprecio);
             return $precioTotal;
      } catch (Exception $ex) {           
            die('El Error excepcoion estoy en la clase DAoplato    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
              
} finally {

}
            
     }
     
     //funcion para enseñar en el pedido los nombre de los platos elegidos
     //tengo el aaray de los id_platos: devuelvo un array con los nombres;
     public function nombresPlatos($arrayId_plato){
        $arraynombreplatos=[];
         try {
            $conexion = new Conexion();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');
           
            foreach ($arrayId_plato as $precio=>$valor){
             $sentancia = "SELECT nombre_plato FROM platos  WHERE id_plato='". $valor."' ;";
            $resultado = $conexion->prepare($sentancia);           
             $resultado->execute();
              while($row = $resultado->fetch(PDO::FETCH_ASSOC)){               
                array_push($arraynombreplatos, $row['nombre_plato']);                
            }            
            }
             $resultado->closeCursor();
      
             return $arraynombreplatos;
      } catch (Exception $ex) {           
            die('El Error excepcoion estoy en la clase DAoplato    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
              
} finally {

}
            
     }
     
     public function listarnombreplatos($tipo){
      
         try {
            $conexion = new Conexion();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');   
            $sentancia = "SELECT id_plato ,nombre_plato FROM platos  WHERE tipo_p='".$tipo."' ORDER BY nombre_plato ;";
            $resultado = $conexion->prepare($sentancia);           
             $resultado->execute();
             $numeroregistro=$resultado->rowCount();
             if($numeroregistro==0){
                 $mensaje="No hay platos de este tipo disponibles";
                 return $mensaje;
             }
             $lista="" ;
              while($row = $resultado->fetch(PDO::FETCH_ASSOC)){ 
                 
                 $lista.= '<input type="radio"  name="id_plato"  value="'.$row['id_plato'].'" checked="chequed"/>'. $row['nombre_plato'].'<br> ';       
            }            
            
             $resultado->closeCursor();
      
             return $lista;
      } catch (Exception $ex) {           
            die('El Error excepcoion estoy en la clase DAoplato    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
              
} finally {

}
            
     }
     
}// fin de clase

?>

