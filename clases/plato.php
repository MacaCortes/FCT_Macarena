<?php
//clase de platos relacionada con la tabla de platos de nuestra base de datos
/* EL PATRON DE DISEÑO DAO (DATA ACCES OBJETS) NOS DICE QUE POR CADA UNA DE LA TABLAS QUE TENGAMOS EN LA BASE DE DATOS, CREAREMOS UNA CLASE LOS 
 * CAMPOS DE LA TABLAS SERAN LOS ATRIBUTOS */
class plato{
    
private $id_plato;
private $nombre_plato;
private $tipo_p;
private $apto_p;
private $precio_plato;
private $foto_p;
const TABLA= 'platos';

public function __construct($tipo_p,$apto_p,$precio_plato,$link_foto_p,$nombre_plato) {
    $this->id_plato=NULL;
    $this->tipo_p=$tipo_p;
    $this->apto_p=$apto_p;
    $this->precio_plato=$precio_plato;
    $this->foto_p=$link_foto_p;
    $this->nombre_plato=$nombre_plato;
}

//funciones getters 

    public function getid_plato(){
        return $this->id_plato;
    }
    public function getnombre_plato(){
        return $this->nombre_plato;
    } public function gettipo_p(){
        return $this->tipo_p;
    } public function getapto_p(){
        return $this->apto_p;
    }
     public function getlink_foto_p(){
        return $this->foto_p;
    }
      public function getprecio_plato(){
        return $this->precio_plato;
    }
 // settters
    /*
    public function setid_plato($valor){
        $this->id_plato==$valor;
    }
     */
     public function setnombre_plato($valor){
        $this->nombre_plato==$valor;
    }
     public function settipo_p($valor){
        $this->tipo_p==$valor;
    }
     public function setapto_p($valor){
        $this->apto_p==$valor;
    }
     public function setlink_foto_p($valor){
        $this->foto_p==$valor;
    }
     public function setprecio_plato($valor){
        $this->precio_plato==$valor;
    }
     public function __toString() {
          return "nombre" .$this->nombre_plato ."<br/>id: " .$this->id_plato ."<br/> apto $this->apto_p "
                  . "<br/> tipo = $this->tipo_p .. Imagen = $this->foto_p , precio = $this->precio_plato";
     }

} //fin calse platos
?>