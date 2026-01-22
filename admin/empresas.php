<?php
session_start();
require '../constants/db_config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("location:../login.php");
    exit();
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM tbl_users WHERE role = 'employer' ORDER BY first_name ASC");
    $stmt->execute();
    $empresas = $stmt->fetchAll();
} catch(PDOException $e) {
    $empresas = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empresas Aliadas | Nelson Torres</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --red-nt: #e30613; --dark-nt: #000; --footer-bg: #262626; }
        body { background: #f4f4f4; font-family: 'Open Sans', sans-serif; display: flex; flex-direction: column; min-height: 100vh; }
        .header { background: var(--dark-nt); padding: 15px 0; border-bottom: 3px solid var(--red-nt); }
        .logo-gris { height: 45px; filter: grayscale(100%) brightness(2); }
        .card-table { background: #fff; border-radius: 8px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .table thead { background: var(--dark-nt); color: #fff; }
        
        /* Estilo del Buscador */
        .search-container { position: relative; max-width: 400px; }
        .search-container i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #888; }
        .search-container input { padding-left: 40px; border-radius: 20px; border: 1px solid #ddd; }
        .search-container input:focus { border-color: var(--red-nt); box-shadow: none; }

        /* Footer Original */
        .main-footer { background-color: var(--footer-bg); color: #aaa; padding: 60px 0 20px; margin-top: auto; font-size: 14px; border-top: 5px solid #333; }
        .footer-title { color: #fff; margin-bottom: 25px; font-size: 18px; text-transform: uppercase; }
        .footer-link { color: #888; display: block; margin-bottom: 12px; text-decoration: none; transition: 0.3s; }
        .footer-link:hover { color: #fff; padding-left: 5px; }
        .social-icons-bar { border-top: 1px solid #333; margin-top: 40px; padding-top: 20px; display: flex; justify-content: space-between; align-items: center; }
        .social-icons-bar a { color: #888; font-size: 18px; margin-left: 20px; transition: 0.3s; }
        .social-icons-bar a:hover { color: var(--red-nt); }
    </style>
</head>
<body>

<header class="header">
    <div class="container d-flex justify-content-between align-items-center">
        <img src="../images/IntGris.png" class="logo-gris" alt="Logo INT">
        <nav>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm px-4 fw-bold">VOLVER AL PANEL</a>
        </nav>
    </div>
</header>

<div class="container mt-5 mb-5">
    <div class="card card-table overflow-hidden">
        <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-0 fw-bold text-dark text-uppercase"><i class="fa fa-building me-2"></i> Empresas Aliadas</h4>
                <p class="text-muted small mb-0">Gestión de entidades empleadoras registradas.</p>
            </div>
            <div class="search-container flex-grow-1 flex-md-grow-0">
                <i class="fa fa-search"></i>
                <input type="text" id="inputBusqueda" class="form-control" placeholder="Buscar empresa o email...">
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaEmpresas">
                    <thead>
                        <tr>
                            <th class="ps-4">Razón Social</th>
                            <th>Email</th>
                            <th>Ciudad</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($empresas as $e): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?php echo $e['first_name']; ?></td>
                            <td><?php echo $e['email']; ?></td>
                            <td><?php echo $e['city'] ?: '---'; ?></td>
                            <td class="text-center">
                                <button onclick="eliminarEmpresa('<?php echo $e['member_no']; ?>')" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($empresas)): ?>
                        <tr id="sinResultados"><td colspan="4" class="text-center py-4">No hay empresas registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="footer-title">Acerca EmpleaTec</h5>
                <p>EmpleaTec es tu plataforma integral para la búsqueda de empleo y la gestión de talento. Diseñada especialmente para conectar a profesionales recién graduados y experimentados con las mejores oportunidades laborales.</p>
            </div>
            <div class="col-md-4 mb-4 ps-md-5">
                <h5 class="footer-title">Sitios Oficiales</h5>
                <a href="http://eva.intsuperior.edu.ec/" target="_blank" class="footer-link">Web Institucional</a>
                <a href="http://siga.institutos.gob.ec:8080" target="_blank" class="footer-link">Plataforma SIGA</a>
                <a href="http://eva.intsuperior.edu.ec/aula-virtual/" target="_blank" class="footer-link">Aula Virtual (EVA)</a>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="footer-title">Contacto Nelson Torres</h5>
                <p class="mb-2"><i class="fa fa-location-dot me-2"></i> Cayambe, Ecuador</p>
                <p class="small text-white-50">Atención: Lunes a Viernes | 02H30 a 18H30</p>
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

<script>
// Lógica del buscador en tiempo real
document.getElementById('inputBusqueda').addEventListener('keyup', function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll('#tablaEmpresas tbody tr:not(#sinResultados)');
    
    filas.forEach(fila => {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
});

function eliminarEmpresa(id) {
    Swal.fire({
        title: '<span style="color:#000">¿Confirmar Eliminación?</span>',
        html: 'Esta acción borrará la empresa y sus vacantes de forma <b>permanente</b>.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e30613',
        cancelButtonColor: '#000',
        confirmButtonText: '<i class="fa fa-trash"></i> SÍ, ELIMINAR',
        cancelButtonText: 'CANCELAR',
        reverseButtons: true,
        backdrop: `rgba(0,0,123,0.1)`
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "eliminar.php?id=" + id + "&tipo=empresa";
        }
    })
}

// Alerta de éxito al eliminar
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('msg') === 'deleted') {
    Swal.fire({
        title: '¡Eliminado!',
        text: 'La empresa ha sido removida del sistema.',
        icon: 'success',
        confirmButtonColor: '#000'
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>