<?php
// Iniciamos la sesión solo si no ha sido iniciada previamente
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
    // Usamos el operador ?? '' para asignar un valor vacío si la clave no existe
    $myid = $_SESSION['myid'] ?? '';
    $myfname = $_SESSION['myfname'] ?? '';
    $mylname = $_SESSION['mylname'] ?? '';
    $mygender = $_SESSION['gender'] ?? ''; // Verifica si en tu BD es 'gender' o 'mygender'
    $myemail = $_SESSION['myemail'] ?? '';
    $mydate = $_SESSION['mydate'] ?? '';
    $mymonth = $_SESSION['mymonth'] ?? '';
    $myyear = $_SESSION['myyear'] ?? '';
    $myphone = $_SESSION['myphone'] ?? '';
    $myedu = $_SESSION['myedu'] ?? '';
    $mytitle = $_SESSION['mytitle'] ?? '';
    $mycity = $_SESSION['mycity'] ?? '';
    $mystreet = $_SESSION['mystreet'] ?? '';
    $myzip = $_SESSION['myzip'] ?? '';
    $mycountry = $_SESSION['mycountry'] ?? '';
    $mydesc = $_SESSION['mydesc'] ?? '';
    $myavatar = $_SESSION['avatar'] ?? null;
    $mylogin = $_SESSION['lastlogin'] ?? '';
    $myrole = $_SESSION['role'] ?? '';
    
    $user_online = true; 
} else {
    $user_online = false;
}
?>