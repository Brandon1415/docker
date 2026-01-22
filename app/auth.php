<?php
date_default_timezone_set('America/Guayaquil'); 
$last_login = date('d-m-Y h:i A');
require '../constants/db_config.php';

$myemail = $_POST['email'];
$mypass = md5($_POST['password']);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscamos al usuario por email y contraseña
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE email = :myemail AND login = :mypassword");
    $stmt->bindParam(':myemail', $myemail);
    $stmt->bindParam(':mypassword', $mypass);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $rec = count($result);
    
    if ($rec == "0") {
        // Si no existe, regresa al login con error
        header("location:../login.php?r=0346");
    } else {
        foreach($result as $row) {
            $role = $row['role'];
            session_start();
            
            // Variables de sesión comunes para todos
            $_SESSION['logged'] = true;
            $_SESSION['myid'] = $row['member_no'];
            $_SESSION['myemail'] = $row['email'];
            $_SESSION['role'] = $role;
            $_SESSION['avatar'] = $row['avatar'];
            $_SESSION['lastlogin'] = $row['last_login'];

            // LÓGICA POR ROLES
            if ($role == "employee") {
                // Datos para Candidato
                $_SESSION['myfname'] = $row['first_name'];
                $_SESSION['mylname'] = $row['last_name'];
                $_SESSION['myphone'] = $row['phone'];
                $_SESSION['mycity'] = $row['city'];
                $_SESSION['mydesc'] = $row['about'];
            } 
            else if ($role == "admin") {
                // Datos para Administrador
                $_SESSION['myfname'] = "Administrador";
                $_SESSION['compname'] = "Panel de Control";
            } 
            else {
                // Datos para Empresa (employer)
                $_SESSION['compname'] = $row['first_name'];
                $_SESSION['myphone'] = $row['phone'];
                $_SESSION['mycity'] = $row['city'];
                $_SESSION['website'] = $row['website'];
            }

            // Actualizar la fecha de último acceso en la base de datos
            try {
                $stmt_upd = $conn->prepare("UPDATE tbl_users SET last_login = :lastlogin WHERE email= :email");
                $stmt_upd->bindParam(':lastlogin', $last_login);
                $stmt_upd->bindParam(':email', $myemail);
                $stmt_upd->execute();
            } catch(PDOException $e) {
                // Error silencioso en actualización
            }

            // REDIRECCIÓN FINAL SEGÚN CARPETAS
            if ($role == "admin") {
                // CAMBIO CLAVE: Ahora enviamos al nuevo archivo PHP que creamos
                header("location:../admin/dashboard.php");
            } else {
                // Enviamos a /employee o /employer (PHP)
                header("location:../$role");
            }
        }
    }
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>