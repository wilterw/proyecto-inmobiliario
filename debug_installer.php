<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Instalador - Diagnóstico</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007cba; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        .section { background: #f9f9f9; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #007cba; }
        .error { border-left-color: #dc3545; background: #f8d7da; }
        .success { border-left-color: #28a745; background: #d4edda; }
        .warning { border-left-color: #ffc107; background: #fff3cd; }
        pre { background: #f1f1f1; padding: 10px; border-radius: 3px; overflow-x: auto; }
        .btn { display: inline-block; padding: 10px 20px; background: #007cba; color: white; text-decoration: none; border-radius: 5px; margin: 5px; border: none; cursor: pointer; }
        .btn:hover { background: #005a87; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<div class="container">
    <h1>🔧 Debug Instalador - Diagnóstico del Problema</h1>
    
    <div class="section warning">
        <h3>⚠️ Problema Reportado</h3>
        <p><strong>Síntoma:</strong> "Probar Conexión" funciona pero "Continuar" da error "Host y usuario son requeridos"</p>
    </div>

    <h2>📊 Información del Sistema</h2>
    
    <div class="section">
        <h3>🌐 Variables de Entorno</h3>
        <table>
            <tr><th>Variable</th><th>Valor</th></tr>
            <tr><td>DB_HOST</td><td><?php echo $_ENV['DB_HOST'] ?? '<span style="color:red;">No definido</span>'; ?></td></tr>
            <tr><td>DB_USER</td><td><?php echo $_ENV['DB_USER'] ?? '<span style="color:red;">No definido</span>'; ?></td></tr>
            <tr><td>DB_NAME</td><td><?php echo $_ENV['DB_NAME'] ?? '<span style="color:red;">No definido</span>'; ?></td></tr>
            <tr><td>DB_PASS</td><td><?php echo !empty($_ENV['DB_PASS']) ? '***[CONFIGURADO]***' : '<span style="color:red;">No definido</span>'; ?></td></tr>
        </table>
    </div>

    <div class="section">
        <h3>📦 Datos POST (Último Envío)</h3>
        <?php if (!empty($_POST)): ?>
            <pre><?php print_r($_POST); ?></pre>
        <?php else: ?>
            <p style="color: #888;">No hay datos POST disponibles</p>
        <?php endif; ?>
    </div>

    <div class="section">
        <h3>🎯 Datos de Sesión</h3>
        <?php if (!empty($_SESSION)): ?>
            <pre><?php print_r($_SESSION); ?></pre>
        <?php else: ?>
            <p style="color: #888;">No hay datos de sesión disponibles</p>
        <?php endif; ?>
    </div>

    <h2>📁 Verificación de Archivos</h2>
    
    <div class="section">
        <h3>🔍 Archivos de Configuración</h3>
        <table>
            <tr><th>Archivo</th><th>Estado</th><th>Tamaño</th></tr>
            <?php
            $configFiles = [
                'config/database.php',
                'config/config.php',
                '.env',
                'install.php',
                'setup.php',
                'installer.php',
                'wizard.php'
            ];
            
            foreach ($configFiles as $file) {
                $exists = file_exists($file);
                $size = $exists ? filesize($file) : 0;
                echo "<tr>";
                echo "<td>$file</td>";
                echo "<td>" . ($exists ? '<span style="color:green;">✅ Existe</span>' : '<span style="color:red;">❌ No existe</span>') . "</td>";
                echo "<td>" . ($exists ? number_format($size) . ' bytes' : '-') . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <h2>🧪 Pruebas de Conexión</h2>
    
    <?php if (isset($_POST['test_connection'])): ?>
    <div class="section">
        <h3>🔌 Resultado de Prueba Manual</h3>
        <?php
        $host = $_POST['db_host'] ?? '';
        $user = $_POST['db_user'] ?? '';
        $pass = $_POST['db_pass'] ?? '';
        $dbname = $_POST['db_name'] ?? '';
        
        if (empty($host) || empty($user)) {
            echo '<div class="error"><strong>❌ Error:</strong> Host y usuario son requeridos para la prueba</div>';
        } else {
            try {
                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
                $pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                
                $version = $pdo->query("SELECT VERSION() as version")->fetch();
                
                echo '<div class="success">';
                echo '<strong>✅ Conexión exitosa!</strong><br>';
                echo 'Versión: ' . htmlspecialchars($version['version']) . '<br>';
                echo 'Host: ' . htmlspecialchars($host) . '<br>';
                echo 'Usuario: ' . htmlspecialchars($user) . '<br>';
                echo 'Base de datos: ' . htmlspecialchars($dbname);
                echo '</div>';
                
                // Guardar en sesión para simular el comportamiento del instalador
                $_SESSION['installer_db'] = [
                    'host' => $host,
                    'user' => $user,
                    'pass' => $pass,
                    'dbname' => $dbname,
                    'tested_at' => date('Y-m-d H:i:s')
                ];
                
            } catch (PDOException $e) {
                echo '<div class="error"><strong>❌ Error de conexión:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
    </div>
    <?php endif; ?>

    <div class="section">
        <h3>🧪 Formulario de Prueba</h3>
        <form method="POST" style="display: grid; gap: 10px; max-width: 400px;">
            <label>Host de Base de Datos:</label>
            <input type="text" name="db_host" value="<?php echo htmlspecialchars($_POST['db_host'] ?? 'localhost'); ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            
            <label>Usuario:</label>
            <input type="text" name="db_user" value="<?php echo htmlspecialchars($_POST['db_user'] ?? 'root'); ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            
            <label>Contraseña:</label>
            <input type="password" name="db_pass" value="<?php echo htmlspecialchars($_POST['db_pass'] ?? ''); ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            
            <label>Nombre de Base de Datos:</label>
            <input type="text" name="db_name" value="<?php echo htmlspecialchars($_POST['db_name'] ?? 'inmobiliaria_db'); ?>" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            
            <button type="submit" name="test_connection" class="btn">🔌 Probar Conexión</button>
        </form>
    </div>

    <?php if (isset($_SESSION['installer_db'])): ?>
    <div class="section success">
        <h3>✅ Datos Guardados en Sesión</h3>
        <p>Los datos de conexión se han guardado correctamente en la sesión. Si el instalador real usara estos datos, no debería haber error.</p>
        <pre><?php print_r($_SESSION['installer_db']); ?></pre>
        
        <h4>🔧 Código de Ejemplo para el Instalador Real:</h4>
        <pre style="background: #e8f5e8; border: 1px solid #4caf50; padding: 15px;">
// En la función "Continuar" del instalador real:
$dbData = $_SESSION['installer_db'] ?? null;
if (!$dbData || empty($dbData['host']) || empty($dbData['user'])) {
    throw new Exception("Host y usuario son requeridos. Prueba la conexión primero.");
}

// Usar los datos guardados:
$host = $dbData['host'];
$user = $dbData['user'];
$pass = $dbData['pass'];
$dbname = $dbData['dbname'];
        </pre>
    </div>
    <?php endif; ?>

    <h2>💡 Recomendaciones</h2>
    
    <div class="section">
        <h3>🚀 Pasos Siguientes</h3>
        <ol>
            <li><strong>Localizar el archivo del instalador:</strong>
                <pre>find . -name "*install*" -o -name "*setup*" -o -name "*wizard*"</pre>
            </li>
            <li><strong>Buscar el mensaje de error específico:</strong>
                <pre>grep -r "Host y usuario son requeridos" .</pre>
            </li>
            <li><strong>Verificar la función que funciona vs la que falla</strong></li>
            <li><strong>Implementar persistencia de datos entre funciones</strong></li>
        </ol>
    </div>

    <div class="section warning">
        <h3>⚡ Solución Rápida</h3>
        <p>Si no puedes encontrar el instalador, puedes configurar manualmente:</p>
        <ol>
            <li>Editar <code>config/database.php</code> con tus datos de conexión</li>
            <li>O configurar las variables de entorno en <code>.env</code></li>
            <li>Omitir el instalador y configurar directamente</li>
        </ol>
    </div>

</div>

</body>
</html>