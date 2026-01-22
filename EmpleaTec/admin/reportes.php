<!DOCTYPE html>
<html lang="es">
<?php
require_once 'auth-check.php';
require_once '../constants/db_config.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reportes - Panel Admin</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../icons/font-awesome/css/font-awesome.min.css">
    <style>
        body { background: #f5f5f5; font-family: 'Arial', sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 250px; background: #2c3e50; padding-top: 20px; color: white; overflow-y: auto; }
        .sidebar h3 { text-align: center; margin-bottom: 30px; color: #ecf0f1; font-weight: bold; }
        .sidebar a { display: block; padding: 15px 25px; color: #ecf0f1; text-decoration: none; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; border-left: 4px solid #3498db; }
        .main-content { margin-left: 250px; padding: 30px; }
        .report-card { background: white; border-radius: 10px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .report-card h4 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-bottom: 20px; }
        .btn-download { background: #27ae60; color: white; padding: 8px 20px; border-radius: 5px; text-decoration: none; display: inline-block; margin-top: 10px; }
        .btn-download:hover { background: #229954; color: white; }
        table { font-size: 0.9rem; }
        .header-bar { background: white; padding: 20px 30px; margin: -30px -30px 30px -30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3>📊 EMPLEATEC ADMIN</h3>
        <a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
        <a href="reportes.php" class="active"><i class="fa fa-bar-chart"></i> Reportes</a>
        <a href="usuarios.php"><i class="fa fa-users"></i> Usuarios</a>
        <a href="empresas.php"><i class="fa fa-building"></i> Empresas</a>
        <a href="ofertas.php"><i class="fa fa-briefcase"></i> Ofertas Laborales</a>
        <a href="crear-trabajo.php"><i class="fa fa-plus-circle"></i> Crear Oferta</a>
        <hr style="border-color: #34495e;">
        <a href="../index.php"><i class="fa fa-home"></i> Ir al Sitio</a>
        <a href="../logout.php"><i class="fa fa-sign-out"></i> Cerrar Sesión</a>
    </div>

    <div class="main-content">
        <div class="header-bar">
            <h1><i class="fa fa-bar-chart"></i> Reportes del Sistema</h1>
            <p>Análisis y estadísticas de EmpleaTec</p>
        </div>

        <!-- Reporte 1: Estadísticas de Estudiantes -->
        <div class="report-card">
            <h4><i class="fa fa-graduation-cap"></i> Estadísticas de Estudiantes</h4>
            <table class="table table-bordered">
                <thead class="bg-primary" style="color: white;">
                    <tr>
                        <th>Género</th>
                        <th>País</th>
                        <th>Total Estudiantes</th>
                        <th>Promedio de Edad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT COUNT(*) AS total_estudiantes, gender, country, 
                                         AVG(YEAR(CURDATE()) - CAST(byear AS SIGNED)) AS promedio_edad
                                         FROM tbl_users WHERE role = 'employee' GROUP BY gender, country");
                    while($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td><?php echo $row['gender'] ?: 'No especificado'; ?></td>
                        <td><?php echo $row['country'] ?: 'No especificado'; ?></td>
                        <td><strong><?php echo number_format($row['total_estudiantes']); ?></strong></td>
                        <td><?php echo round($row['promedio_edad'], 1); ?> años</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="#" class="btn-download" onclick="exportTableToCSV('estudiantes.csv', this)">
                <i class="fa fa-download"></i> Descargar CSV
            </a>
        </div>

        <!-- Reporte 2: Estadísticas de Empresas -->
        <div class="report-card">
            <h4><i class="fa fa-building"></i> Estadísticas de Empresas</h4>
            <table class="table table-bordered">
                <thead class="bg-success" style="color: white;">
                    <tr>
                        <th>Género del Representante</th>
                        <th>País</th>
                        <th>Total Empresas</th>
                        <th>Promedio de Edad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT COUNT(*) AS total_empresas, gender, country, 
                                         AVG(YEAR(CURDATE()) - CAST(byear AS SIGNED)) AS promedio_edad
                                         FROM tbl_users WHERE role = 'employer' GROUP BY gender, country");
                    while($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td><?php echo $row['gender'] ?: 'No especificado'; ?></td>
                        <td><?php echo $row['country'] ?: 'No especificado'; ?></td>
                        <td><strong><?php echo number_format($row['total_empresas']); ?></strong></td>
                        <td><?php echo round($row['promedio_edad'], 1); ?> años</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="#" class="btn-download" onclick="exportTableToCSV('empresas.csv', this)">
                <i class="fa fa-download"></i> Descargar CSV
            </a>
        </div>

        <!-- Reporte 3: Ofertas por Categoría -->
        <div class="report-card">
            <h4><i class="fa fa-briefcase"></i> Ofertas Laborales por Categoría y Tipo</h4>
            <table class="table table-bordered">
                <thead class="bg-warning">
                    <tr>
                        <th>Categoría</th>
                        <th>Tipo</th>
                        <th>Total Ofertas</th>
                        <th>Duración Promedio (días)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT COUNT(*) AS total_ofertas, category, type,
                                         AVG(DATEDIFF(STR_TO_DATE(closing_date, '%Y-%m-%d'), 
                                         STR_TO_DATE(date_posted, '%Y-%m-%d'))) AS duracion_promedio
                                         FROM tbl_jobs GROUP BY category, type");
                    while($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td><?php echo $row['category']; ?></td>
                        <td><span class="label label-info"><?php echo $row['type']; ?></span></td>
                        <td><strong><?php echo number_format($row['total_ofertas']); ?></strong></td>
                        <td><?php echo round($row['duracion_promedio'], 0); ?> días</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="#" class="btn-download" onclick="exportTableToCSV('ofertas-categoria.csv', this)">
                <i class="fa fa-download"></i> Descargar CSV
            </a>
        </div>

        <!-- Reporte 4: Postulaciones por Empleo -->
        <div class="report-card">
            <h4><i class="fa fa-file-text"></i> Postulaciones por Empleo</h4>
            <table class="table table-bordered">
                <thead class="bg-danger" style="color: white;">
                    <tr>
                        <th>ID Empleo</th>
                        <th>Total Postulaciones</th>
                        <th>Postulantes Únicos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT COUNT(*) AS total_postulaciones, job_id,
                                         COUNT(DISTINCT member_no) AS postulantes_por_empleo
                                         FROM tbl_job_applications GROUP BY job_id LIMIT 20");
                    while($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td><?php echo $row['job_id']; ?></td>
                        <td><strong><?php echo number_format($row['total_postulaciones']); ?></strong></td>
                        <td><?php echo number_format($row['postulantes_por_empleo']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="#" class="btn-download" onclick="exportTableToCSV('postulaciones.csv', this)">
                <i class="fa fa-download"></i> Descargar CSV
            </a>
        </div>

        <!-- Reporte 5: Idiomas -->
        <div class="report-card">
            <h4><i class="fa fa-language"></i> Competencias de Idiomas</h4>
            <table class="table table-bordered">
                <thead class="bg-info" style="color: white;">
                    <tr>
                        <th>Idioma</th>
                        <th>Estudiantes</th>
                        <th>Nivel Hablar (Promedio)</th>
                        <th>Nivel Leer (Promedio)</th>
                        <th>Nivel Escribir (Promedio)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conn->query("SELECT language, COUNT(DISTINCT member_no) AS estudiantes_con_idioma,
                                         AVG(CASE WHEN speak = 'Fluent' THEN 3 WHEN speak = 'Intermediate' THEN 2 ELSE 1 END) AS promedio_hablar,
                                         AVG(CASE WHEN reading = 'Fluent' THEN 3 WHEN reading = 'Intermediate' THEN 2 ELSE 1 END) AS promedio_leer,
                                         AVG(CASE WHEN writing = 'Fluent' THEN 3 WHEN writing = 'Intermediate' THEN 2 ELSE 1 END) AS promedio_escribir
                                         FROM tbl_language GROUP BY language");
                    while($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td><strong><?php echo $row['language']; ?></strong></td>
                        <td><?php echo number_format($row['estudiantes_con_idioma']); ?></td>
                        <td><?php echo round($row['promedio_hablar'], 2); ?></td>
                        <td><?php echo round($row['promedio_leer'], 2); ?></td>
                        <td><?php echo round($row['promedio_escribir'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="#" class="btn-download" onclick="exportTableToCSV('idiomas.csv', this)">
                <i class="fa fa-download"></i> Descargar CSV
            </a>
        </div>

    </div>

    <script src="../js/jquery-1.11.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script>
    function exportTableToCSV(filename, button) {
        var csv = [];
        var table = $(button).closest('.report-card').find('table');
        var rows = table.find('tr');
        
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll('td, th');
            for (var j = 0; j < cols.length; j++) 
                row.push(cols[j].innerText);
            csv.push(row.join(","));        
        }
        
        var csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
        var downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }
    </script>
</body>
</html>