const express = require('express');
const app = express();
const db = require('./db');  // Configuración de conexión a MySQL
const cors = require('cors');

// Middleware para manejar JSON
app.use(express.json());

// Configurar CORS
app.use(cors({
  origin: 'http://localhost:5173', // Cambia esta URL según tu frontend
  methods: ['GET', 'POST', 'PUT', 'DELETE'],
  allowedHeaders: ['Content-Type', 'Authorization'],
}));

// **Rutas para los reportes completos**

/** 2. Insertar nuevo trabajo */
app.post('/api/create/jobs', (req, res) => {
  const { job_id,title, city, country, category, type, experience, description, responsibilities, requirements, company, deadline } = req.body;

  if (!job_id || !title || !city || !country || !category || !type || !experience || !description || !responsibilities || !requirements || !company || !deadline) {
    return res.status(400).json({ message: 'Todos los campos son requeridos' });
  }

  const query = `
    INSERT INTO tbl_jobs (job_id, title, city, country, category, type, experience, description, responsibility, requirements, company, date_posted, closing_date)
    VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?);
  `;

  // Ejecutar la consulta de inserción
  db.query(query, [job_id, title, city, country, category, type, experience, description, responsibilities, requirements, company, deadline], (err, results) => {
    if (err) {
      console.error('Error al insertar trabajo:', err);
      return res.status(500).json({ message: 'Error al insertar trabajo' });
    }
    res.status(201).json({ message: 'Trabajo insertado correctamente', jobId: results.insertId });
  });
});


/** 1. Reporte: Estudiantes */
app.get('/api/reports/usuarios', (req, res) => {
  const query = `
    SELECT 
      IFNULL(first_name, 'Desconocido') AS first_name,
      IFNULL(last_name, 'Desconocido') AS last_name,
      IFNULL(email, 'No proporcionado') AS email,
      IFNULL(education, 'No especificado') AS education,
      IFNULL(phone, 'No proporcionado') AS phone
    FROM tbl_users
    WHERE role = 'employee';
  `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al obtener empleados:', err);
      return res.status(500).json({ message: 'Error al obtener empleados' });
    }
    res.json(results);
  });
});


/** 2. Reporte: Empresas */
app.get('/api/reports/companies', (req, res) => {
  const query = `
       SELECT 
      IFNULL(first_name, 'Desconocido') AS first_name,
      IFNULL(last_name, 'Desconocido') AS last_name,
      IFNULL(email, 'No proporcionado') AS email,
      IFNULL(education, 'No especificado') AS education,
      IFNULL(phone, 'No proporcionado') AS phone
    FROM tbl_users
    WHERE role = 'employer';
    `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al obtener empresas:', err);
      return res.status(500).json({ message: 'Error al obtener empresas' });
    }
    res.json(results);
  });
});

/** 3. Reporte: Ofertas de Empleo */
app.get('/api/reports/job_offers', (req, res) => {
  const query = `
      SELECT 
        IFNULL(company, 'Sin empresa') AS company,
        IFNULL(title, 'Sin título') AS title,
        IFNULL(city, 'Sin ciudad') AS city,
        IFNULL(category, 'Sin categoría') AS category,
        IFNULL(experience, 'Sin experiencia requerida') AS experience
      FROM tbl_jobs
      WHERE company IS NOT NULL AND title IS NOT NULL;
    `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al obtener ofertas de empleo:', err);
      return res.status(500).json({ message: 'Error al obtener ofertas de empleo' });
    }
    res.json(results);
  });
});


/** 4. Reporte: Número de estudiantes registrados y promedio de edad */
app.get('/api/reports/students', (req, res) => {
  const query = `
        SELECT
      COUNT(*) AS total_estudiantes,
      gender,
      country,
      AVG(YEAR(CURDATE()) - CAST(byear AS SIGNED)) AS promedio_edad
    FROM tbl_users
    WHERE role = 'employee'  
    GROUP BY gender, country;
  `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al generar el reporte de estudiantes:', err);
      return res.status(500).json({ message: 'Error al generar el reporte' });
    }
    res.json(results);
  });
});

/** 4.1. Reporte: Número de empresas registradas*/
app.get('/api/reports/employer', (req, res) => {
  const query = `
        SELECT
      COUNT(*) AS total_empresas,
      gender,
      country,
      AVG(YEAR(CURDATE()) - CAST(byear AS SIGNED)) AS promedio_edad
    FROM tbl_users
    WHERE role = 'employer'  
    GROUP BY gender, country;
  `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al generar el reporte de estudiantes:', err);
      return res.status(500).json({ message: 'Error al generar el reporte' });
    }
    res.json(results);
  });
});

/** 4.1. Reporte: Numero de Ofertas Lavorales Registrados*/
app.get('/api/reports/totalJobs', (req, res) => {
  const query = `
      SELECT COUNT(*) AS total_ofertas_empleo
      FROM tbl_jobs;
  `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al generar el reporte de estudiantes:', err);
      return res.status(500).json({ message: 'Error al generar el reporte' });
    }
    res.json(results);
  });
});

/** 5. Reporte: Ofertas de empleo por categoría y tipo */
app.get('/api/reports/jobs', (req, res) => {
  const query = `
    SELECT
      COUNT(*) AS total_ofertas,
      category,
      type,
      AVG(DATEDIFF(STR_TO_DATE(closing_date, '%Y-%m-%d'), STR_TO_DATE(date_posted, '%Y-%m-%d'))) AS duracion_promedio
    FROM tbl_jobs
    GROUP BY category, type;
  `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al generar el reporte de ofertas de empleo:', err);
      return res.status(500).json({ message: 'Error al generar el reporte' });
    }
    res.json(results);
  });
});

/** 6. Reporte: Postulaciones realizadas */
app.get('/api/reports/applications', (req, res) => {
  const query = `
    SELECT
      COUNT(*) AS total_postulaciones,
      job_id,
      COUNT(DISTINCT member_no) AS postulantes_por_empleo
    FROM tbl_job_applications
    GROUP BY job_id;
  `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al generar el reporte de postulaciones:', err);
      return res.status(500).json({ message: 'Error al generar el reporte' });
    }
    res.json(results);
  });
});

/** 7. Reporte: Experiencia laboral de estudiantes */
app.get('/api/reports/experience', (req, res) => {
  const query = `
    SELECT
      COUNT(DISTINCT member_no) AS estudiantes_con_experiencia,
      AVG(DATEDIFF(STR_TO_DATE(end_date, '%Y-%m-%d'), STR_TO_DATE(start_date, '%Y-%m-%d')) / 365) AS promedio_experiencia
    FROM tbl_experience;
  `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al generar el reporte de experiencia laboral:', err);
      return res.status(500).json({ message: 'Error al generar el reporte' });
    }
    res.json(results);
  });
});

/** 8. Reporte: Competencias de lenguaje */
app.get('/api/reports/languages', (req, res) => {
  const query = `
    SELECT
      language,
      COUNT(DISTINCT member_no) AS estudiantes_con_idioma,
      AVG(CASE WHEN speak = 'Fluent' THEN 1 WHEN speak = 'Intermediate' THEN 2 ELSE 3 END) AS promedio_hablar,
      AVG(CASE WHEN reading = 'Fluent' THEN 1 WHEN reading = 'Intermediate' THEN 2 ELSE 3 END) AS promedio_leer,
      AVG(CASE WHEN writing = 'Fluent' THEN 1 WHEN writing = 'Intermediate' THEN 2 ELSE 3 END) AS promedio_escribir
    FROM tbl_language
    GROUP BY language;
  `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al generar el reporte de competencias de lenguaje:', err);
      return res.status(500).json({ message: 'Error al generar el reporte' });
    }
    res.json(results);
  });
});

/** 9. Reporte: Títulos académicos */
app.get('/api/reports/academic', (req, res) => {
  const query = `
    SELECT
      COUNT(DISTINCT member_no) AS estudiantes_con_titulo,
      level,
      COUNT(*) AS cantidad_por_nivel
    FROM tbl_academic_qualification
    GROUP BY level;
  `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al generar el reporte de títulos académicos:', err);
      return res.status(500).json({ message: 'Error al generar el reporte' });
    }
    res.json(results);
  });
});

/** 10. Reporte: Empresas y ofertas publicadas */
app.get('/api/reports/employers', (req, res) => {
  const query = `
    SELECT
      COUNT(DISTINCT company) AS total_empleadores,
      company,
      COUNT(*) AS ofertas_por_empresa
    FROM tbl_jobs
    GROUP BY company;
  `;
  db.query(query, (err, results) => {
    if (err) {
      console.error('Error al generar el reporte de empleadores:', err);
      return res.status(500).json({ message: 'Error al generar el reporte' });
    }
    res.json(results);
  });
});

// **Inicio del servidor**
const port = 3000;
app.listen(port, () => {
  console.log(`Servidor corriendo en http://localhost:${port}`);
});
