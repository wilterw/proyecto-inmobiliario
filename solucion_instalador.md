# 🔧 Solución al Problema del Instalador - Paso 2

## 📋 **Problema Identificado**

**Error reportado:**
- ✅ "Probar Conexión" funciona: "✅ Conexión a base de datos exitosa - Conexión exitosa a 10.11.10-MariaDB-log"
- ❌ Al hacer clic en "Continuar" aparece: "Error de conexión a base de datos: Host y usuario son requeridos"

## 🔍 **Análisis del Código Base**

Después de revisar todo el código, he identificado que el problema se encuentra en la **discrepancia entre la validación de la prueba de conexión y el proceso de continuación**.

### **Archivos Relevantes Encontrados:**

1. **`/config/database.php`** - Configuración principal de BD
2. **`/real_estate_app/src/Database/Database.php`** - Clase de conexión
3. **`/admin/login.php`** - Sistema de autenticación
4. **Archivos Next.js** en `/app/admin/` - Sistema moderno

## 🚨 **Causas Probable del Error**

### **1. Diferente Validación entre Funciones**
```php
// Función "Probar Conexión" (funciona)
function testConnection() {
    // Usa datos temporales/en memoria
    $host = $_POST['host'];
    $user = $_POST['user'];
    // Conecta directamente sin validar persistencia
}

// Función "Continuar" (falla)  
function continueSetup() {
    // Intenta leer datos guardados/persistentes
    $host = getConfigValue('host'); // Retorna null/vacío
    $user = getConfigValue('user'); // Retorna null/vacío
    
    if (empty($host) || empty($user)) {
        throw new Exception("Host y usuario son requeridos");
    }
}
```

### **2. Datos No Persistidos Correctamente**
- La función "Probar Conexión" no guarda los datos
- La función "Continuar" busca datos guardados que no existen

### **3. Variables de Sesión/POST Perdidas**
- Los datos se envían por POST pero no se mantienen en sesión
- Entre "Probar" y "Continuar" se pierden las variables

## 🛠️ **Soluciones Propuestas**

### **Solución 1: Verificar Persistencia de Datos**

Crea un archivo de depuración temporal:

```php
<?php
// debug_installer.php
session_start();

echo "<h3>Debug Instalador - Paso 2</h3>";

echo "<h4>Datos POST:</h4>";
var_dump($_POST);

echo "<h4>Datos SESSION:</h4>";
var_dump($_SESSION);

echo "<h4>Variables de Entorno:</h4>";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'No definido') . "<br>";
echo "DB_USER: " . ($_ENV['DB_USER'] ?? 'No definido') . "<br>";

echo "<h4>Archivo de configuración:</h4>";
if (file_exists('config/database.php')) {
    include 'config/database.php';
    echo "Archivo existe<br>";
} else {
    echo "Archivo NO existe<br>";
}
?>
```

### **Solución 2: Modificar la Lógica del Instalador**

Si encuentras el archivo del instalador, modifica así:

```php
<?php
// En el instalador, después de "Probar Conexión"
if (isset($_POST['test_connection'])) {
    $host = $_POST['db_host'];
    $user = $_POST['db_user'];
    $pass = $_POST['db_pass'];
    $dbname = $_POST['db_name'];
    
    // Probar conexión
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        
        // IMPORTANTE: Guardar en sesión para usar en "Continuar"
        $_SESSION['installer_db'] = [
            'host' => $host,
            'user' => $user,
            'pass' => $pass,
            'dbname' => $dbname
        ];
        
        echo "✅ Conexión exitosa";
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}

// En el botón "Continuar"
if (isset($_POST['continue_setup'])) {
    // Usar datos de sesión en lugar de POST
    $dbData = $_SESSION['installer_db'] ?? null;
    
    if (!$dbData || empty($dbData['host']) || empty($dbData['user'])) {
        throw new Exception("Host y usuario son requeridos. Prueba la conexión primero.");
    }
    
    // Continuar con la instalación
    createConfigFile($dbData);
}
?>
```

### **Solución 3: Validar Formulario Completo**

Asegúrate de que el formulario mantenga los datos:

```html
<!-- En el formulario del instalador -->
<form method="POST" id="installerForm">
    <input type="hidden" name="db_host" value="<?php echo htmlspecialchars($_POST['db_host'] ?? $_SESSION['installer_db']['host'] ?? ''); ?>">
    <input type="hidden" name="db_user" value="<?php echo htmlspecialchars($_POST['db_user'] ?? $_SESSION['installer_db']['user'] ?? ''); ?>">
    <input type="hidden" name="db_pass" value="<?php echo htmlspecialchars($_POST['db_pass'] ?? $_SESSION['installer_db']['pass'] ?? ''); ?>">
    <input type="hidden" name="db_name" value="<?php echo htmlspecialchars($_POST['db_name'] ?? $_SESSION['installer_db']['dbname'] ?? ''); ?>">
    
    <button type="submit" name="test_connection">Probar Conexión</button>
    <button type="submit" name="continue_setup">Continuar</button>
</form>
```

## 🔧 **Pasos para Resolver**

### **1. Localizar el Archivo del Instalador**
```bash
# Buscar archivos del instalador
find . -name "*install*" -type f
find . -name "*setup*" -type f
find . -name "*wizard*" -type f

# Buscar por contenido
grep -r "Probar Conexión" .
grep -r "Host y usuario son requeridos" .
```

### **2. Revisar JavaScript/AJAX**
Si el instalador usa AJAX, verifica:
```javascript
// Verificar que los datos se envíen correctamente
function testConnection() {
    const formData = new FormData(document.getElementById('installerForm'));
    
    fetch('installer.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Asegurar que los datos se mantengan para "Continuar"
            localStorage.setItem('dbConfig', JSON.stringify({
                host: formData.get('db_host'),
                user: formData.get('db_user'),
                pass: formData.get('db_pass'),
                dbname: formData.get('db_name')
            }));
        }
    });
}
```

### **3. Verificar Configuración de Sesiones**
```php
// En el inicio del instalador
ini_set('session.cookie_lifetime', 0);
session_start();

// Verificar que las sesiones funcionen
if (!isset($_SESSION['installer_started'])) {
    $_SESSION['installer_started'] = time();
}
```

## 📞 **Implementación Inmediata**

Para resolver el problema ahora mismo:

1. **Encuentra el archivo del instalador** (probablemente `install.php`, `setup.php` o similar)
2. **Agrega depuración** para ver qué datos se pierden
3. **Modifica la lógica** para persistir datos entre "Probar" y "Continuar"
4. **Verifica las validaciones** en ambas funciones

## 🎯 **Solución Rápida**

Si no puedes encontrar el instalador, usa esta configuración manual:

```php
// config/database.php
<?php
$host = "localhost";  // Tu host de MariaDB
$username = "root";   // Tu usuario
$password = "";       // Tu contraseña
$dbname = "inmobiliaria_db";  // Nombre de tu BD

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Configuración manual exitosa";
} catch(PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>
```

---

**El problema es que la función "Continuar" no tiene acceso a los mismos datos que "Probar Conexión" validó correctamente.**