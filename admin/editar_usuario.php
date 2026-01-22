<?php
session_start();

// 1. SEGURIDAD
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:../login.php");
    exit();
}

require '../constants/db_config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_GET['id'];

    // 2. PROCESO DE ACTUALIZACIÓN
    if (isset($_POST['update'])) {
        // Recogemos los datos, si vienen vacíos se guardarán como NULL o vacío según tu DB
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $role  = $_POST['role'];
        $phone = $_POST['phone'];
        $city  = $_POST['city'];

        $stmt = $conn->prepare("
            UPDATE tbl_users 
            SET first_name = :fname,
                last_name = :lname,
                email = :email,
                role = :role,
                phone = :phone,
                city = :city
            WHERE member_no = :id
        ");

        $stmt->execute([
            ':fname' => $fname,
            ':lname' => $lname,
            ':email' => $email,
            ':role'  => $role,
            ':phone' => $phone,
            ':city'  => $city,
            ':id'    => $id
        ]);

        if ($role == 'employer') {
            header("location:empresas.php?msg=updated");
        } else {
            header("location:usuarios.php?msg=updated");
        }
        exit();
    }

    // 3. OBTENER DATOS ACTUALES
    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE member_no = :id");
    $stmt->execute([':id' => $id]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$u) {
        header("location:dashboard.php");
        exit();
    }

} catch(PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario | Admin Nelson Torres</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --red: #e30613; --dark: #000; --footer: #262626; }
        body { background: #f4f4f4; font-family: 'Open Sans', sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
        .header { background: var(--dark); padding: 15px 0; border-bottom: 3px solid var(--red); }
        .logo-gris { height: 45px; filter: grayscale(100%) brightness(2); }
        .container-main { max-width: 800px; margin: 40px auto; flex: 1; width: 100%; }
        .form-section { background: #fff; padding: 40px; border-radius: 8px; border: 1px solid #ddd; }
        .form-label { color: #888; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; margin-bottom: 5px; }
        .form-control, .form-select { border: none; border-bottom: 1px solid #eee; border-radius: 0; padding: 10px 0; margin-bottom: 25px; }
        .form-control:focus { box-shadow: none; border-bottom: 1px solid var(--red); }
        .main-footer { background-color: var(--footer); color: #aaa; padding: 60px 0 20px; font-size: 14px; border-top: 5px solid #333; }
        .footer-title { color: #fff; margin-bottom: 25px; font-size: 18px; text-transform: uppercase; }
        .footer-link { color: #888; display: block; margin-bottom: 12px; text-decoration: none; transition: 0.3s; }
        .footer-link:hover { color: #fff; padding-left: 5px; }
        .social-icons-bar { border-top: 1px solid #333; margin-top: 40px; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; }
        .social-icons-bar a { color: #888; font-size: 18px; margin-left: 20px; }
    </style>
</head>
<body>

<header class="header">
    <div class="container d-flex justify-content-between align-items-center">
        <img src="../images/IntGris.png" class="logo-gris" alt="Logo INT">
        <nav><a href="dashboard.php" class="btn btn-outline-light btn-sm px-4">CANCELAR</a></nav>
    </div>
</header>

<div class="container-main px-3">
    <div class="mb-4 text-center">
        <h2 class="fw-light text-uppercase">Editar Perfil de Usuario</h2>
        <p class="text-muted">ID Registro: <strong>#<?php echo $u['member_no']; ?></strong></p>
    </div>

    <div class="form-section shadow-sm">
        <form method="POST">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Nombres *</label>
                    <input type="text" name="fname" class="form-control" value="<?php echo $u['first_name']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellidos (Opcional)</label>
                    <input type="text" name="lname" class="form-control" value="<?php echo $u['last_name']; ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Correo Electrónico *</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $u['email']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono (Opcional)</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo $u['phone']; ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ciudad (Opcional)</label>
                    <input type="text" name="city" class="form-control" value="<?php echo $u['city']; ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Rol del Usuario</label>
                    <select name="role" class="form-select">
                        <option value="employee" <?php if($u['role']=='employee') echo 'selected'; ?>>Candidato / Postulante</option>
                        <option value="employer" <?php if($u['role']=='employer') echo 'selected'; ?>>Empresa / Empleador</option>
                        <option value="admin" <?php if($u['role']=='admin') echo 'selected'; ?>>Administrador</option>
                    </select>
                </div>
                <div class="col-md-12 mt-4 text-center">
                    <button type="submit" name="update" class="btn btn-dark px-5 py-2 text-uppercase">
                        Actualizar Datos
                    </button>
                    <div class="mt-2 small text-muted"></div>
                </div>
            </div>
        </form>
    </div>
</div>

<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="footer-title">Acerca EmpleaTec</h5>
                <p>Plataforma de vinculación laboral del IST Nelson Torres.</p>
            </div>
            <div class="col-md-4 mb-4 ps-md-5">
                <h5 class="footer-title">Sitios Oficiales</h5>
                <a href="http://eva.intsuperior.edu.ec/" target="_blank" class="footer-link">Web Institucional</a>
                <a href="http://siga.institutos.gob.ec:8080" target="_blank" class="footer-link">SIGA</a>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="footer-title">Contacto</h5>
                <p class="mb-1">Cayambe, Ecuador</p>
                <p class="small">Lunes a Viernes | 02H30 a 18H30</p>
            </div>
        </div>
        <div class="social-icons-bar">
            <div>© 2026 - Desarrollado por <strong>DieDay Soft.</strong></div>
            <div>
                <a href="https://chat.whatsapp.com/FlalUqG1CwqFeU1NPCPQId" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                <a href="https://www.facebook.com/intsuperior/" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/intsuperior/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>