// db.js
const mysql = require('mysql2');

// Crear una conexión con la base de datos
const connection = mysql.createConnection({
  host: '107.6.164.22',  // Cambia esto si tu base de datos está en otro servidor
  user: 'granoblesapcolaa_bolsaempleoint',       // Tu usuario de MySQL
  password: '(zio=}Uqny6j',       // Tu contraseña de MySQL
  database: 'granoblesapcolaa_bolsaempleoint' // El nombre de tu base de datos
});

// Verificar que la conexión fue exitosa
connection.connect(err => {
  if (err) {
    console.error('Error conectando a la base de datos: ' + err.stack);
    return;
  }
  console.log('Conexión exitosa a la base de datos.');
});

module.exports = connection;
