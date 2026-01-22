<?php
session_start();
require '../constants/db_config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:../login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = $_GET['id'];
    $tipo = $_GET['tipo'];

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($tipo == 'empresa') {
            // 1. Primero eliminamos todas las vacantes de esa empresa
            // Usamos 'company' o el campo que relacione la vacante con el usuario
            $stmt1 = $conn->prepare("DELETE FROM tbl_jobs WHERE company = :id");
            $stmt1->execute([':id' => $id]);

            // 2. Luego eliminamos la empresa de tbl_users
            $stmt2 = $conn->prepare("DELETE FROM tbl_users WHERE member_no = :id");
            $stmt2->execute([':id' => $id]);
            
            $redireccion = 'empresas.php';
        } 
        else if ($tipo == 'usuario') {
            $stmt = $conn->prepare("DELETE FROM tbl_users WHERE member_no = :id");
            $stmt->execute([':id' => $id]);
            $redireccion = 'usuarios.php';
        } 
        else if ($tipo == 'vacante') {
            $stmt = $conn->prepare("DELETE FROM tbl_jobs WHERE job_id = :id");
            $stmt->execute([':id' => $id]);
            $redireccion = 'vacantes.php';
        }

        header("location:" . $redireccion . "?msg=deleted");
        exit();

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}