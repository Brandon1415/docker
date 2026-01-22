<?php
/**
 * REPORTEADOR UNIVERSAL - DieDay Soft
 * Diseñado para evitar Error 500 y Headers Already Sent
 */

// 1. Limpieza total de búferes previos
while (ob_get_level()) {
    ob_end_clean();
}

// 2. Configuración de errores (solo para debug si algo falla)
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en pantalla para no romper el CSV

require '../constants/db_config.php';
session_start();

// 3. Seguridad de acceso
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit("Acceso no autorizado");
}

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'postulantes';

try {
    // Conexión con Charset UTF8 para tildes y eñes
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 4. Selección de Query según el tipo
    if ($tipo == 'empresas') {
        $stmt = $conn->prepare("SELECT first_name, email, city, phone, website FROM tbl_users WHERE role = 'employer' ORDER BY first_name ASC");
        $filename = "Empresas_Aliadas_" . date('Ymd') . ".csv";
        $header = ["NOMBRE EMPRESA", "CORREO ELECTRONICO", "CIUDAD", "TELEFONO", "WEB"];
    } elseif ($tipo == 'vacantes') {
        $stmt = $conn->prepare("SELECT j.title, u.first_name, j.city, j.category, j.date_posted FROM tbl_jobs j LEFT JOIN tbl_users u ON j.company = u.member_no ORDER BY j.date_posted DESC");
        $filename = "Reporte_Vacantes_" . date('Ymd') . ".csv";
        $header = ["TITULO EMPLEO", "EMPRESA", "CIUDAD", "CATEGORIA", "FECHA PUBLICACION"];
    } else {
        $stmt = $conn->prepare("SELECT first_name, last_name, email, city, phone FROM tbl_users WHERE role = 'employee' ORDER BY first_name ASC");
        $filename = "Reporte_Postulantes_" . date('Ymd') . ".csv";
        $header = ["NOMBRES", "APELLIDOS", "CORREO ELECTRONICO", "CIUDAD", "TELEFONO"];
    }

    $stmt->execute();

    // 5. CABECERAS DE DESCARGA (El estándar más compatible)
    header('Content-Description: File Transfer');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    // 6. Generación del archivo
    $output = fopen('php://output', 'w');

    // Insertar BOM UTF-8 para que Excel reconozca tildes y la letra Ñ automáticamente
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Escribir los encabezados usando punto y coma (;) que es el estándar de Excel en Latinoamérica
    fputcsv($output, $header, ";");

    // Escribir los datos
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row, ";");
    }

    fclose($output);
    exit();

} catch(Exception $e) {
    // Si falla, enviamos un log interno pero no rompemos la descarga si ya inició
    error_log("Error en reporte: " . $e->getMessage());
    echo "Error crítico al generar el reporte. Por favor contacte a DieDay Soft.";
}