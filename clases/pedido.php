<?php

/*
 * la clase pedido, tal como en su tabla, la id de pedido se genera utomaticamente;
 * la fecha del pedido la genera automaticamente en la base de datos
 * tabla de pedidos, pondra la fecha actual.Si no ponemos estado, esta por defecto en proceso
 */
class pedido{
 //private $id_pedido;
 private $fecha_pedido;
 private $fecha_envio;
 private $id_usuario;
 private $estado;
 private $precio_total;
 private $id_receta;
 
 public function __construct($fecha_envio,$id_usuario,$precio_total,$id_receta) {
     $this->fecha_envio=$fecha_envio;
     $this->id_usuario=$id_usuario;    
     $this->precio_total=$precio_total;
     $this->fecha_pedido=$fechaActual = date('d-m-Y');
     $this->estado="en proceso";
     $this->id_receta=$id_receta;
 }

 public function getfecha_pedido(){
        return $this->fecha_pedido;
    }
  public function getfecha_envio(){
        return $this->fecha_envio;
    }
     public function getid_usuario(){
        return $this->id_usuario;
    }
     public function getprecio_total(){
        return $this->precio_total;
    }
     public function getid_receta(){
        return $this->id_receta;
    }
     public function setfecha_envio($valor){
        $this->fecha_envio==$valor;
    }
     public function getestado(){
        return $this->estado;
    }
     public function setid_usuario($valor){
        $this->id_usuario==$valor;
    }
     public function setprecio_total($valor){
        $this->precio_total==$valor;
    }
     public function setid_receta($valor){
        $this->id_receta==$valor;
    }
      public function __toString() {
          echo "fecha envio". $this->fecha_envio 
                  .'<br/> ususario ' .$this->id_usuario ."<br/> preceio ". $this->precio_total ."<br/> id receta". $this->id_receta;
      }
}//fin clase pedido