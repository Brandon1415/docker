<?php
session_start();
require_once '../constants/db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $_SESSION['errorMsg'] = true;
        header("Location: ../login.php");
        exit();
    }
    
    try {
        // Conectar a la base de datos
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password_db);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Buscar usuario por email
        $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verificar contraseña
            $stored_password = $user['login'];
            
            // Comprobar si la contraseña está hasheada con MD5 o es texto plano
            if (md5($password) === $stored_password || $password === $stored_password) {
                
                // Login exitoso - Configurar sesión
                $_SESSION['logged'] = true;
                $_SESSION['user_id'] = $user['member_no'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['role'] = $user['role'];
                
                // Actualizar último login
                $update_stmt = $conn->prepare("UPDATE tbl_users SET last_login = NOW() WHERE member_no = :member_no");
                $update_stmt->execute([':member_no' => $user['member_no']]);
                
                // Redirigir según el rol
                switch ($user['role']) {
                    case 'admin':
                        header("Location: ../admin/dashboard.php");
                        break;
                    
                    case 'employer':
                        header("Location: ../employer/");
                        break;
                    
                    case 'employee':
                        header("Location: ../employee/");
                        break;
                    
                    default:
                        // Si no tiene rol definido, redirigir a la página principal
                        header("Location: ../index.php");
                        break;
                }
                exit();
                
            } else {
                // Contraseña incorrecta
                $_SESSION['errorMsg'] = true;
                header("Location: ../login.php?error=invalid_credentials");
                exit();
            }
            
        } else {
            // Usuario no encontrado
            $_SESSION['errorMsg'] = true;
            header("Location: ../login.php?error=user_not_found");
            exit();
        }
        
    } catch(PDOException $e) {
        // Error de base de datos
        $_SESSION['errorMsg'] = true;
        header("Location: ../login.php?error=db_error");
        exit();
    }
    
} else {
    // Acceso directo no permitido
    header("Location: ../login.php");
    exit();
}

date_default_timezone_set('Africa/Dar_es_salaam');
$last_login = date('d-m-Y h:m A [T P]');
require '../constants/db_config.php';
$myemail = $_POST['email'];
$mypass = md5($_POST['password']);

    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE email = :myemail AND login = :mypassword");
	$stmt->bindParam(':myemail', $myemail);
	$stmt->bindParam(':mypassword', $mypass);
    $stmt->execute();
    $result = $stmt->fetchAll();
	$rec = count($result);
	
	if ($rec == "0") {
	 header("location:../login.php?r=0346");
	}else{

    foreach($result as $row)
    {
	$role = $row['role'];
	if ($role == "employee") {
	session_start();
    $_SESSION['logged'] = true;
    $_SESSION['myid'] = $row['member_no'];
    $_SESSION['myfname'] = $row['first_name'];
	$_SESSION['mylname'] = $row['last_name'];
    $_SESSION['myemail'] = $row['email'];
	$_SESSION['mydate'] = $row['bdate'];
	$_SESSION['mymonth'] = $row['bmonth'];
	$_SESSION['myyear'] = $row['byear'];
    $_SESSION['myphone'] = $row['phone'];
	$_SESSION['myedu'] = $row['education'];
	$_SESSION['mytitle'] = $row['title'];
	$_SESSION['mycity'] = $row['city'];
	$_SESSION['mystreet'] = $row['street'];
	$_SESSION['myzip'] = $row['zip'];
    $_SESSION['mycountry'] = $row['country'];
    $_SESSION['mydesc'] = $row['about'];


	$_SESSION['avatar'] = $row['avatar'];
	$_SESSION['lastlogin'] = $row['last_login'];
	$_SESSION['avatar'] = $row['avatar'];
	$_SESSION['gender'] = $row['avatar'];
	$_SESSION['role'] = $role;
	
	}else{
	session_start();
    $_SESSION['logged'] = true;	
	$_SESSION['myid'] = $row['member_no'];
    $_SESSION['compname'] = $row['first_name'];
	$_SESSION['established'] = $row['byear'];
    $_SESSION['myemail'] = $row['email'];
    $_SESSION['myphone'] = $row['phone'];
	$_SESSION['comptype'] = $row['title'];
	$_SESSION['mycity'] = $row['city'];
	$_SESSION['mystreet'] = $row['street'];
	$_SESSION['myzip'] = $row['zip'];
    $_SESSION['mycountry'] = $row['country'];
    $_SESSION['mydesc'] = $row['about'];
	$_SESSION['avatar'] = $row['avatar'];
	$_SESSION['myserv'] = $row['services'];
	$_SESSION['myexp'] = $row['expertise'];
	$_SESSION['lastlogin'] = $row['last_login'];
	$_SESSION['website'] = $row['website'];
	$_SESSION['people'] = $row['people'];
	$_SESSION['role'] = $role;
	
		
	}
	

    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	
    $stmt = $conn->prepare("UPDATE tbl_users SET last_login = :lastlogin WHERE email= :email");
	$stmt->bindParam(':lastlogin', $last_login);
    $stmt->bindParam(':email', $myemail);
    $stmt->execute();
	header("location:../$role");
					  
	}catch(PDOException $e)
    {

    }
	

	}
	
	}

					  
	}catch(PDOException $e)
    {

    }


?>