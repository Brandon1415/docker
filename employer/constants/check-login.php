<?php
// Aseguramos que la sesión esté iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    // Usamos el operador ?? '' para que si la clave no existe, asigne un texto vacío y no dé error
    $myid = $_SESSION['myid'] ?? '';
    $compname = $_SESSION['compname'] ?? 'Empresa';
    $esta = $_SESSION['established'] ?? '';
    $mymail = $_SESSION['myemail'] ?? '';
    $myphone = $_SESSION['myphone'] ?? '';
    $comptype = $_SESSION['comptype'] ?? '';
    $city = $_SESSION['mycity'] ?? '';
    $street = $_SESSION['mystreet'] ?? '';
    $zip = $_SESSION['myzip'] ?? '';
    $country = $_SESSION['mycountry'] ?? '';
    $desc = $_SESSION['mydesc'] ?? '';
    $logo = $_SESSION['avatar'] ?? null;
    $mylogin = $_SESSION['lastlogin'] ?? '';
    $myrole = $_SESSION['role'] ?? '';
    $myserv = $_SESSION['myserv'] ?? '';
    $myex = $_SESSION['myexp'] ?? '';	
    $mytitle = $_SESSION['comptype'] ?? '';
    $myweb = $_SESSION['website'] ?? '';
    $mypeople = $_SESSION['people'] ?? '';
    $user_online = true;	
} else {
    $user_online = false;
}
?>