<?php
session_start();


if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    $user_online = true;
    $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'employee';
    
    // Definir la ruta según el rol
    switch($user_role) {
        case 'admin':
            $myrole = 'admin/dashboard.php';
            break;
        case 'employer':
            $myrole = 'employer/';
            break;
        case 'employee':
            $myrole = 'employee/';
            break;
        default:
            $myrole = 'index.php';
            break;
    }
    
} else {
    $user_online = false;
    $myrole = 'login.php';
}
?>