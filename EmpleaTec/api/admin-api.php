<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../constants/db_config.php';

class AdminAPI {
    private $conn;
    
    public function __construct($servername, $username, $password, $dbname) {
        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error de conexión a la base de datos']);
        }
    }
    
    private function sendResponse($code, $data) {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }
    
    // CREAR NUEVO TRABAJO
    public function createJob() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $required = ['job_id', 'title', 'city', 'country', 'category', 'type', 'experience', 
                     'description', 'responsibilities', 'requirements', 'company', 'deadline'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->sendResponse(400, ['message' => "El campo $field es requerido"]);
            }
        }
        
        try {
            $sql = "INSERT INTO tbl_jobs (job_id, title, city, country, category, type, experience, 
                    description, responsibility, requirements, company, date_posted, closing_date)
                    VALUES (:job_id, :title, :city, :country, :category, :type, :experience, 
                    :description, :responsibilities, :requirements, :company, NOW(), :deadline)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':job_id' => $data['job_id'],
                ':title' => $data['title'],
                ':city' => $data['city'],
                ':country' => $data['country'],
                ':category' => $data['category'],
                ':type' => $data['type'],
                ':experience' => $data['experience'],
                ':description' => $data['description'],
                ':responsibilities' => $data['responsibilities'],
                ':requirements' => $data['requirements'],
                ':company' => $data['company'],
                ':deadline' => $data['deadline']
            ]);
            
            $this->sendResponse(201, [
                'message' => 'Trabajo creado correctamente',
                'jobId' => $this->conn->lastInsertId()
            ]);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al crear trabajo: ' . $e->getMessage()]);
        }
    }
    
    // REPORTE: ESTUDIANTES (EMPLEADOS)
    public function getUsuarios() {
        try {
            $sql = "SELECT 
                    IFNULL(first_name, 'Desconocido') AS first_name,
                    IFNULL(last_name, 'Desconocido') AS last_name,
                    IFNULL(email, 'No proporcionado') AS email,
                    IFNULL(education, 'No especificado') AS education,
                    IFNULL(phone, 'No proporcionado') AS phone
                    FROM tbl_users
                    WHERE role = 'employee'";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al obtener empleados']);
        }
    }
    
    // REPORTE: EMPRESAS
    public function getCompanies() {
        try {
            $sql = "SELECT 
                    IFNULL(first_name, 'Desconocido') AS first_name,
                    IFNULL(last_name, 'Desconocido') AS last_name,
                    IFNULL(email, 'No proporcionado') AS email,
                    IFNULL(education, 'No especificado') AS education,
                    IFNULL(phone, 'No proporcionado') AS phone
                    FROM tbl_users
                    WHERE role = 'employer'";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al obtener empresas']);
        }
    }
    
    // REPORTE: OFERTAS DE EMPLEO
    public function getJobOffers() {
        try {
            $sql = "SELECT 
                    IFNULL(company, 'Sin empresa') AS company,
                    IFNULL(title, 'Sin título') AS title,
                    IFNULL(city, 'Sin ciudad') AS city,
                    IFNULL(category, 'Sin categoría') AS category,
                    IFNULL(experience, 'Sin experiencia requerida') AS experience
                    FROM tbl_jobs
                    WHERE company IS NOT NULL AND title IS NOT NULL";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al obtener ofertas']);
        }
    }
    
    // REPORTE: ESTADÍSTICAS DE ESTUDIANTES
    public function getStudents() {
        try {
            $sql = "SELECT
                    COUNT(*) AS total_estudiantes,
                    gender,
                    country,
                    AVG(YEAR(CURDATE()) - CAST(byear AS SIGNED)) AS promedio_edad
                    FROM tbl_users
                    WHERE role = 'employee'
                    GROUP BY gender, country";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al generar reporte']);
        }
    }
    
    // REPORTE: ESTADÍSTICAS DE EMPRESAS
    public function getEmployers() {
        try {
            $sql = "SELECT
                    COUNT(*) AS total_empresas,
                    gender,
                    country,
                    AVG(YEAR(CURDATE()) - CAST(byear AS SIGNED)) AS promedio_edad
                    FROM tbl_users
                    WHERE role = 'employer'
                    GROUP BY gender, country";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al generar reporte']);
        }
    }
    
    // REPORTE: TOTAL DE OFERTAS LABORALES
    public function getTotalJobs() {
        try {
            $sql = "SELECT COUNT(*) AS total_ofertas_empleo FROM tbl_jobs";
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetch();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al generar reporte']);
        }
    }
    
    // REPORTE: OFERTAS POR CATEGORÍA Y TIPO
    public function getJobs() {
        try {
            $sql = "SELECT
                    COUNT(*) AS total_ofertas,
                    category,
                    type,
                    AVG(DATEDIFF(STR_TO_DATE(closing_date, '%Y-%m-%d'), 
                        STR_TO_DATE(date_posted, '%Y-%m-%d'))) AS duracion_promedio
                    FROM tbl_jobs
                    GROUP BY category, type";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al generar reporte']);
        }
    }
    
    // REPORTE: POSTULACIONES
    public function getApplications() {
        try {
            $sql = "SELECT
                    COUNT(*) AS total_postulaciones,
                    job_id,
                    COUNT(DISTINCT member_no) AS postulantes_por_empleo
                    FROM tbl_job_applications
                    GROUP BY job_id";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al generar reporte']);
        }
    }
    
    // REPORTE: EXPERIENCIA LABORAL
    public function getExperience() {
        try {
            $sql = "SELECT
                    COUNT(DISTINCT member_no) AS estudiantes_con_experiencia,
                    AVG(DATEDIFF(STR_TO_DATE(end_date, '%Y-%m-%d'), 
                        STR_TO_DATE(start_date, '%Y-%m-%d')) / 365) AS promedio_experiencia
                    FROM tbl_experience";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetch();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al generar reporte']);
        }
    }
    
    // REPORTE: IDIOMAS
    public function getLanguages() {
        try {
            $sql = "SELECT
                    language,
                    COUNT(DISTINCT member_no) AS estudiantes_con_idioma,
                    AVG(CASE WHEN speak = 'Fluent' THEN 1 WHEN speak = 'Intermediate' THEN 2 ELSE 3 END) AS promedio_hablar,
                    AVG(CASE WHEN reading = 'Fluent' THEN 1 WHEN reading = 'Intermediate' THEN 2 ELSE 3 END) AS promedio_leer,
                    AVG(CASE WHEN writing = 'Fluent' THEN 1 WHEN writing = 'Intermediate' THEN 2 ELSE 3 END) AS promedio_escribir
                    FROM tbl_language
                    GROUP BY language";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al generar reporte']);
        }
    }
    
    // REPORTE: TÍTULOS ACADÉMICOS
    public function getAcademic() {
        try {
            $sql = "SELECT
                    COUNT(DISTINCT member_no) AS estudiantes_con_titulo,
                    level,
                    COUNT(*) AS cantidad_por_nivel
                    FROM tbl_academic_qualification
                    GROUP BY level";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al generar reporte']);
        }
    }
    
    // REPORTE: EMPRESAS Y OFERTAS PUBLICADAS
    public function getEmployersReport() {
        try {
            $sql = "SELECT
                    COUNT(DISTINCT company) AS total_empleadores,
                    company,
                    COUNT(*) AS ofertas_por_empresa
                    FROM tbl_jobs
                    GROUP BY company";
            
            $stmt = $this->conn->query($sql);
            $result = $stmt->fetchAll();
            $this->sendResponse(200, $result);
        } catch(PDOException $e) {
            $this->sendResponse(500, ['message' => 'Error al generar reporte']);
        }
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = isset($_GET['path']) ? $_GET['path'] : '';
        
        // POST /api/create/jobs
        if ($method === 'POST' && $path === 'create/jobs') {
            $this->createJob();
        }
        // GET /api/reports/*
        elseif ($method === 'GET' && strpos($path, 'reports/') === 0) {
            $report = str_replace('reports/', '', $path);
            
            switch($report) {
                case 'usuarios':
                    $this->getUsuarios();
                    break;
                case 'companies':
                    $this->getCompanies();
                    break;
                case 'job_offers':
                    $this->getJobOffers();
                    break;
                case 'students':
                    $this->getStudents();
                    break;
                case 'employer':
                    $this->getEmployers();
                    break;
                case 'totalJobs':
                    $this->getTotalJobs();
                    break;
                case 'jobs':
                    $this->getJobs();
                    break;
                case 'applications':
                    $this->getApplications();
                    break;
                case 'experience':
                    $this->getExperience();
                    break;
                case 'languages':
                    $this->getLanguages();
                    break;
                case 'academic':
                    $this->getAcademic();
                    break;
                case 'employers':
                    $this->getEmployersReport();
                    break;
                default:
                    $this->sendResponse(404, ['message' => 'Endpoint no encontrado']);
            }
        }
        else {
            $this->sendResponse(404, ['message' => 'Ruta no encontrada']);
        }
    }
}

// INICIALIZAR API
$api = new AdminAPI($servername, $username, $password, $dbname);
$api->handleRequest();
?>