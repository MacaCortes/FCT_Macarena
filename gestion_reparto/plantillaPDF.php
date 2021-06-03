<?php



require '../clases/fpdf/fpdf.php';

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Logo
        $this->Image("../imagenes/logo.png", 10, 10 ,50);
        $this->SetFont("Arial", "", 10);      
        $this->SetFont("Arial", "B", 25);  
        $this->Cell(25);
        $this->Cell(140, 10, utf8_decode("ALBARAN"), 0, 0, "C");
        $this->Cell(140, 10, utf8_decode("datos pedido"), 0, 0, "C");
       
        $this->Ln(10);
     
        $this->Cell(25);
        $this->SetFont("Arial", "", 10);
        $this->Cell(150, 10, "Fecha: ". date("d/m/Y"), 0, 1, "C");
      
        // Salto de línea
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
