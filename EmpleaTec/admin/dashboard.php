<!DOCTYPE html>
<html lang="es">
<?php
require_once 'auth-check.php';
require_once '../constants/db_config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener estadísticas
    $stmt_students = $conn->query("SELECT COUNT(*) as total FROM tbl_users WHERE role = 'employee'");
    $total_students = $stmt_students->fetch()['total'];
    
    $stmt_employers = $conn->query("SELECT COUNT(*) as total FROM tbl_users WHERE role = 'employer'");
    $total_employers = $stmt_employers->fetch()['total'];
    
    $stmt_jobs = $conn->query("SELECT COUNT(*) as total FROM tbl_jobs");
    $total_jobs = $stmt_jobs->fetch()['total'];
    
    $stmt_applications = $conn->query("SELECT COUNT(*) as total FROM tbl_job_applications");
    $total_applications = $stmt_applications->fetch()['total'];
    
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Administración - EmpleaTec</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../icons/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #2c3e50;
            padding-top: 20px;
            color: white;
            overflow-y: auto;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            color: #ecf0f1;
            font-weight: bold;
        }
        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #34495e;
            border-left: 4px solid #3498db;
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .stat-card h3 {
            font-size: 2.5rem;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .stat-card p {
            color: #7f8c8d;
            font-size: 1rem;
            margin: 0;
        }
        .stat-card .icon {
            font-size: 3rem;
            color: #3498db;
            float: right;
        }
        .card-blue { border-top: 4px solid #3498db; }
        .card-green { border-top: 4px solid #2ecc71; }
        .card-orange { border-top: 4px solid #e67e22; }
        .card-red { border-top: 4px solid #e74c3c; }
        .header-bar {
            background: white;
            padding: 20px 30px;
            margin: -30px -30px 30px -30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header-bar h1 {
            margin: 0;
            color: #2c3e50;
        }
        .btn-custom {
            background: #3498db;
            color: white;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .btn-custom:hover {
            background: #2980b9;
            color: white;
        }
        table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>📊 EMPLEATEC ADMIN</h3>
        <a href="dashboard.php" class="active"><i class="fa fa-dashboard"></i> Dashboard</a>
        <a href="reportes.php"><i class="fa fa-bar-chart"></i> Reportes</a>
        <a href="usuarios.php"><i class="fa fa-users"></i> Usuarios</a>
        <a href="empresas.php"><i class="fa fa-building"></i> Empresas</a>
        <a href="ofertas.php"><i class="fa fa-briefcase"></i> Ofertas Laborales</a>
        <a href="crear-trabajo.php"><i class="fa fa-plus-circle"></i> Crear Oferta</a>
        <hr style="border-color: #34495e;">
        <a href="../index.php"><i class="fa fa-home"></i> Ir al Sitio</a>
        <a href="../logout.php"><i class="fa fa-sign-out"></i> Cerrar Sesión</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header-bar">
            <h1>Dashboard Administrativo</h1>
            <p>Bienvenido al panel de control de EmpleaTec</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card card-blue">
                    <i class="fa fa-users icon"></i>
                    <h3><?php echo number_format($total_students); ?></h3>
                    <p>Estudiantes Registrados</p>
                    <a href="usuarios.php" class="btn-custom">Ver Detalles</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card-green">
                    <i class="fa fa-building icon"></i>
                    <h3><?php echo number_format($total_employers); ?></h3>
                    <p>Empresas Registradas</p>
                    <a href="empresas.php" class="btn-custom">Ver Detalles</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card-orange">
                    <i class="fa fa-briefcase icon"></i>
                    <h3><?php echo number_format($total_jobs); ?></h3>
                    <p>Ofertas Laborales</p>
                    <a href="ofertas.php" class="btn-custom">Ver Detalles</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card card-red">
                    <i class="fa fa-file-text icon"></i>
                    <h3><?php echo number_format($total_applications); ?></h3>
                    <p>Postulaciones Totales</p>
                    <a href="reportes.php" class="btn-custom">Ver Reportes</a>
                </div>
            </div>
        </div>

        <!-- Recent Jobs -->
        <div class="chart-container">
            <h3>Últimas Ofertas Publicadas</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Empresa</th>
                        <th>Ciudad</th>
                        <th>Categoría</th>
                        <th>Tipo</th>
                        <th>Fecha Cierre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT * FROM tbl_jobs ORDER BY enc_id DESC LIMIT 10");
                    $recent_jobs = $stmt->fetchAll();
                    foreach($recent_jobs as $job):
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($job['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($job['company']); ?></td>
                        <td><?php echo htmlspecialchars($job['city']); ?></td>
                        <td><span class="label label-primary"><?php echo htmlspecialchars($job['category']); ?></span></td>
                        <td><span class="label label-info"><?php echo htmlspecialchars($job['type']); ?></span></td>
                        <td><?php echo htmlspecialchars($job['closing_date']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Quick Actions -->
        <div class="row" style="margin-top: 30px;">
            <div class="col-md-12">
                <div class="chart-container">
                    <h3>Acciones Rápidas</h3>
                    <a href="crear-trabajo.php" class="btn btn-success btn-lg" style="margin-right: 10px;">
                        <i class="fa fa-plus"></i> Nueva Oferta Laboral
                    </a>
                    <a href="reportes.php" class="btn btn-info btn-lg" style="margin-right: 10px;">
                        <i class="fa fa-download"></i> Generar Reportes
                    </a>
                    <a href="usuarios.php" class="btn btn-warning btn-lg">
                        <i class="fa fa-search"></i> Buscar Usuarios
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery-1.11.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>