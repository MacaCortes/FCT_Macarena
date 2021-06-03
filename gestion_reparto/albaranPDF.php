<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require "../clases/conexion.php";
require "plantillaPDF.php";

if (!empty($_POST)) {
    
$id_pedido=$_POST['id_pedido'];
$id_receta=$_POST['id_receta'];
    $mysqli= new Conexion();

     $sql = "SELECT p.id_pedido, p.fecha_envio, u.nombre,u.apellidos ,u.direccion,u.telefono  FROM pedidos p "
          . "  JOIN usuarios u ON (p.id_usuario=u.id_usuario) WHERE p.id_pedido='".$id_pedido."';";
     $resultado = $mysqli->query($sql);

    $pdf = new PDF("P", "mm", "letter");
    $pdf->AliasNbPages();
    $pdf->SetMargins(20, 10, 20);
    $pdf->AddPage();
     $pdf->Cell(25,15,"",0,1,"C");
    $pdf->SetFont("Arial", "B", 18);
    $pdf->Cell(100,5, "DATOS PEDIDO", 0, 1, "C");
     $pdf->Cell(25,10,"",0,1,"C");
    $pdf->SetFont("Arial", "B", 10);

    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)){
       $pdf->Cell(40, 9, "id_pedido :", 0, 0, "C");
        $pdf->Cell(40, 9, $fila['id_pedido'], 0, 1, "");
        $pdf->Cell(40, 9, "Nombre :", 0, 0, "C");
        $pdf->Cell(40, 9, utf8_decode($fila['nombre']), 0, 1, "");
        $pdf->Cell(40, 9, "Apellidos :", 0, 0, "C");
        $pdf->Cell(40, 9, utf8_decode($fila['apellidos']), 0, 1, "");
        $pdf->Cell(40, 9, "direccion :", 0, 0, "C");
        $pdf->Cell(100, 9, $fila['direccion'], 0, 1, "");
         $pdf->Cell(40, 9, "telefono :", 0, 0, "C");
        $pdf->Cell(40, 9, $fila['telefono'], 0, 1, "");
       
    }
    $pdf->Cell(25,10,"",0,1,"C");
    $resultado->closeCursor();
    
    
    if($id_receta=="0"){
 
    $sql ="SELECT pl.nombre_plato, pl.precio_plato FROM platos pl 
                        JOIN detalle_pedido dp ON (pl.id_plato=dp.id_plato)
                        WHERE dp.id_pedido='".$id_pedido."';" ;
    
    $resultado = $mysqli->query($sql);
    $pdf->SetFont("Arial", "B", 12);
    $pdf->Cell(100,3, "Platos ", 0, 1, "C");
    $pdf->Cell(25,5,"",0,1,"C");
    $total=[];
    $pdf->SetFont("Arial", "B", 9);
      while($muestra = $resultado->fetch(PDO::FETCH_ASSOC)){
      $pdf->Cell(100, 9,utf8_decode( $muestra['nombre_plato']),1, 0, "C");
      $pdf->Cell(20, 9, utf8_decode($muestra['precio_plato'] .'E'),1, 1, "C");
      array_push($total,$muestra['precio_plato']);
        }
       $precioTotal=array_sum($total);
        $pdf->Cell(100, 9, 'TOTAL',1, 0, "R"); 
      $pdf->Cell(20, 9,utf8_decode( $precioTotal .' E'),1, 1, "C"); 
         $resultado->closeCursor();
    } else {
        $sql = " SELECT  p.fecha_pedido ,p.fecha_envio , p.precio_total,p.estado_final , r.dificultad_r,r.link_video,r.link_doc_receta,pl.nombre_plato ,pl.foto_p "
                . "FROM pedidos p JOIN recetasweb r on (p.id_receta=r.id_receta)"
                . " JOIN platos pl on (r.id_plato=pl.id_plato) WHERE id_pedido='" . $id_pedido . "';";
        $resultado = $mysqli->query($sql);
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(100, 3, "KIT´S ALIMENTOS ", 0, 1, "C");
        $pdf->Cell(25, 5, "", 0, 1, "C");
        $pdf->SetFont("Arial", "B", 9);
        while ($muestra = $resultado->fetch(PDO::FETCH_ASSOC)) {
            
            $pdf->Cell(100, 9,$muestra ['fecha_pedido'], 1, 1, "C");
             $pdf->Cell(100, 9,'RECETA', 1, 0, "C");
            $pdf->Cell(100, 9,utf8_decode( $muestra['nombre_plato']),1, 0, "C");
             $pdf->Cell(100, 9, 'TOTAL',1, 0, "c"); 
            $pdf->Cell(20, 9, utf8_decode($muestra['precio_total'].'E'),1, 1, "C");
           
         }
    }
   
     
   
    $pdf->Output();
}
