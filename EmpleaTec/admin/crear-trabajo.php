<!DOCTYPE html>
<html lang="es">
<?php
require_once 'auth-check.php';
require_once '../constants/db_config.php';

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mensaje = '';
$tipo_mensaje = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $job_id = uniqid('JOB_');
        $title = $_POST['title'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $category = $_POST['category'];
        $type = $_POST['type'];
        $experience = $_POST['experience'];
        $description = $_POST['description'];
        $responsibilities = $_POST['responsibilities'];
        $requirements = $_POST['requirements'];
        $company = $_POST['company'];
        $deadline = $_POST['deadline'];
        
        $sql = "INSERT INTO tbl_jobs (job_id, title, city, country, category, type, experience, 
                description, responsibility, requirements, company, date_posted, closing_date)
                VALUES (:job_id, :title, :city, :country, :category, :type, :experience, 
                :description, :responsibilities, :requirements, :company, NOW(), :deadline)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':job_id' => $job_id,
            ':title' => $title,
            ':city' => $city,
            ':country' => $country,
            ':category' => $category,
            ':type' => $type,
            ':experience' => $experience,
            ':description' => $description,
            ':responsibilities' => $responsibilities,
            ':requirements' => $requirements,
            ':company' => $company,
            ':deadline' => $deadline
        ]);
        
        $mensaje = 'Oferta laboral creada exitosamente!';
        $tipo_mensaje = 'success';
    } catch(PDOException $e) {
        $mensaje = 'Error al crear la oferta: ' . $e->getMessage();
        $tipo_mensaje = 'danger';
    }
}

// Obtener lista de empresas
$stmt_companies = $conn->query("SELECT member_no, first_name FROM tbl_users WHERE role = 'employer' ORDER BY first_name");
$companies = $stmt_companies->fetchAll();
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Oferta - Panel Admin</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../icons/font-awesome/css/font-awesome.min.css">
    <style>
        body { background: #f5f5f5; font-family: 'Arial', sans-serif; }
        .sidebar { position: fixed; top: 0; left: 0; height: 100vh; width: 250px; background: #2c3e50; padding-top: 20px; color: white; overflow-y: auto; }
        .sidebar h3 { text-align: center; margin-bottom: 30px; color: #ecf0f1; font-weight: bold; }
        .sidebar a { display: block; padding: 15px 25px; color: #ecf0f1; text-decoration: none; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; border-left: 4px solid #3498db; }
        .main-content { margin-left: 250px; padding: 30px; }
        .form-card { background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header-bar { background: white; padding: 20px 30px; margin: -30px -30px 30px -30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group label { font-weight: bold; color: #2c3e50; }
        .btn-submit { background: #27ae60; color: white; padding: 12px 40px; border: none; border-radius: 5px; font-size: 16px; }
        .btn-submit:hover { background: #229954; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h3>📊 EMPLEATEC ADMIN</h3>
        <a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
        <a href="reportes.php"><i class="fa fa-bar-chart"></i> Reportes</a>
        <a href="usuarios.php"><i class="fa fa-users"></i> Usuarios</a>
        <a href="empresas.php"><i class="fa fa-building"></i> Empresas</a>
        <a href="ofertas.php"><i class="fa fa-briefcase"></i> Ofertas Laborales</a>
        <a href="crear-trabajo.php" class="active"><i class="fa fa-plus-circle"></i> Crear Oferta</a>
        <hr style="border-color: #34495e;">
        <a href="../index.php"><i class="fa fa-home"></i> Ir al Sitio</a>
        <a href="../logout.php"><i class="fa fa-sign-out"></i> Cerrar Sesión</a>
    </div>

    <div class="main-content">
        <div class="header-bar">
            <h1><i class="fa fa-plus-circle"></i> Crear Nueva Oferta Laboral</h1>
            <p>Completa el formulario para publicar una nueva vacante</p>
        </div>

        <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $mensaje; ?>
        </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label><i class="fa fa-briefcase"></i> Título del Puesto *</label>
                            <input type="text" name="title" class="form-control" required placeholder="Ej: Desarrollador Full Stack">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fa fa-building"></i> Empresa *</label>
                            <select name="company" class="form-control" required>
                                <option value="">Seleccione empresa...</option>
                                <?php foreach($companies as $comp): ?>
                                <option value="<?php echo htmlspecialchars($comp['first_name']); ?>">
                                    <?php echo htmlspecialchars($comp['first_name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fa fa-map-marker"></i> Ciudad *</label>
                            <input type="text" name="city" class="form-control" required placeholder="Ej: Quito">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fa fa-globe"></i> País *</label>
                            <input type="text" name="country" class="form-control" required placeholder="Ej: Ecuador">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fa fa-calendar"></i> Fecha de Cierre *</label>
                            <input type="date" name="deadline" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fa fa-tags"></i> Categoría *</label>
                            <select name="category" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <option value="Tecnología">Tecnología</option>
                                <option value="Ventas">Ventas</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Administración">Administración</option>
                                <option value="Finanzas">Finanzas</option>
                                <option value="Recursos Humanos">Recursos Humanos</option>
                                <option value="Ingeniería">Ingeniería</option>
                                <option value="Salud">Salud</option>
                                <option value="Educación">Educación</option>
                                <option value="Otros">Otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fa fa-clock-o"></i> Tipo de Contrato *</label>
                            <select name="type" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <option value="Full-time">Tiempo Completo</option>
                                <option value="Part-time">Tiempo Parcial</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Internship">Pasantía</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><i class="fa fa-star"></i> Experiencia Requerida *</label>
                            <select name="experience" class="form-control" required>
                                <option value="">Seleccione...</option>
                                <option value="Sin experiencia">Sin experiencia</option>
                                <option value="1-2 años">1-2 años</option>
                                <option value="3-5 años">3-5 años</option>
                                <option value="5+ años">5+ años</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-file-text"></i> Descripción del Puesto *</label>
                    <textarea name="description" class="form-control" rows="4" required 
                              placeholder="Describe el puesto y sus objetivos principales..."></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-list"></i> Responsabilidades *</label>
                    <textarea name="responsibilities" class="form-control" rows="4" required 
                              placeholder="Lista las principales responsabilidades del puesto..."></textarea>
                </div>

                <div class="form-group">
                    <label><i class="fa fa-check-circle"></i> Requisitos *</label>
                    <textarea name="requirements" class="form-control" rows="4" required 
                              placeholder="Lista los requisitos y cualificaciones necesarias..."></textarea>
                </div>

                <hr>
                <button type="submit" class="btn-submit">
                    <i class="fa fa-save"></i> Publicar Oferta Laboral
                </button>
                <a href="ofertas.php" class="btn btn-default" style="padding: 12px 40px;">
                    <i class="fa fa-times"></i> Cancelar
                </a>
            </form>
        </div>
    </div>

    <script src="../js/jquery-1.11.3.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>