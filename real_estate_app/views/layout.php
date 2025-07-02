<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'InmobiliariaApp' ?> - <?= $siteConfig['name'] ?? 'InmobiliariaApp' ?></title>
    
    <!-- Meta Description -->
    <meta name="description" content="<?= $siteConfig['description'] ?? 'Tu inmobiliaria de confianza' ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/animations.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Additional CSS -->
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <nav class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    <a href="/" class="logo">
                        <img src="<?= $siteConfig['logo'] ?? '/assets/images/logo.png' ?>" alt="<?= $siteConfig['name'] ?? 'InmobiliariaApp' ?>">
                        <span><?= $siteConfig['name'] ?? 'InmobiliariaApp' ?></span>
                    </a>
                </div>
                
                <div class="navbar-menu" id="navbar-menu">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="/" class="nav-link">Inicio</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="/propiedades" class="nav-link">Propiedades</a>
                            <div class="dropdown-menu">
                                <a href="/propiedades?type=venta" class="dropdown-link">En Venta</a>
                                <a href="/propiedades?type=arriendo" class="dropdown-link">En Arriendo</a>
                                <a href="/propiedades?type=venta_arriendo" class="dropdown-link">Venta y Arriendo</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="/vender" class="nav-link">Vender</a>
                        </li>
                        <li class="nav-item">
                            <a href="#contact" class="nav-link">Contacto</a>
                        </li>
                    </ul>
                </div>
                
                <div class="navbar-actions">
                    <div class="search-box">
                        <input type="text" id="search-input" placeholder="Buscar propiedades...">
                        <button class="search-btn" id="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <button class="mobile-toggle" id="mobile-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Contacto</h3>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span><?= $siteConfig['contact_phone'] ?? '+57 300 123 4567' ?></span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span><?= $siteConfig['contact_email'] ?? 'contacto@inmobiliaria.com' ?></span>
                        </div>
                        <div class="contact-item">
                            <i class="fab fa-whatsapp"></i>
                            <a href="https://wa.me/<?= str_replace(['+', ' '], '', $siteConfig['whatsapp_number'] ?? '+573001234567') ?>" target="_blank">
                                WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Enlaces Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="/">Inicio</a></li>
                        <li><a href="/propiedades">Propiedades</a></li>
                        <li><a href="/propiedades?type=venta">En Venta</a></li>
                        <li><a href="/propiedades?type=arriendo">En Arriendo</a></li>
                        <li><a href="/vender">Vender</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Síguenos</h3>
                    <div class="social-links">
                        <?php if (!empty($siteConfig['facebook_url'])): ?>
                            <a href="<?= $siteConfig['facebook_url'] ?>" target="_blank" class="social-link">
                                <i class="fab fa-facebook"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($siteConfig['instagram_url'])): ?>
                            <a href="<?= $siteConfig['instagram_url'] ?>" target="_blank" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= $siteConfig['name'] ?? 'InmobiliariaApp' ?>. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/animations.js"></script>
    
    <!-- Additional JavaScript -->
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Google Maps API (if available) -->
    <?php if (!empty($siteConfig['google_maps_api_key'])): ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?= $siteConfig['google_maps_api_key'] ?>&libraries=places"></script>
    <?php endif; ?>
</body>
</html>