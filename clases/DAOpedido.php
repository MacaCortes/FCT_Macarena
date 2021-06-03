<?php
/*clase Daopedio hace la conexion a la base de datos , aqui se encuentran las setencias relativas a pedidos
 */

spl_autoload_register(function ($clase) {
  require_once "$clase.php";
});
class DAOpedido{
 private $con;
//constructor
public function DAOpedido(){
    
} 
//insertamos pedido

//insertamos el pedido nos devolvera el id que le asigna a ese pedido
public function insertar_pedido($objeto_pedido){
         try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia= "INSERT INTO pedidos (fecha_pedido,fecha_envio,id_usuario,estado_final,precio_total,id_receta ) VALUES (:fecha_pedido,:fecha_envio,:id_usuario,:estado,:precio_total,:id_receta );";
            $fecha_envio = $objeto_pedido->getfecha_envio();
            $fecha_pedido = $objeto_pedido->getfecha_pedido();
            $id_usuario = $objeto_pedido->getid_usuario();
            $precio_total = $objeto_pedido->getprecio_total();
            $estado_final = $objeto_pedido->getestado();
            $id_receta = $objeto_pedido->getid_receta();
            $resultado= $conexion->prepare($sqlSentencia);
            $resultado->execute(array(":fecha_pedido"=>$fecha_pedido ,":fecha_envio"=>$fecha_envio ,":id_usuario"=>$id_usuario,":estado"=>$estado_final,":precio_total"=> $precio_total,":id_receta"=>$id_receta));                
            $idultima=$conexion->lastInsertId();           
            $resultado->closeCursor();
        //$mensaje= "El registro se ha efectuado satisfactoriamente, Gracias";           
        } catch (Exception $ex) {
              echo $ex;
           //el error 23000 hace referencia a la clave primaria  no puede haber dos iguales
            if($ex->getCode()==23000){               
            echo" este pedido ya existe !!";
           
            }else{
            die('El Error excepcoion estoy en la clase DaoPedidos    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    }
} finally {
     $base=null;
}
        return $idultima; 
    }

    //inserto en detalle pedido los platos selecionados con la id del pedido
    public function insertar_detalle_pedido($id_pedido,$arrayId_plato){
        try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        foreach ($arrayId_plato as $info=>$valor){
        $sqlSentencia= "INSERT INTO detalle_pedido (id_pedido,id_plato ) VALUES ('$id_pedido' , '$valor')";
        $resultado= $conexion->prepare($sqlSentencia);
        $resultado->execute();
        $resultado->closeCursor();
        
        }
         } catch (Exception $ex) {
              echo $ex;
           //el error 23000 hace referencia a la clave primaria  no puede haber dos iguales
            if($ex->getCode()==23000){               
            echo" este pedido ya existe !!";
           
            }else{
            die('El Error excepcoion estoy en la clase DaoPedidos    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    }
} finally {
     $base=null;
}
       
    }
 //funcion para comprobar si un plato esta en pedido para poder borarlo o no (el plato)
 public function platoEnPedidos($id_plato){
      try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        
        $sqlSentencia= "SELECT id_plato FROM detalle_pedido  WHERE id_plato= '".$id_plato."';";
        $resultado= $conexion->prepare($sqlSentencia);
        $resultado->execute();
        $numeroregistro=$resultado->rowCount();
             if($numeroregistro==0){              
                $mensaje= "no";
             }else{
                $mensaje= "si"; 
             }
        $resultado->closeCursor();
        
        
         } catch (Exception $ex) {
              echo $ex;
           //el error 23000 hace referencia a la clave primaria  no puede haber dos iguales
            if($ex->getCode()==23000){               
            echo" este pedido ya existe !!";
           
            }else{
            die('El Error excepcoion estoy en la clase DaoPedidos    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    }
} finally {
     $base=null;

 }
return $mensaje;
 }
//listado de pedido por usuario
    public function listarPedidoCompleto(){
   
     try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');        
        $sentancia="SELECT  p.id_pedido,p.fecha_pedido, p.fecha_envio, u.DNI , p.estado_final , p.precio_total  FROM pedidos p 
                           JOIN usuarios u ON (p.id_usuario=u.id_usuario) ORDER BY p.fecha_pedido; " ;
                           
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
      
             
        $tabla = "<table   class='table  table-hover'>"
                
                    . "<th class='bg-warning' scope='col'>ID_PEDIDO</th>"
                    . "<th class='bg-warning' scope='col'>DNI usuario</th>"
                    . "<th class='bg-warning' scope='col'>Fecha pedido</th>";
       
            $tabla .= "<th class='bg-warning' scope='col'>Fecha envio </th>";
            $tabla .= "<th class='bg-warning' scope='col'>ESTADO</th>"; 
            $tabla .= "<th class='bg-warning' scope='col'><i class='fas fa-euro-sign'></i></th>";
            while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){
           
                $tabla .= '<tr>';
                $tabla .= '<td >'.$muestra['id_pedido'].'</td>';
                $tabla .= '<td >'.$muestra['DNI'].'</td>';
                $tabla .= '<td >' . $muestra['fecha_pedido'] . '</td>';
                $tabla .= '<td >' . $muestra['fecha_envio'] . '</td>';
                $tabla .= '<td >'.$muestra['estado_final'] .'</td>';
                $tabla .= '<td >'.$muestra['precio_total'] .'</td>'; 
              
             
            }
          
            $resultado->closeCursor();
        
              
              
         } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoPedidos     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
   
}
        return $tabla; 
     
    }

 //listado de pedido por usuario
    public function listarPedido($id_usuario){
   
     try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');        
        $sentancia="SELECT p.id_pedido,p.fecha_pedido, p.fecha_envio, u.nombre , p.estado_final , p.precio_total , p.id_receta "
                . " FROM pedidos p JOIN usuarios u ON (p.id_usuario=u.id_usuario) "
                . " where  p.id_usuario='".$id_usuario."'; " ;
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
        $numeroregistro=$resultado->rowCount();
       
        if($numeroregistro==0){
            $tabla="De momento no tienes pedidos, Te animamos a que empieces a comer sano!! ";
        }else{
           
        $tabla = "<table   class='table  table-hover'>"
                   . "<th class='bg-warning' scope='col'>CATEGORIA</th>"
                    . "<th class='bg-warning' scope='col'>Fecha pedido</th>";
            $tabla .= "<th class='bg-warning' scope='col'>Fecha envio </th>";
            $tabla .= "<th class='bg-warning' scope='col'>estado</th>";
            $tabla .= "<th class='bg-warning' scope='col'><i class='fas fa-euro-sign'></i></th>";
            while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){
                 $estado=$muestra['estado_final'];
                if($muestra['id_receta']!=0){
                    $categoria="KIST ALIMENTOS";
                }else{
                    $categoria="MENÚ";
                }
                $tabla .= "<form action='' method='POST'>";
                $tabla .= '<tr>';
                $tabla .= '<td >'.$categoria.'</td>';
                
                $tabla .= '<td >' . $muestra['fecha_pedido'] . '</td>';
                $tabla .= '<td >' . $muestra['fecha_envio'] . '</td>';
                 
                if($estado=="entregado"){
                $tabla .= '<td style="color:green;"><strong> Entregado</strong></td>';
                }elseif ($estado=="en reparto") {
                 $tabla .= '<td style="color:blue;"><strong> En Reparto</strong></td>';       
                    } else {
                 $tabla .= '<td style="color:orange;"><strong>En proceso</strong></td>';       
                    }
                $tabla .= '<td >'.$muestra['precio_total'] .'</td>'; 
              
                $tabla.='<input type="hidden" name"id_pedido" value="'.$muestra['id_pedido'].'"/>';
              //  $id_pedido="'".$muestra['id_pedido']."'";
               // $tabla.='<td><button type="submit" mane="detalles" class="btn btn-primary" onclick="mostrar2('.$id_pedido.')" > ver detalles </button></form>';
           
            }
        } 
        
            $resultado->closeCursor();
        
              
              
         } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoPedidos     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
   
}
        return $tabla; 
     
    }
 //comprobamos si el usuaario tiene al gun pedido que no este finalozado, esta funcion la utilizo para luego poder borrar el ususario o no.  
public function comprobarPedidos($id_usuario){
     try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');        
        $sentancia="SELECT  p.id_pedido,p.fecha_pedido, p.fecha_envio, u.nombre , p.estado_final , p.precio_total , p.id_receta  FROM pedidos p 
                           JOIN usuarios u ON (p.id_usuario=u.id_usuario) where p.id_usuario= '".$id_usuario."' and estado_final != 'entregado'; " ;
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
        $numeroregistro=$resultado->rowCount();
        if($numeroregistro== 0){
            $mensaje=true;
        }else{
            $mensaje=false;
        }
        
        
         } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoPedidos     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
   
}
        return $mensaje; 
     
}


    //listado de todos los platos selecionados en un menu , por id_pedido
    public function listardetallesPlatospedido($id_pedido){
         try{
             
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
          $sentencia="SELECT pl.nombre_plato FROM platos pl 
                        JOIN detalle_pedido dp ON (pl.id_plato=dp.id_plato)
                        WHERE dp.id_pedido='".$id_pedido."'; " ;
        $resultado=$conexion->prepare($sentencia);

        $resultado->execute();
        
           $lista="<h3 class='orange font'>Tus platos de pedido</h3>";
            
          while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){  
        
             $lista.='<i class="fas fa-utensils"></i><p>'.$muestra['nombre_plato'].'</p>';
          }  
       
            $resultado->closeCursor();
          } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DaoPedidos    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
   
}
        return $lista; 
     
    }//fin de listardetalles
       
    public function listarXfechaSelecionada($fecha){
      
          try {
            $conexion = new Conexion();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');   
            $sentancia = "SELECT pl.nombre_plato , p.fecha_envio,dp.estado ,p.id_pedido,p.id_receta, COUNT(pl.nombre_plato) as num "
                    . "FROM pedidos p JOIN detalle_pedido dp ON (p.id_pedido = dp.id_pedido) "
                    . "JOIN platos pl on (dp.id_plato= pl.id_plato) WHERE p.fecha_envio ='".$fecha."' GROUP by pl.nombre_plato";
            $resultado = $conexion->prepare($sentancia);
           // $resultado->bindValue(":fecha",$fecha);           
            $resultado->execute(); 
           $numeroregistro=$resultado->rowCount();
          //echo $numeroregistro;
          if($numeroregistro != 0){      
             $tabla = "<table  class='table  table-hover'>"                   
                    . "<th class='bg-warning' scope='col'>nombre Plato</th>";
            $tabla .= "<th class='bg-warning' scope='col'>cantidad </th>";
            $tabla .= "<th class='bg-warning' scope='col'>fecha_envio</th>";
            $tabla .= "<th class='bg-warning' scope='col'>Estado PLATO</th>";                          
              while($row = $resultado->fetch(PDO::FETCH_ASSOC)){               
                $tabla .= '<tr>';                                     
                $tabla .= '<td >' . $row['nombre_plato'] . '</td>';                 
                $tabla .= '<td >' . $row['num'] . '</td>'; 
                $tabla .= '<td >' . $row['fecha_envio']. '</td>';
               
                $tabla.= "<td><form action='' method='POST'>" 
                   . "<input type='hidden' name='fecha_envio' value='".$row['fecha_envio']."'/>"    
                   ."<input type='hidden' name='nombre_plato' value='".$row['nombre_plato']."'/> ";
                        if($row['estado']=="en proceso"){
                  $tabla.="<button type='submit' class='btn-warning btn-xs paddingbtn' name='aReparto'>" .$row['estado']."</button></form></td>";             
                        }elseif($row['estado']=="en reparto"){
                  $tabla.="<button type='button' class='btn-success btn-xs paddingbtn' onclick='areparto(this)' name='aReparto'>" .$row['estado']."</button></form></td>";             
                        } 
                 $tabla .= ' </tr>';
                
            } 
            
            $tabla .= '</tr></table>';
             
          }else{
               $tabla=" <h2 class='orange'>todos los platos estan realizados o son KITS . <br/>Ya no tienes faena, bueno puedes adelantar trabajo de mañana</h2>";            
          
          }
            $resultado->closeCursor();            
           
      } catch (Exception $ex) {           
            die('El Error excepcoion estoy en la clase DAOpedidos    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
           $tabla= "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
              
} finally {

}
         return $tabla;    
     }
     
     //funcion que cambia el estado en detalles pedidos de los platos ya cocinados
   public function cambioEstado($fecha,$nombre_plato){
         $arrayID=[];
         $arraypedidos=[];
        try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');        
        $sentancia="select pl.id_plato,dp.id_pedido FROM platos pl 
               JOIN detalle_pedido dp on (pl.id_plato=dp.id_plato) 
               JOIN pedidos p ON (dp.id_pedido= p.id_pedido) WHERE p.fecha_envio='$fecha' AND pl.nombre_plato='$nombre_plato';";
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
       
            while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){
               $id_Plato= $muestra['id_plato'];
               $id_pedido=$muestra['id_pedido'];
         //  array_push($arrayID, $muestra['id_plato']); 
           //  var_dump($arrayID);
            } 
          
            $resultado->closeCursor();
         //  foreach ($arrayID as $key => $value) {
                 $sentancia="UPDATE detalle_pedido SET estado='en reparto' WHERE id_plato='".$id_Plato."';";
                 $resultado=$conexion->prepare($sentancia);
                 $resultado->execute();                
          //        }
                $resultado->closeCursor();
             
        
         } catch (Exception $ex) {
            $mensaje= $ex;
            die('El Error excepcoion estoy en la clase DaoPedidos     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
           $mensaje="la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
     
}     
return $id_pedido;
    }
    
    
    public function cambioestadoPedio($id_pedido){
       
        try {
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia="SELECT count(id_plato) as platos FROM detalle_pedido where id_pedido='".$id_pedido."';";
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
        $count = $resultado->fetchColumn();
        $resultado->closeCursor();
        echo $count;
        $sentancia2="SELECT count(estado) as estado FROM detalle_pedido where id_pedido='".$id_pedido."' and estado ='en reparto';";
        $resultado2=$conexion->prepare($sentancia2);
        $resultado2->execute();
        $count2 = $resultado2->fetchColumn();
        $resultado2->closeCursor();
           echo "estados".$count2;
          if($count==$count2){
             $sentancia="UPDATE pedidos SET estado_final='en reparto' WHERE id_pedido='".$id_pedido."';";
                 $resultado=$conexion->prepare($sentancia);
                 $resultado->execute();       
          }

} catch (PDOException $err) {
    // Mostramos un mensaje genérico de error.
	echo "Error: ejecutando consulta SQL.";
}
   $resultado->closeCursor();
}
 
    

    public function cambioEstadoFinal($id_pedido){
       try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8'); 
         $sentancia="UPDATE pedidos SET estado_final='entregado' WHERE id_pedido='".$id_pedido."';";
                 $resultado=$conexion->prepare($sentancia);
                 $resultado->execute();                
                 $resultado->closeCursor();
                 $mensaje="PEDIDO FINALIZADO";
         } catch (Exception $ex) {
            $mensaje= $ex;
            die('El Error excepcoion estoy en la clase DaoPedidos     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
           $mensaje="la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
     
}     
return $mensaje;
    }
     

     public function listarFechaspedidos(){
        try {
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia="SELECT DISTINCT(fecha_envio) FROM pedidos where estado_final='en proceso' order by fecha_envio;";
        $resultado= $conexion->query($sqlSentencia);
        $resultado->setFetchMode(PDO::FETCH_ASSOC);
         //$arraytipos=$resultado->fetch(PDO::FETCH_ASSOC); 
        $lista='<form action="" method="POST">';
         while ($row =  $resultado->fetch()) {
            
            $lista.=' <input type="radio"  name="fecha" value="'.$row['fecha_envio'].'" checked="chequed"/>'. $row['fecha_envio'].'<br> ';
        
         }
          $lista.='<input type="submit" name="proximo" value="listar por fecha selecionada"/>';
          $lista.='</form>';
          $resultado->closeCursor(); 
} catch (PDOException $err) {
    // Mostramos un mensaje genérico de error.
	echo "Error: ejecutando consulta SQL.";
    
} finally {
   
}  
return $lista;
    }
  

        
 public function listapedidoReceta($id_pedido){
       try {
         
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia="SELECT p.id_pedido, p.fecha_pedido ,p.fecha_envio , p.precio_total,p.estado_final , r.dificultad_r,r.link_video,r.link_doc_receta,pl.nombre_plato ,pl.foto_p "
                . "FROM pedidos p JOIN recetasweb r on (p.id_receta=r.id_receta)"
                . " JOIN platos pl on (r.id_plato=pl.id_plato) WHERE id_pedido='".$id_pedido."';";
       
           $resultado= $conexion->prepare($sqlSentencia);               
         //  $resultado->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);   
           $resultado->execute();  
           $tabla = "<table class='table table-hover sombra paddingbtn2'>";
          while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){
          $tabla .='<tr><td>REFERENCIA PEDIDO:<strong class="paddingbtn">'. $muestra['id_pedido'].'</strong></td></tr>';
          $tabla .='<tr><td> FECHA_ PEDIDO:<strong class="paddingbtn"> '. $muestra['fecha_pedido'].'</strong></td>';
          $tabla .='<td>FECHA_ ENVIO:<strong class="paddingbtn"> '. $muestra['fecha_envio'].'</strong></td></tr>';
          $tabla .='<tr><td>ESTADO:<strong class="paddingbtn"> '. $muestra['estado_final'].'</strong></td>';
          $tabla .='<td>DIFICULTAD:<strong class="paddingbtn"> '. $muestra['dificultad_r'].'</strong></td></tr>';
          $tabla .='<tr><td><a class="paddingbtn" href="'.$muestra['link_video'] .' "> Enlace al video </a></td></tr>';
          $tabla .='<tr><td><a class="paddingbtn" download href="'.$muestra['link_doc_receta'].'"> DESCARGA TU RECETA</a></td></tr>';
          $tabla .='<tr><td><i class="fas fa-utensils"></i><br/><strong class="paddingbtn">'. $muestra['nombre_plato'].'</strong></td>';
          $tabla .='<td><img class="tfoto_peque" style="aling:center;"src="'.$muestra['foto_p'].'" alt="imagen plato escogido"/></td></tr>';
          
         }
       $tabla.="</table>";
       $resultado->closeCursor(); 
     
} catch (PDOException $err) {
    // Mostramos un mensaje genérico de error.
	echo "Error: ejecutando consulta SQL.";
} finally {
   
}  
  return $tabla;
    }
     
 //listado que aparecera en REPARTO: el estado sera el que este en pedidos estado_final
 public function listarXestadoREparto(){
      
          try {
            $conexion = new Conexion();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conexion->exec('SET CHARACTER SET utf8');   
            $sentancia = "SELECT p.fecha_envio,p.estado_final ,p.id_pedido, p.id_receta ,p.id_usuario FROM pedidos p 
            where p.estado_final='en reparto' ORDER BY p.fecha_envio asc; ";
            $resultado = $conexion->prepare($sentancia);
            $resultado->execute();  
           // $resultado->bindParam(':fecha',$fechaMañana);
          //  $numeroregistro=$resultado->rowCount();

         // if($numeroregistro != 0){ 
           // $muestra = $resultado->fetch(PDO::FETCH_ASSOC);
             $tabla = "<table  class='table  table-hover'>"                   
                    . "<th class='bg-warning' scope='col'>id_pedido</th>";
            $tabla .= "<th class='bg-warning' scope='col'>Categoria </th>";
            $tabla .= "<th class='bg-warning' scope='col'>fecha_envio</th>";
            $tabla .= "<th class='bg-warning' scope='col'></th>";                          
              while(  $muestra = $resultado->fetch(PDO::FETCH_ASSOC)){                
                $tabla .= '<tr>'; 
               
                $tabla .= '<td >' . $muestra['id_pedido'] . '</td>'; 
                  if ($muestra['id_receta']== 0) {             
                $tabla .= '<td > Menu </td>';
                  }else{
                $tabla .= '<td ><form action="" method="POST"><input type="hidden" name="id_pedido" value="'.$muestra['id_pedido'].'"/>'
                        . '<input style="border: 0; color:orange"  onclick="mostrar()" type="submit" name="darId_Kit" value=" Kits Alimentos"/></form></td>';   
                  }
                $tabla .= '<td >' . $muestra['fecha_envio'] . '</td>';

                $tabla .= "<td><form action='albaranPDF.php' method='POST' target='_blank' >"
                        . "<input type='hidden' name='id_usuario' value='" . $muestra['id_usuario'] . "'/>"
                        . "<input type='hidden' name='id_pedido' value='" . $muestra['id_pedido'] . "'/>"
                        . "<input type='hidden' name='id_receta' value='" . $muestra['id_receta'] . "'/> ";
                $tabla .= "<button type='submit' class='btnapp2 paddingbtn' name='detalles'> IMPRIMIR</button></form></td>";
                $tabla .= "<td><form action='' method='POST' >";
                $tabla .= "<input type='hidden' name='id_pedido' value='" . $muestra['id_pedido'] . "'/>";
                $tabla .= "<button type='submit' class='btn-warning btn-xs paddingbtn' name='entregado'> ENTEGADO</button></form></td>";
                $tabla .= ' </tr>';
            }

            $tabla .= '</tr></table>';

            $resultado->closeCursor();            
           
      } catch (Exception $ex) {           
            die('El Error excepcoion estoy en la clase DAOpedidos    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
              
} finally {

}
         return $tabla;    
     }
     
}//fin de clase Dao pedidos