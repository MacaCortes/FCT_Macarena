<?php

class receta{
    
private $id_receta;
private $dificultad_r;
private $link_video;
private $link_doc_receta;
private $id_plato;
private $precio_receta;
const TABLA= 'recetas';

public function __construct($dificultad_r,$link_video,$link_doc_receta,$id_plato,$precio_receta) {
   // $this->id_receta=$id_receta;
    $this->dificultad_r=$dificultad_r;
    $this->link_video=$link_video;
    $this->link_doc_receta=$link_doc_receta;
    $this->id_plato=$id_plato;
    $this->precio_receta=$precio_receta;
}
//funciones getters 

    public function getid_receta(){
        return $this->id_receta;
    }
     public function getdificultad(){
        return $this->dificultad_r;
    }
     public function getlink_video(){
        return $this->link_video;
    }
     public function getlink_doc_receta(){
        return $this->link_doc_receta;
    }
     public function getid_plato(){
        return $this->id_plato;
    }
     public function getprecio_receta(){
        return $this->precio_receta;
    }
    
    // settters
    
    public function setid_receta($valor){
        $this->id_receta==$valor;
    }
     public function setdificultad($valor){
        $this->dificultad_r==$valor;
    }
     public function setlink_video($valor){
        $this->link_video==$valor;
    }
     public function setlink_doc_receta($valor){
        $this->link_doc_receta==$valor;
    }    
     public function setid_plato($valor){
        $this->id_plato==$valor;
    }
     public function setprecio_receta($valor){
        $this->precio_receta==$valor;
    }
    public function __toString() {
        echo $this->id_plato;
    }
}//clase raceta