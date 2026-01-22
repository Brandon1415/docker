<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:../login.php");
    exit();
}
require '../constants/db_config.php';

try {
    // Definimos el charset para evitar problemas con caracteres especiales
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $total_postulantes = $conn->query("SELECT COUNT(*) FROM tbl_users WHERE role = 'employee'")->fetchColumn();
    $total_empresas = $conn->query("SELECT COUNT(*) FROM tbl_users WHERE role = 'employer'")->fetchColumn();
    $total_vacantes = $conn->query("SELECT COUNT(*) FROM tbl_jobs")->fetchColumn();
} catch(PDOException $e) { 
    $total_postulantes = 0;
    $total_empresas = 0;
    $total_vacantes = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrativo | EmpleaTec</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --red: #e30613; --dark: #000; --footer: #262626; }
        body { background: #f4f4f4; font-family: 'Open Sans', sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
        .header { background: var(--dark); padding: 15px 0; border-bottom: 3px solid var(--red); }
        .logo-gris { height: 50px; filter: grayscale(100%) brightness(2); }
        
        /* Cards de Estadísticas */
        .stat-card { background: #fff; border-radius: 12px; padding: 35px 20px; text-align: center; transition: 0.3s; text-decoration: none; color: #333; display: block; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .stat-card:hover { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(0,0,0,0.1); }
        .stat-card i { transition: 0.3s; color: var(--dark); }
        .stat-card:hover i { color: var(--red); }
        .stat-val { font-size: 2.5rem; font-weight: 800; color: var(--red); margin: 10px 0; }
        
        /* Sección Reportes */
        .report-section { background: #fff; border-radius: 12px; border-left: 5px solid #1f6e43; }
        .btn-excel { background-color: #1f6e43; color: white; border: none; transition: 0.3s; font-weight: bold; }
        .btn-excel:hover { background-color: #165231; color: white; transform: scale(1.02); }

        /* Footer Estilo DieDay Soft */
        .main-footer { background-color: var(--footer); color: #aaa; padding: 60px 0 20px; margin-top: auto; font-size: 14px; border-top: 5px solid #333; }
        .footer-title { color: #fff; margin-bottom: 25px; font-size: 18px; text-transform: uppercase; font-weight: bold; }
        .footer-link { color: #888; display: block; margin-bottom: 12px; text-decoration: none; transition: 0.3s; }
        .footer-link:hover { color: #fff; padding-left: 5px; }
        .social-icons-bar { border-top: 1px solid #333; margin-top: 40px; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; }
        .social-icons-bar a { color: #888; font-size: 18px; margin-left: 20px; transition: 0.3s; }
        .social-icons-bar a:hover { color: var(--red); }
    </style>
</head>
<body>

<header class="header shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <img src="../images/IntGris.png" class="logo-gris" alt="Logo INT">
        <nav>
            <span class="text-white-50 me-3 d-none d-md-inline">Sesión: <strong>Administrador</strong></span>
            <a href="../logout.php" class="btn btn-danger btn-sm px-3 fw-bold shadow-sm">CERRAR SESIÓN</a>
        </nav>
    </div>
</header>

<div class="container mt-5 mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-uppercase" style="letter-spacing: 1px;">Panel de Control Administrativo</h2>
        <div class="bg-danger mx-auto" style="height: 3px; width: 60px;"></div>
    </div>
    
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <a href="usuarios.php" class="stat-card">
                <i class="fa fa-user-graduate fa-3x mb-3"></i>
                <h5 class="fw-bold text-uppercase small text-muted">Postulantes</h5>
                <div class="stat-val"><?php echo $total_postulantes; ?></div>
                <span class="badge bg-dark">Ver Listado</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="vacantes.php" class="stat-card">
                <i class="fa fa-briefcase fa-3x mb-3"></i>
                <h5 class="fw-bold text-uppercase small text-muted">Vacantes</h5>
                <div class="stat-val"><?php echo $total_vacantes; ?></div>
                <span class="badge bg-dark">Gestionar Ofertas</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="empresas.php" class="stat-card">
                <i class="fa fa-building fa-3x mb-3"></i>
                <h5 class="fw-bold text-uppercase small text-muted">Empresas Aliadas</h5>
                <div class="stat-val"><?php echo $total_empresas; ?></div>
                <span class="badge bg-dark">Ver Directorio</span>
            </a>
        </div>
    </div>

    <div class="card mt-5 report-section shadow-sm p-4">
        <div class="row align-items-center">
            <div class="col-md-5">
                <h5 class="fw-bold mb-1 text-dark"><i class="fa fa-file-csv text-success me-2"></i>Descarga de Reportes</h5>
                <p class="text-muted small mb-md-0">Exporta la base de datos a formato Excel (.csv).</p>
            </div>
            <div class="col-md-7 text-md-end">
                <div class="d-grid d-md-flex gap-2 justify-content-md-end">
                    <a href="descargar_reporte.php?tipo=postulantes" class="btn btn-excel btn-sm px-3">
                        <i class="fa fa-download me-1"></i> POSTULANTES
                    </a>
                    <a href="descargar_reporte.php?tipo=empresas" class="btn btn-excel btn-sm px-3">
                        <i class="fa fa-download me-1"></i> EMPRESAS
                    </a>
                    <a href="descargar_reporte.php?tipo=vacantes" class="btn btn-excel btn-sm px-3">
                        <i class="fa fa-download me-1"></i> VACANTES
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> <footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="footer-title">Acerca EmpleaTec</h5>
                <p>EmpleaTec es tu plataforma integral para la búsqueda de empleo y la gestión de talento. Diseñada especialmente para conectar a profesionales recién graduados y experimentados con las mejores oportunidades laborales.</p>
            </div>
            <div class="col-md-4 mb-4 ps-md-5">
                <h5 class="footer-title">Enlaces Oficiales</h5>
                <a href="http://eva.intsuperior.edu.ec/" target="_blank" class="footer-link">Web Institucional</a>
                <a href="http://siga.institutos.gob.ec:8080" target="_blank" class="footer-link">SIGA</a>
                <a href="http://eva.intsuperior.edu.ec/aula-virtual/" target="_blank" class="footer-link">Aula Virtual</a>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="footer-title">Contacto Nelson Torres</h5>
                <p class="mb-2"><i class="fa fa-location-dot me-2 text-danger"></i> Cayambe, vía Ayora</p>
                <p class="small text-white-50">Horario Administrativo:<br>02H30 a 18H30</p>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>