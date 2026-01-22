<?php
require('html_table.php');
require '../../constants/db_config.php';
require '../constants/check-login.php';

if ($user_online == "true") {
    if ($myrole == "employee") {
        // Permitir acceso
    } else {
        header("location:../"); // Redirigir si no es empleado
    }
} else {
    header("location:../"); // Redirigir si no está en línea
}

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = '$myid'");
$stmt->execute();
$result = $stmt->fetchAll();

foreach($result as $row) {
    $mygender = $row['gender'];
    $myemail = $row['email'];
    $mycountry = $row['country'];
    $mycity = $row['city'];
    $mystreet = $row['street'];
    $myzip = $row['zip'];
}

$pdf = new PDF_HTML_Table();
$pdf->AddPage();
$pdf->SetTitle(''.$myfname.' '.$mylname.' CV');

// Definir la fuente para el título principal
$pdf->SetFont('Arial', 'B', 25);
$pdf->SetDrawColor(189, 1, 2); // Color #BD0102 (Rojo oscuro)
$pdf->SetFillColor(189, 1, 2); // Color de relleno #BD0102
$pdf->SetTextColor(255, 255, 255); // Color del texto #FFFFFF (blanco)
$pdf->Ln(30);

// Añadir un título centrado
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetDrawColor(255, 255, 255); // Blanco
$pdf->SetFillColor(239, 23, 24); // Color de fondo #EF1718 (Rojo vivo)
$pdf->SetTextColor(0, 0, 0); // Texto en negro
$pdf->Cell(0, 9, 'Bolsa de Empleo Instituto Nelson Torres', 0, 0, 'C', true);

$pdf->Ln(9);

// Segundo título
$pdf->SetFont('Arial', 'B', 15);
$pdf->SetDrawColor(255, 255, 255); // Blanco
$pdf->SetFillColor(239, 23, 24); // Color de fondo #EF1718
$pdf->SetTextColor(0, 0, 0); // Texto en negro
$pdf->Cell(0, 9, 'CERTIFICADO DE REGISTRO', 0, 0, 'C', true);

$pdf->Ln(20); 

// Centrando la imagen del logo
$imageWidth = 40;
$centerX = ($pdf->GetPageWidth() - $imageWidth) / 2;
$pdf->Image('Logo.png', $centerX, 0, $imageWidth, 35);

$pdf->SetTitle(''.$myfname.' '.$mylname.' CV');
$pdf->SetFont('Arial', 'B', 25);
$pdf->SetDrawColor(189, 1, 2); // Color #BD0102
$pdf->SetFillColor(189, 1, 2); // Color de fondo #BD0102
$pdf->SetTextColor(255, 255, 255); // Blanco
$pdf->Cell(0, 10, ''.$myfname.' '.$mylname.'', 1, 1, 'C', true);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 9, ''.$mytitle.'', 1, 1, 'C', true);

$pdf->Ln(40);

// Texto de registro exitoso
$pdf->SetFont('Arial', 'B', 20);
$pdf->SetDrawColor(255, 255, 255); // Blanco
$pdf->SetFillColor(255, 255, 255); // Blanco
$pdf->SetTextColor(0, 0, 0); // Negro
$pdf->Cell(0, 9, 'USTED SE HA REGISTRADO DE MANERA EXITOSA!!!', 0, 0, 'C', true);

$pdf->Ln(20); 

// Añadir la imagen del QR centrada
$qrWidth = 50;
$centerXQR = ($pdf->GetPageWidth() - $qrWidth) / 2;
$pdf->Image('qr.png', $centerXQR, 160, $qrWidth, 50);

$pdf->Ln(110);

// Nota de descargo
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetDrawColor(255, 255, 255); // Blanco
$pdf->SetFillColor(255, 255, 255); // Blanco
$pdf->SetTextColor(0, 0, 0); // Negro
$pdf->Cell(80, 9, 'Nota de descargo: La informacion contenida en este documento es responsabilidad unica del titular.', 1, 1, 'L', true);
$pdf->Cell(80, 1, 'Cualquier alteracion de dicha informacion puede ocasionar sanciones.', 1, 1, 'L', true);

// Preparar la consulta para los referees
$stmt = $conn->prepare("SELECT * FROM tbl_referees WHERE member_no = '$myid'");
$stmt->execute();
$result = $stmt->fetchAll();

// Manejar los datos de referees
foreach($result as $row) {
    // Puedes agregar código adicional aquí para manejar la información de los referees si es necesario
}

// Salida del PDF
$pdf->Output(''.$myfname.' '.$mylname.' registro.pdf', 'I');
?>
