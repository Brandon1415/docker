<?php
$opt = $_GET['opt'];
require '../constants/db_config.php';
require '../constants/uniques.php';
include '../constants/settings.php';
require '../mail/vendor/autoload.php'; // Asegúrate de que la ruta es correcta
use PHPMailer\PHPMailer\PHPMailer; 

    try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE email = :email");
	$stmt->bindParam(':email', $opt);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $rec = count($result);
	
	if ($rec == "0") {
	    print '
	 <div class="alert alert-warning">
     No account is associated with email <strong>'.$opt.'</strong>
	 </div>
     ';
		
	}else{
    foreach($result as $row)
    {
	
    $myfname = $row['first_name'];
	$mylname = $row['last_name'];
	$mymail = $row['email'];
	$full_name = "$myfname $mylname";
	$idt = 'token'.get_rand_numbers(17).'';
    $token = md5($idt);
    $def_link = 'http://'.$_SERVER['HTTP_HOST'].'/reset.php?token='.$token.'';

    $stmt = $conn->prepare("DELETE FROM tbl_tokens WHERE email = :email");
	$stmt->bindParam(':email', $opt);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO tbl_tokens (email, token) VALUES (:email, :token)");
    $stmt->bindParam(':email', $opt);
	$stmt->bindParam(':token', $token);
    $stmt->execute();	

	$message = "Hellow!! <b>$full_name</b>, <br>Click <a href='$def_link'>HERE</a> to reset your <b>BwireJobs</b> password.";

    require '../mail/vendor/phpmailer/phpmailer/src/Exception.php';
    require '../mail/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../mail/vendor/phpmailer/phpmailer/src/SMTP.php';
    require '../mail/vendor/autoload.php';

    $mail = new PHPMailer(true);
    $mail->isSMTP();                                      
    $mail->Host = $smtp_host;
    $mail->SMTPAuth = true;                           
    $mail->Username = $smtp_user;               
    $mail->Password = $smtp_pass;                          
    $mail->SMTPSecure = 'tls';                            
    $mail->Port = 587;                                   

    $mail->setFrom($smtp_user, 'bwirejobs@no-reply');
    $mail->addAddress($mymail , $full_name);              
   
    $mail->isHTML(true);                                 

    $mail->Subject = 'Password Reset';
    $mail->Body    = $message;
    $mail->AltBody = $message;

    if(!$mail->send()) {
    print '
	 <div class="alert alert-danger">
     An error occurred please contact us for more help.
	 </div>
     ';
    } else {
    print '
	 <div class="alert alert-info">
     A link to reset your password was sent to '.$mymail.'.
	 </div>
     ';
    }

	
    }
} 


					  
	}catch(PDOException $e)
    {

    }
	


?>
