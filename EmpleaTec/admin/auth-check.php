<?php
/**
 * ARCHIVO DE PROTECCIÓN PARA PANEL ADMINISTRATIVO
 * Incluye este archivo al inicio de cada página del admin
 * 
 * Uso: require_once 'auth-check.php';
 */

session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    // Usuario no autenticado - Redirigir al login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    $_SESSION['errorMsg'] = 'Debes iniciar sesión para acceder a esta área';
    header('Location: ../login.php');
    exit();
}

// Verificar si tiene rol de administrador
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Usuario autenticado pero sin permisos de admin
    $_SESSION['errorMsg'] = 'No tienes permisos para acceder a esta área';
    
    // Redirigir según su rol
    switch($_SESSION['role']) {
        case 'employer':
            header('Location: ../employer/');
            break;
        case 'employee':
            header('Location: ../employee/');
            break;
        default:
            header('Location: ../index.php');
            break;
    }
    exit();
}

// Si llegó aquí, el usuario es admin válido
// Definir variables útiles
$admin_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Administrador';
$admin_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
$admin_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Función para verificar permisos específicos (opcional, para futuro)
function hasPermission($permission) {
    // Por ahora, todos los admin tienen todos los permisos
    // En el futuro puedes agregar lógica de permisos más granular
    return true;
}

// Prevenir caché de páginas sensibles
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>