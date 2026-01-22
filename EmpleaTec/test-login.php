<?php
// 1️⃣ Cargar conexión a la base de datos
require_once __DIR__ . '/constants/db_config.php';

echo "<h2>🔍 Diagnóstico de Login</h2><hr>";

// 2️⃣ Prueba de conexión
try {
    $stmt = $pdo->query("SELECT 1");
    echo "✅ Conexión a la base de datos: OK<br>";
} catch (Exception $e) {
    die("❌ Fallo de conexión: " . $e->getMessage());
}

// 3️⃣ Verificar tabla de usuarios
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM tbl_users");
    $total = $stmt->fetchColumn();
    echo "✅ Tabla <b>tbl_users</b> encontrada<br>";
    echo "📊 Usuarios registrados: <b>$total</b><br>";
} catch (Exception $e) {
    die("❌ Error al acceder a tbl_users: " . $e->getMessage());
}

// 4️⃣ Mostrar columnas de la tabla
echo "<hr><h3>📋 Columnas de tbl_users</h3>";
$stmt = $pdo->query("DESCRIBE tbl_users");
echo "<ul>";
while ($row = $stmt->fetch()) {
    echo "<li>{$row['Field']} ({$row['Type']})</li>";
}
echo "</ul>";

echo "<hr>🎯 Diagnóstico finalizado correctamente";
?>
