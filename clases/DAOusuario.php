<?php

spl_autoload_register(function ($clase) {
  require_once "$clase.php";
});
include 'insertar_registro.php';

class DAOusuario{
 private $con;
//constructor
public function DAOusuario(){
    
}  
public function insertar($objeto_usuario){
        try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia= "INSERT INTO usuarios(DNI,nombre,apellidos,direccion,email,telefono,pass,tipo_u)VALUES (:dni, :nombre , :apellido ,:direccion ,:email,:telf,:pass,:tipo_u)";
        $dni=$objeto_usuario->getDni();
        $nombre=$objeto_usuario->getNombre();
        $apellido=$objeto_usuario->getApellido();
        $direccion=$objeto_usuario->getDireccion();
        $telf=$objeto_usuario-> getTelefono();
        $email=$objeto_usuario->getEmail();
       // $pass=$objeto_usuario->getPass();
        $pass = password_hash($objeto_usuario->getPass(), PASSWORD_DEFAULT);
        $tipo_u=$objeto_usuario->gettipo_u();
        
        $resultado= $conexion->prepare($sqlSentencia);      
        $resultado->execute(array(":dni"=> $dni,":nombre"=> $nombre,":apellido"=> $apellido,":direccion"=> $direccion,"email"=> $email,":telf"=> $telf,":pass"=> $pass,":tipo_u"=> $tipo_u));     
        $resultado->closeCursor();
        $mensaje= "El registro se ha efectuado satisfactoriamente, Gracias";           
        } catch (Exception $ex) {
           
           //el error 23000 hace referencia a la clave primaria (DNI) no puede haber dos iguales
            if($ex->getCode()==23000){
            $mensaje=" Error usuario .Este usuario  ya existe !!";
             
            }else{
            die('El Error excepcoion estoy en la clase Ususario     :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            $mensaje= "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
              }
} finally {
    $base=null;
}
        return $mensaje; 
    }//fin de funcion guardar
    
    //comprueba si el usuario esta en la base de datos;
    public function comprueba($dni,$pass){ 
        try{       
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia="SELECT  pass FROM  usuarios WHERE DNI= :dni ";
        $resultado= $conexion->prepare($sqlSentencia);        
  
           $resultado->bindValue(":dni",$dni,PDO::PARAM_INT);
           // $resultado->bindValue(":pass",$pass1);
           $resultado->execute();
          // $row = $resultado->fetch(PDO::FETCH_ASSOC);
           $numeroregistro=$resultado->rowCount();
           if($numeroregistro==0){
              $resultado->closeCursor();
              return false;
           }else{
              $sqlSentencia="SELECT  DISTINCT * FROM  usuarios WHERE DNI= :dni ";
              $resultado= $conexion->prepare($sqlSentencia);
              $resultado->bindValue(":dni",$dni);         
              $resultado->execute();         
             $row = $resultado->fetch(PDO::FETCH_ASSOC);
           }
            //comprobaremos las contraseñas descodificando con pasword_verify
            //PDO::FETCH_ASSOC: devuelve un array indexado
            // por los nombres de las columnas del conjunto de resultados.
           // while ($row) {
                if (password_verify($pass, $row['pass'])) { 
                    session_start();
                    $user = array(
                        'DNI' => $row['DNI'],
                        'nombre' => $row["nombre"],
                        'apellidos' => $row["apellidos"],
                        'direccion' => $row["direccion"],
                        'email' => $row["email"],
                        'telefono' => $row["telefono"],
                        'id_usuario' => $row["id_usuario"],
                        'tipo_u' => $row["tipo_u"]);
                    $_SESSION['login'] = $user;
                    $resultado->closeCursor();                                                  
                    return true;
                       
                } else {
                    $resultado->closeCursor();
                    return false;
                }
          //  }
           
         $resultado->closeCursor();
         
//error en la conexion
    } catch (Exception $ex) {
              die("error: " . $ex->getMessage());
       }
            
    } //fin de comprueba login
    
     
    public function mandar($tipo){   
        
         if(!isset($_SESSION['login'])){
           //  echo "no hay login";
           header("location:../index.php?");
           exit();
         }else{            
             //ususario_comun
             if($tipo==1){              
                $_SESSION['loginUsuario']=$_SESSION['login'];
                 $ok=true;
                 $nombre= $_SESSION['loginUsuario']['nombre'];
                    //  $nombrelogin=$_SESSION['loginUsuario']['nombre'];
                       $mensaje="Bienvenido: ". $nombre;
                    // session_unset($_SESSION['login']);
                     //session_destroy();
                     header("location:../index.php?mensaje=$mensaje&ok=$ok");
                     exit();
                       
                 //usuario Administrador
             }
             if ($tipo==2) {
                $_SESSION['loginAdmin']= $_SESSION['login'];
               //session_unset($_SESSION['login']);
                 //session_destroy();
                 //var_dump($_SESSION['loginAdmin']);
                 header("location:../gestion_admin/comeBienSanoAdministracion.php");
                 exit();
                 
                 //usuario Cocina
            }
            if ($tipo==3) {
                $_SESSION['loginCocina']=$_SESSION['login'];
               //  session_unset($_SESSION['login']);
               //  session_destroy();
                 header("location:../gestion_cocina/comeBienSanoCocina.php");
                 exit();
                
                //usuario reparto
            }
            if ($tipo==4) {
                $_SESSION['loginReparto']=$_SESSION['login'];
              //   session_unset($_SESSION['login']);
              //   session_destroy();
                 header("location:../gestion_reparto/comeBienSanoReparto.php");
                 exit();
            }
            
         }
       
    }//fin de metodo mandar
    
    
     public function mostrarTablaDatos($id,$modificar=null,$array=null){
       
          try{       
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia="SELECT  * FROM  usuarios WHERE id_usuario= :id ";
        $resultado= $conexion->prepare($sqlSentencia);        
        
            $resultado->bindValue(":id",$id);         
            $resultado->execute();        
            $total_column=$resultado->columnCount(); 
            $row=$resultado->fetch(PDO::FETCH_ASSOC);
            /*
             if($array !=null){
               $array=$arrayUser['pedido']; 
               $arrayUser['pedido']['nombre']=$row['nombre'];
                $arrayUser['pedido']['apellidos']=$row['apellidos'];
                  $arrayUser['pedido']['direccion']=$row['direccion'];
                  $arrayUser['pedido']['telefono']=$row['telefono'];
                   $arrayUser['pedido']['telefono']=$row['email'];
                 $resultado->closeCursor();
                 var_dump($arrayUser);
                 return $arrayUser['pedido'];
             }*/
                if (($modificar == null)){
                $tabla = " <table class='table table-hover table-warning'>  ";
                $tabla .= "<tr><td scope='col'>Nombre</td>";
                $tabla .= "<td scope='col'>" . $row['nombre'] . "</td></tr>";
                $tabla .= "<tr><td>Apellidos</td>";
                $tabla .= "<td scope='col'>" . $row['apellidos'] . " </td> </tr>";
                $tabla .= "<tr><td>DNI</td>";
                $tabla .= "<td scope='col'>" . $row['DNI'] . " </td> </tr>";
                $tabla .= "<tr><td scope='col'>Direccion</td>";
                $tabla .= "<td scope='col'>" . $row['direccion'] . " </td></tr>";
                $tabla .= "<tr><td scope='col'>Email</td>";
                $tabla .= "<td>" . $row['email'] . " </td></tr>";
                $tabla .= "<tr><td scope='col'>Teléfono</td>";
                $tabla .= "<td scope='col'>" . $row['telefono'] . " </td></tr>";
                }
                elseif (($modificar != null) ){ 
                 $tabla =  ' <h3> Modificar datos de usuario</h3>';
                $tabla .= " <table class='table table-hover table-info'>  ";
                $tabla.="<form action='' method='POST'>";
                $tabla .= "<tr><td scope='row'>Nombre</td>";
                $tabla .= "<td><input type='text' size='60px' name='nombre' placeholder ='". $row['nombre'] . "' value='".$row['nombre']."'/></td></tr>";
                $tabla .= "<tr><td>Apellidos</td>";
                $tabla .= "<td scope='row'><input type='text'size='60px' name='apellido' placeholder ='". $row['apellidos'] . "' value='".$row['apellidos']."' /> </td> </tr>";
                $tabla .= "<tr><td scope='row'>DNI</td>";
                $tabla .= "<td scope='row'><input type='text' name='dni' placeholder ='". $row['DNI'] ."' readonly/> </td></tr>";
                $tabla .= "<tr><td scope='row'>Direccion</td>";
                $tabla .= "<td><input type='text' size='60px' name='direccion' placeholder ='". $row['direccion'] . "' value='".$row['direccion']."' /></td></tr>";
                $tabla .= "<tr><td scope='col'>Email</td>";
                $tabla .= "<td><input type='text'size='60px' name='email' placeholder ='". $row['email'] . "' value='".$row['email']."' /></td></tr>";
                $tabla .= "<tr><td scope='col'>Teléfono</td>";
                $tabla .= "<td><input type='text' size='40px' name='telefono' placeholder ='". $row['telefono'] . "' value='".$row['telefono']."'/></td></tr>";
                $tabla.= "<tr scope='row'><td colspan='2'>"
                    . "<input type='hidden' name='id_usuario' value='".$row['id_usuario']."'/>"                        
                    ."<button type='submit' class='btn-success btn-xs' name='modificar'> MODIFICAR</button>"
                    ."<button type='submit' style='margin-left:50%'class='btn-danger btn-xs' name='borrar'> ELIMINAR CUENTA USUARIO</button></form>"
                 . "</td></tr>"; 
                  }               
            
          $tabla.=" </table>";
//error en la conexion
    } catch (Exception $ex) {
              die("error: " . $ex->getMessage());
       }

       $resultado->closeCursor();
       return $tabla;
    } //fin demostrar
    
     public function modificar($id_ususario, $nombre,$apellido,$direccion,$email,$telefono){        
          try{       
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sqlSentencia="UPDATE usuarios SET nombre=:nombre, apellidos=:apellido,"
                . " direccion =:direccion, email =:email, telefono =:telefono WHERE id_usuario= :id;";
        $resultado= $conexion->prepare($sqlSentencia);                
       
      //  $resultado->execute(array(":id"=> $id_ususario,":nombre"=> $nombre,":apellido"=> $apellido,":direccion"=> $direccion,"email"=> $email,":telf"=> $telefono));    
         //  $sql = $connect->prepare($consulta);
            $resultado->bindParam(':nombre', $nombre, PDO::PARAM_STR, 25);
            $resultado->bindParam(':apellido', $apellido, PDO::PARAM_STR, 25);
            $resultado->bindParam(':direccion', $direccion, PDO::PARAM_STR, 25);
            $resultado->bindParam(':email', $email, PDO::PARAM_STR, 25);           
            $resultado->bindParam(':id', $id_ususario, PDO::PARAM_INT);
            $resultado->bindParam(':telefono', $telefono, PDO::PARAM_INT);
            $resultado->execute();

            if ($resultado->rowCount() > 0) {
               // $count = $resultado->rowCount();
                $mensaje= "Actualizado !";
                return $mensaje;
            }else{
                $mensaje=" no se a actualizado nada";
                 return $mensaje;
            }
        } catch (Exception $ex) {
              die("error: " . $ex->getMessage());
       }

       $resultado->closeCursor();
      
    } //fin modificar
    public function eliminarUsuario($id_usuario){
         try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia="DELETE FROM usuarios WHERE  id_usuario='".$id_usuario ."';"; 
        $resultado = $conexion->prepare($sentancia);
            $resultado->execute();
            if ($resultado) {
             $mensaje= "¡El registro de usuario se ha sido eliminado!";
            }
        } catch (Exception $ex) {
            $mensaje = $ex;
            die('El Error excepcoion estoy en la clase DaoPlatos     :' . $ex->getCode() . " en la linea  " . $ex->getFile() . " nºlinea " . $ex->getLine());
            echo "la linea de error : " . $ex->getLine() . " en la linea  " . $ex->getFile() . " nºlinea " . $ex->getLine();
        } finally {
    
}
        return $mensaje;  
        
    }


    public function listarTrabajador(){
           try{
        $conexion=new Conexion();
        $conexion->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
        $conexion->exec('SET CHARACTER SET utf8');
        $sentancia="SELECT u.DNI , u.nombre , u.id_usuario , t.user_tipo FROM usuarios u "
                . " JOIN tipo_usuario t ON (u.tipo_u=t.id_user_tipo) WHERE u.tipo_u=2 OR u.tipo_u=3 OR u.tipo_u=4 ";       
        $resultado=$conexion->prepare($sentancia);
        $resultado->execute();
        
        $tabla = "<table   class='table  table-hover'>"
                . "<th class='bg-warning' scope='col'>NOMBRE </th>"
                . "<th class='bg-warning' scope='col'>DNI </th>";
              
            $tabla .= "<th class='bg-warning' scope='col'>CATEGORIA</th>";          
            $tabla .= "<form action='' method='POST'>";
            while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){             
                $tabla .= '<tr>';
                $tabla .= '<td ><input type="radio" name="usuario" value="'.$muestra['id_usuario'].'" checked/>  ' . $muestra['nombre'] . '</td>';             
                $tabla .= '<td >' . $muestra['DNI'] . '</td>';
                $tabla .= '<td >'  . $muestra['user_tipo'] . '</td>';
                $tabla .= ' </tr>';                
            }            
              $tabla .= "<button type='submit' class='btn-success btn-xs paddingbtn' name='seleccionarU'> SELECCIONAR</button></form>";
              $tabla .="</table>";
              $resultado->closeCursor();
         } catch (Exception $ex) {
             echo $ex;
            die('El Error excepcoion estoy en la clase DAOusuario    :' . $ex->getCode()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine());
            echo "la linea de error : " . $ex->getLine()." en la linea  ".$ex->getFile()  ." nºlinea ".$ex->getLine();
    
} finally {
    $base=null;
}
        return $tabla; 
 
    } 
        
    
}// fin de clase DAO usuario
