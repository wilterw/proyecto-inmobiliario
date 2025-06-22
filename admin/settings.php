<?php
require_once '../config/database.php';
require_once '../classes/Auth.php';
require_once '../classes/SiteConfig.php';

$database = new Database();
$db = $database->getConnection();

$auth = new Auth($db);
$auth->requireAdmin();

$siteConfig = new SiteConfig($db);
$config = $siteConfig->getConfig();

$success = false;
$error = '';

if ($_POST) {
    try {
        $result = $siteConfig->updateConfig($_POST);
        if ($result) {
            $success = true;
            $config = $siteConfig->getConfig(); // Recargar configuración
        } else {
            $error = 'Error al actualizar la configuración';
        }
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}

$currentUser = $auth->getCurrentUser();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - Panel de Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">Configuración del Sitio</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Bienvenido, <?php echo htmlspecialchars($currentUser['name']); ?></span>
                        <a href="logout.php" class="text-red-600 hover:text-red-700">Cerrar Sesión</a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">
                <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    ✓ Configuración actualizada exitosamente
                </div>
                <?php endif; ?>

                <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <!-- Información General -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Información General</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Sitio</label>
                                <input type="text" name="site_name" value="<?php echo htmlspecialchars($config['site_name']); ?>" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Portal Inmobiliario">
                                <p class="text-sm text-gray-500 mt-1">Este será el título que aparece en el navegador</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">URL del Logo</label>
                                <input type="url" name="logo_url" value="<?php echo htmlspecialchars($config['logo_url']); ?>" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="https://ejemplo.com/logo.png">
                                <p class="text-sm text-gray-500 mt-1">URL del logo que aparece en el header</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción del Sitio</label>
                            <textarea name="site_description" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Encuentra tu próximo hogar..."><?php echo htmlspecialchars($config['site_description']); ?></textarea>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">URL del Favicon</label>
                            <input type="url" name="favicon_url" value="<?php echo htmlspecialchars($config['favicon_url']); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="https://ejemplo.com/favicon.ico">
                            <p class="text-sm text-gray-500 mt-1">URL del icono que aparece en la pestaña del navegador (16x16 o 32x32 píxeles)</p>
                        </div>
                    </div>

                    <!-- Textos Personalizables -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Textos del Sitio</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Título Principal (Hero)</label>
                                <input type="text" name="hero_title" value="<?php echo htmlspecialchars($config['hero_title']); ?>" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Encuentra tu hogar ideal">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Texto del Footer</label>
                                <input type="text" name="footer_text" value="<?php echo htmlspecialchars($config['footer_text']); ?>" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Tu hogar perfecto te está esperando">
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtítulo Principal (Hero)</label>
                            <textarea name="hero_subtitle" rows="2" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Descubre las mejores propiedades en las ubicaciones más exclusivas"><?php echo htmlspecialchars($config['hero_subtitle']); ?></textarea>
                        </div>
                    </div>

                    <!-- Colores y Tema -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Colores y Tema</h2>
                        
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color Primario</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" name="primary_color" value="<?php echo htmlspecialchars($config['primary_color']); ?>" 
                                           class="w-12 h-12 border border-gray-300 rounded-lg cursor-pointer">
                                    <input type="text" value="<?php echo htmlspecialchars($config['primary_color']); ?>" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color Secundario</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" name="secondary_color" value="<?php echo htmlspecialchars($config['secondary_color']); ?>" 
                                           class="w-12 h-12 border border-gray-300 rounded-lg cursor-pointer">
                                    <input type="text" value="<?php echo htmlspecialchars($config['secondary_color']); ?>" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color de Acento</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" name="accent_color" value="<?php echo htmlspecialchars($config['accent_color']); ?>" 
                                           class="w-12 h-12 border border-gray-300 rounded-lg cursor-pointer">
                                    <input type="text" value="<?php echo htmlspecialchars($config['accent_color']); ?>" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color de Fondo</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" name="background_color" value="<?php echo htmlspecialchars($config['background_color']); ?>" 
                                           class="w-12 h-12 border border-gray-300 rounded-lg cursor-pointer">
                                    <input type="text" value="<?php echo htmlspecialchars($config['background_color']); ?>" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color de Texto</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" name="text_color" value="<?php echo htmlspecialchars($config['text_color']); ?>" 
                                           class="w-12 h-12 border border-gray-300 rounded-lg cursor-pointer">
                                    <input type="text" value="<?php echo htmlspecialchars($config['text_color']); ?>" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Botones de Acción</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Botón de Venta -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Botón de Venta</h3>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="sell_button_enabled" value="1" 
                                               <?php echo $config['sell_button_enabled'] ? 'checked' : ''; ?>
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">Habilitado</span>
                                    </label>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Texto del Botón</label>
                                        <input type="text" name="sell_button_text" value="<?php echo htmlspecialchars($config['sell_button_text']); ?>" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="¿Quieres Vender tu Propiedad?">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Enlace del Botón</label>
                                        <input type="url" name="sell_button_link" value="<?php echo htmlspecialchars($config['sell_button_link']); ?>" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="https://wa.me/573001234567?text=Quiero%20vender%20mi%20propiedad">
                                        <p class="text-sm text-gray-500 mt-1">Puede ser WhatsApp, formulario, email, etc.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Arriendo -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">Botón de Arriendo</h3>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="rent_button_enabled" value="1" 
                                               <?php echo $config['rent_button_enabled'] ? 'checked' : ''; ?>
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-600">Habilitado</span>
                                    </label>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Texto del Botón</label>
                                        <input type="text" name="rent_button_text" value="<?php echo htmlspecialchars($config['rent_button_text']); ?>" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="¿Quieres Arrendar tu Propiedad?">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Enlace del Botón</label>
                                        <input type="url" name="rent_button_link" value="<?php echo htmlspecialchars($config['rent_button_link']); ?>" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="https://wa.me/573001234567?text=Quiero%20arrendar%20mi%20propiedad">
                                        <p class="text-sm text-gray-500 mt-1">Puede ser WhatsApp, formulario, email, etc.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Información de Contacto</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email de Contacto</label>
                                <input type="email" name="contact_email" value="<?php echo htmlspecialchars($config['contact_email']); ?>" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="contacto@inmobiliaria.com">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                <input type="tel" name="contact_phone" value="<?php echo htmlspecialchars($config['contact_phone']); ?>" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="+57 300 123 4567">
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                            <textarea name="address" rows="2" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Calle 123 #45-67, Bogotá, Colombia"><?php echo htmlspecialchars($config['address']); ?></textarea>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Número de WhatsApp</label>
                            <input type="tel" name="whatsapp_number" value="<?php echo htmlspecialchars($config['whatsapp_number']); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="573001234567">
                            <p class="text-sm text-gray-500 mt-1">Formato: código de país + número (sin + ni espacios)</p>
                        </div>
                    </div>

                    <!-- Redes Sociales -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Redes Sociales</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                                <input type="url" name="facebook_url" value="<?php echo htmlspecialchars($config['facebook_url']); ?>" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="https://facebook.com/tu-pagina">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                                <input type="url" name="instagram_url" value="<?php echo htmlspecialchars($config['instagram_url']); ?>" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="https://instagram.com/tu-cuenta">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">YouTube</label>
                                <input type="url" name="youtube_url" value="<?php echo htmlspecialchars($config['youtube_url']); ?>" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="https://youtube.com/tu-canal">
                            </div>
                        </div>
                    </div>

                    <!-- Google Maps -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Configuración de Google Maps</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">API Key de Google Maps</label>
                            <input type="password" name="google_maps_key" value="<?php echo htmlspecialchars($config['google_maps_key']); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="AIzaSyC4R6AN7SmxjPUIGKdyBLT7CovTuIgYUnE">
                            <p class="text-sm text-gray-500 mt-1">
                                Necesario para mostrar mapas en las propiedades.
                                <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" 
                                   target="_blank" class="text-blue-600 hover:underline">
                                    Obtener API Key
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex justify-end space-x-4">
                        <a href="index.php" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i data-lucide="save" class="h-4 w-4 mr-2 inline"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Sincronizar color picker con input de texto
        document.querySelectorAll('input[type="color"]').forEach(colorInput => {
            const textInput = colorInput.parentElement.querySelector('input[type="text"]');
            
            colorInput.addEventListener('change', function() {
                textInput.value = this.value;
                colorInput.name = colorInput.name || textInput.name;
            });
        });
    </script>
</body>
</html>
