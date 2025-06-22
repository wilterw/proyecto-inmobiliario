<?php
require_once 'config/database.php';
require_once 'classes/Property.php';
require_once 'classes/SiteConfig.php';

$database = new Database();
$db = $database->getConnection();

$property = new Property($db);
$siteConfig = new SiteConfig($db);

$config = $siteConfig->getConfig();
$properties = $property->getAll();

// Obtener filtros
$filters = [];
if (isset($_GET['location'])) $filters['location'] = $_GET['location'];
if (isset($_GET['price_min'])) $filters['price_min'] = $_GET['price_min'];
if (isset($_GET['price_max'])) $filters['price_max'] = $_GET['price_max'];
if (isset($_GET['bedrooms'])) $filters['bedrooms'] = $_GET['bedrooms'];
if (isset($_GET['bathrooms'])) $filters['bathrooms'] = $_GET['bathrooms'];
if (isset($_GET['property_type'])) $filters['property_type'] = $_GET['property_type'];
if (isset($_GET['status'])) $filters['status'] = $_GET['status'];

if (!empty($filters)) {
    $properties = $property->getAll($filters);
}

function formatPrice($price) {
    return '$' . number_format($price, 0, ',', '.');
}

function getStatusColor($status) {
    switch ($status) {
        case 'En Venta': return 'bg-green-500';
        case 'En Arriendo': return 'bg-blue-500';
        case 'Vendido': return 'bg-gray-500';
        case 'Arrendado': return 'bg-yellow-500';
        default: return 'bg-gray-500';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config['site_name']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($config['site_description']); ?>">
    
    <?php if (!empty($config['favicon_url'])): ?>
    <link rel="icon" href="<?php echo htmlspecialchars($config['favicon_url']); ?>">
    <?php endif; ?>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <style>
        :root {
            --color-primary: <?php echo $config['primary_color']; ?>;
            --color-secondary: <?php echo $config['secondary_color']; ?>;
            --color-accent: <?php echo $config['accent_color']; ?>;
            --color-background: <?php echo $config['background_color']; ?>;
            --color-text: <?php echo $config['text_color']; ?>;
        }
        
        .bg-primary { background-color: var(--color-primary); }
        .text-primary { color: var(--color-primary); }
        .border-primary { border-color: var(--color-primary); }
        .bg-secondary { background-color: var(--color-secondary); }
        .text-secondary { color: var(--color-secondary); }
        .bg-accent { background-color: var(--color-accent); }
        .text-accent { color: var(--color-accent); }
        
        .btn-primary {
            background-color: var(--color-primary);
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background-color: var(--color-secondary);
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .property-card {
            transition: all 0.3s ease;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <?php if (!empty($config['logo_url'])): ?>
                    <img src="<?php echo htmlspecialchars($config['logo_url']); ?>" alt="Logo" class="h-10 w-auto">
                    <?php endif; ?>
                    <h1 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($config['site_name']); ?></h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <?php if ($config['sell_button_enabled'] && !empty($config['sell_button_link'])): ?>
                    <a href="<?php echo htmlspecialchars($config['sell_button_link']); ?>" 
                       target="_blank"
                       class="btn-primary px-4 py-2 rounded-lg font-medium">
                        <?php echo htmlspecialchars($config['sell_button_text']); ?>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($config['rent_button_enabled'] && !empty($config['rent_button_link'])): ?>
                    <a href="<?php echo htmlspecialchars($config['rent_button_link']); ?>" 
                       target="_blank"
                       class="btn-secondary px-4 py-2 rounded-lg font-medium">
                        <?php echo htmlspecialchars($config['rent_button_text']); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-5xl font-bold mb-6"><?php echo htmlspecialchars($config['hero_title']); ?></h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto"><?php echo htmlspecialchars($config['hero_subtitle']); ?></p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar de Filtros -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Filtros de Búsqueda</h3>
                    
                    <form method="GET" class="space-y-4">
                        <!-- Pestañas de Estado -->
                        <div class="mb-6">
                            <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                                <button type="submit" name="status" value="" 
                                        class="flex-1 py-2 px-3 rounded-md text-sm font-medium <?php echo empty($_GET['status']) ? 'btn-primary' : 'bg-transparent text-gray-700 hover:bg-gray-200'; ?>">
                                    Todos
                                </button>
                                <button type="submit" name="status" value="En Venta" 
                                        class="flex-1 py-2 px-3 rounded-md text-sm font-medium <?php echo ($_GET['status'] ?? '') === 'En Venta' ? 'btn-primary' : 'bg-transparent text-gray-700 hover:bg-gray-200'; ?>">
                                    Venta
                                </button>
                                <button type="submit" name="status" value="En Arriendo" 
                                        class="flex-1 py-2 px-3 rounded-md text-sm font-medium <?php echo ($_GET['status'] ?? '') === 'En Arriendo' ? 'btn-primary' : 'bg-transparent text-gray-700 hover:bg-gray-200'; ?>">
                                    Arriendo
                                </button>
                            </div>
                        </div>

                        <!-- Tipo de Propiedad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Propiedad</label>
                            <select name="property_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todos los tipos</option>
                                <option value="Casa" <?php echo ($_GET['property_type'] ?? '') === 'Casa' ? 'selected' : ''; ?>>Casa</option>
                                <option value="Apartamento" <?php echo ($_GET['property_type'] ?? '') === 'Apartamento' ? 'selected' : ''; ?>>Apartamento</option>
                                <option value="Oficina" <?php echo ($_GET['property_type'] ?? '') === 'Oficina' ? 'selected' : ''; ?>>Oficina</option>
                                <option value="Local" <?php echo ($_GET['property_type'] ?? '') === 'Local' ? 'selected' : ''; ?>>Local</option>
                                <option value="Terreno" <?php echo ($_GET['property_type'] ?? '') === 'Terreno' ? 'selected' : ''; ?>>Terreno</option>
                                <option value="Finca" <?php echo ($_GET['property_type'] ?? '') === 'Finca' ? 'selected' : ''; ?>>Finca</option>
                            </select>
                        </div>

                        <!-- Ubicación -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
                            <input type="text" name="location" value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>" 
                                   placeholder="Ciudad, Barrio o Dirección" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Rango de Precios -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rango de Precios</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" name="price_min" value="<?php echo htmlspecialchars($_GET['price_min'] ?? ''); ?>" 
                                       placeholder="Mínimo" 
                                       class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <input type="number" name="price_max" value="<?php echo htmlspecialchars($_GET['price_max'] ?? ''); ?>" 
                                       placeholder="Máximo" 
                                       class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Características -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-4">Características</h4>
                            
                            <div class="mb-4">
                                <label class="block text-sm text-gray-600 mb-2">Dormitorios</label>
                                <select name="bedrooms" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Cualquiera</option>
                                    <option value="1" <?php echo ($_GET['bedrooms'] ?? '') === '1' ? 'selected' : ''; ?>>1+</option>
                                    <option value="2" <?php echo ($_GET['bedrooms'] ?? '') === '2' ? 'selected' : ''; ?>>2+</option>
                                    <option value="3" <?php echo ($_GET['bedrooms'] ?? '') === '3' ? 'selected' : ''; ?>>3+</option>
                                    <option value="4" <?php echo ($_GET['bedrooms'] ?? '') === '4' ? 'selected' : ''; ?>>4+</option>
                                    <option value="5" <?php echo ($_GET['bedrooms'] ?? '') === '5' ? 'selected' : ''; ?>>5+</option>
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm text-gray-600 mb-2">Baños</label>
                                <select name="bathrooms" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Cualquiera</option>
                                    <option value="1" <?php echo ($_GET['bathrooms'] ?? '') === '1' ? 'selected' : ''; ?>>1+</option>
                                    <option value="2" <?php echo ($_GET['bathrooms'] ?? '') === '2' ? 'selected' : ''; ?>>2+</option>
                                    <option value="3" <?php echo ($_GET['bathrooms'] ?? '') === '3' ? 'selected' : ''; ?>>3+</option>
                                    <option value="4" <?php echo ($_GET['bathrooms'] ?? '') === '4' ? 'selected' : ''; ?>>4+</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary w-full py-3 rounded-lg font-bold">
                            Aplicar Filtros
                        </button>
                    </form>
                </div>
            </div>

            <!-- Lista de Propiedades -->
            <div class="lg:col-span-3">
                <?php if (empty($properties)): ?>
                <div class="text-center py-12">
                    <div class="text-gray-500 text-lg">No se encontraron propiedades que coincidan con tus filtros.</div>
                    <p class="text-gray-400 mt-2">Intenta ajustar los criterios de búsqueda.</p>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php foreach ($properties as $prop): ?>
                    <div class="property-card bg-white rounded-xl shadow-lg overflow-hidden cursor-pointer" 
                         onclick="openPropertyModal(<?php echo htmlspecialchars(json_encode($prop)); ?>)">
                        <!-- Imagen -->
                        <div class="relative aspect-video">
                            <?php 
                            $images = json_decode($prop['images'], true) ?: [];
                            $firstImage = !empty($images) ? $images[0] : 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800';
                            ?>
                            <img src="<?php echo htmlspecialchars($firstImage); ?>" 
                                 alt="<?php echo htmlspecialchars($prop['title']); ?>" 
                                 class="object-cover w-full h-full">
                            <div class="absolute top-4 right-4 <?php echo getStatusColor($prop['status']); ?> text-white px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo htmlspecialchars($prop['status']); ?>
                            </div>
                            <?php if ($prop['is_featured']): ?>
                            <div class="absolute top-4 left-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                Destacada
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Contenido -->
                        <div class="p-6">
                            <h4 class="font-bold text-lg text-gray-900 mb-2"><?php echo htmlspecialchars($prop['title']); ?></h4>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <i data-lucide="map-pin" class="h-4 w-4 mr-1"></i>
                                <span><?php echo htmlspecialchars($prop['address'] . ', ' . $prop['city']); ?></span>
                            </div>

                            <p class="text-2xl font-bold text-primary mb-4">
                                <?php echo formatPrice($prop['price']); ?>
                                <?php if ($prop['status'] === 'En Arriendo'): ?>
                                <span class="text-sm text-gray-500">/mes</span>
                                <?php endif; ?>
                            </p>

                            <div class="flex justify-between items-center mb-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i data-lucide="bed-double" class="h-4 w-4 mr-1"></i>
                                        <span><?php echo $prop['bedrooms']; ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i data-lucide="bath" class="h-4 w-4 mr-1"></i>
                                        <span><?php echo $prop['bathrooms']; ?></span>
                                    </div>
                                    <div class="flex items-center">
                                        <i data-lucide="square" class="h-4 w-4 mr-1"></i>
                                        <span><?php echo $prop['square_feet']; ?> m²</span>
                                    </div>
                                </div>
                            </div>

                            <button class="w-full border-2 border-primary text-primary font-medium py-2 rounded-lg transition-colors hover:bg-primary hover:text-white">
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($config['site_name']); ?></h3>
                    <p class="text-gray-300 mb-4"><?php echo htmlspecialchars($config['footer_text']); ?></p>
                    
                    <?php if (!empty($config['address'])): ?>
                    <div class="flex items-start mb-2">
                        <i data-lucide="map-pin" class="h-5 w-5 mr-2 mt-0.5 text-gray-400"></i>
                        <span class="text-gray-300"><?php echo htmlspecialchars($config['address']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($config['contact_phone'])): ?>
                    <div class="flex items-center mb-2">
                        <i data-lucide="phone" class="h-5 w-5 mr-2 text-gray-400"></i>
                        <span class="text-gray-300"><?php echo htmlspecialchars($config['contact_phone']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($config['contact_email'])): ?>
                    <div class="flex items-center mb-4">
                        <i data-lucide="mail" class="h-5 w-5 mr-2 text-gray-400"></i>
                        <span class="text-gray-300"><?php echo htmlspecialchars($config['contact_email']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Propiedades</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Nosotros</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Contacto</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Síguenos</h4>
                    <div class="flex space-x-4">
                        <?php if (!empty($config['facebook_url'])): ?>
                        <a href="<?php echo htmlspecialchars($config['facebook_url']); ?>" target="_blank" class="text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="facebook" class="h-6 w-6"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($config['instagram_url'])): ?>
                        <a href="<?php echo htmlspecialchars($config['instagram_url']); ?>" target="_blank" class="text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="instagram" class="h-6 w-6"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($config['youtube_url'])): ?>
                        <a href="<?php echo htmlspecialchars($config['youtube_url']); ?>" target="_blank" class="text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="youtube" class="h-6 w-6"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($config['whatsapp_number'])): ?>
                        <a href="https://wa.me/<?php echo htmlspecialchars($config['whatsapp_number']); ?>" target="_blank" class="text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="message-circle" class="h-6 w-6"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    © <?php echo date('Y'); ?> <?php echo htmlspecialchars($config['site_name']); ?>. Todos los derechos reservados.
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    Sistema Desarrollado por el <strong>Ing. Wilter Amaro</strong> - <strong>Social Marketing Latino</strong>
                </p>
            </div>
        </div>
    </footer>

    <!-- Modal de Detalles de Propiedad -->
    <div id="propertyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Header del Modal -->
            <div class="flex justify-between items-center p-6 border-b sticky top-0 bg-white z-10">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900"></h2>
                <button onclick="closePropertyModal()" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>

            <!-- Contenido del Modal -->
            <div id="modalContent" class="p-6">
                <!-- El contenido se carga dinámicamente -->
            </div>
        </div>
    </div>

    <script>
        // Inicializar iconos de Lucide
        lucide.createIcons();

        let currentProperty = null;

        function openPropertyModal(property) {
            currentProperty = property;
            const modal = document.getElementById('propertyModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');

            modalTitle.textContent = property.title;

            // Parsear datos JSON
            const images = JSON.parse(property.images || '[]');
            const features = JSON.parse(property.features || '[]');

            modalContent.innerHTML = `
                <!-- Carrusel de Imágenes -->
                <div class="mb-8">
                    <div class="relative w-full h-96 rounded-lg overflow-hidden bg-gray-100">
                        <img id="modalMainImage" src="${images[0] || 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800'}" 
                             alt="${property.title}" class="w-full h-full object-cover">
                        
                        ${images.length > 1 ? `
                        <button onclick="previousImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-all">
                            <i data-lucide="chevron-left" class="h-6 w-6"></i>
                        </button>
                        <button onclick="nextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-all">
                            <i data-lucide="chevron-right" class="h-6 w-6"></i>
                        </button>
                        ` : ''}
                    </div>
                    
                    ${images.length > 1 ? `
                    <div class="mt-4 flex space-x-2 overflow-x-auto pb-2">
                        ${images.map((img, index) => `
                            <button onclick="setMainImage('${img}', ${index})" class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 border-gray-200 hover:border-gray-300 transition-all">
                                <img src="${img}" alt="Imagen ${index + 1}" class="w-full h-full object-cover">
                            </button>
                        `).join('')}
                    </div>
                    ` : ''}
                </div>

                <!-- Información de la Propiedad -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Información Principal -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Ubicación y Precio -->
                        <div>
                            <div class="flex items-center text-gray-600 mb-4">
                                <i data-lucide="map-pin" class="h-5 w-5 mr-2"></i>
                                <span class="text-lg">${property.address}, ${property.city}, ${property.state}</span>
                            </div>

                            <div class="flex items-center justify-between mb-6">
                                <div class="text-4xl font-bold text-primary">
                                    ${formatPrice(property.price)}
                                    ${property.status === 'En Arriendo' ? '<span class="text-xl text-gray-500">/mes</span>' : ''}
                                </div>
                                <div class="text-right">
                                    <span class="px-4 py-2 rounded-full text-sm font-medium ${getStatusColorClass(property.status)} text-white">
                                        ${property.status}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas de la Propiedad -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i data-lucide="bed-double" class="h-8 w-8 mx-auto mb-2 text-primary"></i>
                                <div class="font-semibold text-lg">${property.bedrooms}</div>
                                <div class="text-sm text-gray-600">Dormitorios</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i data-lucide="bath" class="h-8 w-8 mx-auto mb-2 text-primary"></i>
                                <div class="font-semibold text-lg">${property.bathrooms}</div>
                                <div class="text-sm text-gray-600">Baños</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i data-lucide="square" class="h-8 w-8 mx-auto mb-2 text-primary"></i>
                                <div class="font-semibold text-lg">${property.square_feet}</div>
                                <div class="text-sm text-gray-600">m²</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <i data-lucide="car" class="h-8 w-8 mx-auto mb-2 text-primary"></i>
                                <div class="font-semibold text-lg">${property.parking}</div>
                                <div class="text-sm text-gray-600">Estacionamientos</div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        ${property.description ? `
                        <div>
                            <h3 class="text-xl font-semibold mb-3">Descripción</h3>
                            <p class="text-gray-600 leading-relaxed">${property.description}</p>
                        </div>
                        ` : ''}

                        <!-- Características -->
                        ${features.length > 0 ? `
                        <div>
                            <h3 class="text-xl font-semibold mb-3">Características</h3>
                            <div class="grid grid-cols-2 gap-2">
                                ${features.map(feature => `
                                    <div class="flex items-center text-gray-600">
                                        <div class="w-2 h-2 rounded-full bg-primary mr-3"></div>
                                        ${feature}
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        ` : ''}

                        <!-- Mapa (si hay coordenadas) -->
                        ${property.latitude && property.longitude ? `
                        <div>
                            <h3 class="text-xl font-semibold mb-3">Ubicación</h3>
                            <div class="h-64 w-full bg-gray-200 rounded-lg flex items-center justify-center">
                                <div class="text-center text-gray-500">
                                    <i data-lucide="map-pin" class="h-8 w-8 mx-auto mb-2"></i>
                                    <p class="text-sm">Mapa disponible</p>
                                    <p class="text-xs">Lat: ${property.latitude}, Lng: ${property.longitude}</p>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>

                    <!-- Sidebar de Contacto -->
                    <div class="space-y-6">
                        <!-- Información de Contacto -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Información de Contacto</h3>

                            ${property.contact_name ? `
                            <div class="mb-3">
                                <div class="font-medium text-gray-900">${property.contact_name}</div>
                                <div class="text-sm text-gray-600">Agente Inmobiliario</div>
                            </div>
                            ` : ''}

                            <div class="space-y-3 mb-6">
                                ${property.contact_phone ? `
                                <div class="flex items-center text-gray-600">
                                    <i data-lucide="phone" class="h-4 w-4 mr-2"></i>
                                    <span class="text-sm">${property.contact_phone}</span>
                                </div>
                                ` : ''}
                                
                                ${property.contact_email ? `
                                <div class="flex items-center text-gray-600">
                                    <i data-lucide="mail" class="h-4 w-4 mr-2"></i>
                                    <span class="text-sm">${property.contact_email}</span>
                                </div>
                                ` : ''}
                            </div>

                            ${property.contact_link ? `
                            <button onclick="window.open('${property.contact_link}', '_blank')" 
                                    class="w-full btn-primary font-bold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                                <i data-lucide="external-link" class="h-4 w-4 mr-2"></i>
                                Contactar Ahora
                            </button>
                            ` : ''}
                        </div>

                        <!-- Detalles de la Propiedad -->
                        <div class="bg-blue-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-3 text-blue-900">Detalles de la Propiedad</h3>
                            <div class="space-y-2 text-blue-800 text-sm">
                                <div class="flex justify-between">
                                    <span>Tipo:</span>
                                    <span class="font-medium">${property.property_type}</span>
                                </div>
                                ${property.year_built ? `
                                <div class="flex justify-between">
                                    <span>Año de construcción:</span>
                                    <span class="font-medium">${property.year_built}</span>
                                </div>
                                ` : ''}
                                <div class="flex justify-between">
                                    <span>Estado:</span>
                                    <span class="font-medium">${property.status}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de Consulta Rápida -->
                        <div class="bg-white border rounded-lg p-6">
                            <h3 class="text-lg font-semibold mb-4">Consulta Rápida</h3>
                            <form onsubmit="sendInquiry(event)" class="space-y-3">
                                <input type="hidden" name="property_id" value="${property.id}">
                                <input type="text" name="name" placeholder="Tu nombre" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <input type="email" name="email" placeholder="Tu email" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <input type="tel" name="phone" placeholder="Tu teléfono"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <textarea name="message" placeholder="Tu mensaje..." rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"></textarea>
                                <button type="submit" class="w-full btn-secondary font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                                    Enviar Consulta
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            // Reinicializar iconos de Lucide
            lucide.createIcons();
        }

        function closePropertyModal() {
            const modal = document.getElementById('propertyModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            currentProperty = null;
            currentImageIndex = 0;
        }

        let currentImageIndex = 0;

        function setMainImage(imageSrc, index) {
            document.getElementById('modalMainImage').src = imageSrc;
            currentImageIndex = index;
        }

        function previousImage() {
            if (!currentProperty) return;
            const images = JSON.parse(currentProperty.images || '[]');
            if (images.length <= 1) return;
            
            currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
            setMainImage(images[currentImageIndex], currentImageIndex);
        }

        function nextImage() {
            if (!currentProperty) return;
            const images = JSON.parse(currentProperty.images || '[]');
            if (images.length <= 1) return;
            
            currentImageIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
            setMainImage(images[currentImageIndex], currentImageIndex);
        }

        function formatPrice(price) {
            return '$' + new Intl.NumberFormat('es-CO').format(price);
        }

        function getStatusColorClass(status) {
            switch (status) {
                case 'En Venta': return 'bg-green-500';
                case 'En Arriendo': return 'bg-blue-500';
                case 'Vendido': return 'bg-gray-500';
                case 'Arrendado': return 'bg-yellow-500';
                default: return 'bg-gray-500';
            }
        }

        function sendInquiry(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            
            fetch('/api/inquiries.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('¡Consulta enviada exitosamente! Nos pondremos en contacto contigo pronto.');
                    event.target.reset();
                } else {
                    alert('Error al enviar la consulta. Por favor intenta nuevamente.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al enviar la consulta. Por favor intenta nuevamente.');
            });
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('propertyModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePropertyModal();
            }
        });

        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('propertyModal').classList.contains('hidden')) {
                closePropertyModal();
            }
        });
    </script>
</body>
</html>
