<?php
$content = ob_start();
?>

<!-- Hero Section -->
<section class="hero" id="hero">
    <div class="hero-background">
        <div class="hero-overlay"></div>
        <video autoplay muted loop class="hero-video">
            <source src="/assets/videos/hero-bg.mp4" type="video/mp4">
        </video>
    </div>
    
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title animate-fade-up">
                Encuentra la casa de tus sueños
            </h1>
            <p class="hero-subtitle animate-fade-up delay-1">
                Explora una amplia gama de propiedades en venta y alquiler en la ubicación que deseas
            </p>
            
            <!-- Search Form -->
            <div class="hero-search animate-fade-up delay-2">
                <form class="search-form" id="hero-search-form">
                    <div class="search-tabs">
                        <button type="button" class="search-tab active" data-type="venta">Comprar</button>
                        <button type="button" class="search-tab" data-type="arriendo">Alquilar</button>
                        <button type="button" class="search-tab" data-type="venta_arriendo">Ambos</button>
                    </div>
                    
                    <div class="search-fields">
                        <div class="search-field">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" name="location" placeholder="Ubicación">
                        </div>
                        <div class="search-field">
                            <i class="fas fa-home"></i>
                            <select name="property_type">
                                <option value="">Tipo de propiedad</option>
                                <option value="casa">Casa</option>
                                <option value="apartamento">Apartamento</option>
                                <option value="local-comercial">Local Comercial</option>
                                <option value="oficina">Oficina</option>
                            </select>
                        </div>
                        <div class="search-field">
                            <i class="fas fa-dollar-sign"></i>
                            <select name="price_range">
                                <option value="">Rango de precio</option>
                                <option value="0-200000000">Hasta $200M</option>
                                <option value="200000000-500000000">$200M - $500M</option>
                                <option value="500000000-1000000000">$500M - $1B</option>
                                <option value="1000000000-">Más de $1B</option>
                            </select>
                        </div>
                        <button type="submit" class="search-submit">
                            <i class="fas fa-search"></i>
                            Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="hero-scroll">
        <a href="#featured" class="scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card animate-count-up">
                <div class="stat-icon">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="<?= $stats['total'] ?>"><?= $stats['total'] ?></h3>
                    <p class="stat-label">Propiedades Disponibles</p>
                </div>
            </div>
            
            <div class="stat-card animate-count-up">
                <div class="stat-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="<?= $stats['for_sale'] ?>"><?= $stats['for_sale'] ?></h3>
                    <p class="stat-label">En Venta</p>
                </div>
            </div>
            
            <div class="stat-card animate-count-up">
                <div class="stat-icon">
                    <i class="fas fa-key"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="<?= $stats['for_rent'] ?>"><?= $stats['for_rent'] ?></h3>
                    <p class="stat-label">En Arriendo</p>
                </div>
            </div>
            
            <div class="stat-card animate-count-up">
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-count="<?= $stats['cities'] ?>"><?= $stats['cities'] ?></h3>
                    <p class="stat-label">Ciudades</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Properties -->
<section class="featured-section" id="featured">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Propiedades Destacadas</h2>
            <p class="section-subtitle">Descubre nuestras mejores propiedades seleccionadas especialmente para ti</p>
        </div>
        
        <div class="properties-grid" id="featured-properties">
            <?php foreach ($featuredProperties as $property): ?>
                <div class="property-card featured-card" data-aos="fade-up">
                    <div class="property-image">
                        <img src="<?= $property['main_image'] ?? '/assets/images/default-property.jpg' ?>" 
                             alt="<?= htmlspecialchars($property['title']) ?>"
                             loading="lazy">
                        <div class="property-badge featured">Destacada</div>
                        <div class="property-type <?= $property['property_type'] ?>"><?= ucfirst($property['property_type']) ?></div>
                        <div class="property-overlay">
                            <a href="/propiedad/<?= $property['id'] ?>" class="view-details">Ver Detalles</a>
                        </div>
                    </div>
                    
                    <div class="property-content">
                        <h3 class="property-title">
                            <a href="/propiedad/<?= $property['id'] ?>"><?= htmlspecialchars($property['title']) ?></a>
                        </h3>
                        <p class="property-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?= htmlspecialchars($property['city']) ?>, <?= htmlspecialchars($property['neighborhood'] ?? '') ?>
                        </p>
                        <div class="property-features">
                            <?php if ($property['bedrooms'] > 0): ?>
                                <span class="feature">
                                    <i class="fas fa-bed"></i>
                                    <?= $property['bedrooms'] ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($property['bathrooms'] > 0): ?>
                                <span class="feature">
                                    <i class="fas fa-bath"></i>
                                    <?= $property['bathrooms'] ?>
                                </span>
                            <?php endif; ?>
                            <span class="feature">
                                <i class="fas fa-ruler-combined"></i>
                                <?= number_format($property['area'], 0) ?> m²
                            </span>
                        </div>
                        <div class="property-price">
                            <?= $siteConfig['currency_symbol'] ?><?= number_format($property['price'], 0) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="section-footer">
            <a href="/propiedades" class="btn btn-primary">Ver Todas las Propiedades</a>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Nuestros Servicios</h2>
            <p class="section-subtitle">Te ayudamos en cada paso del proceso inmobiliario</p>
        </div>
        
        <div class="services-grid">
            <div class="service-card" data-aos="flip-left">
                <div class="service-icon">
                    <i class="fas fa-home"></i>
                </div>
                <h3 class="service-title">Compra</h3>
                <p class="service-description">
                    Encuentra la propiedad perfecta con nuestra amplia selección y asesoría experta.
                </p>
            </div>
            
            <div class="service-card" data-aos="flip-left" data-aos-delay="100">
                <div class="service-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h3 class="service-title">Arriendo</h3>
                <p class="service-description">
                    Propiedades en alquiler verificadas con contratos seguros y soporte completo.
                </p>
            </div>
            
            <div class="service-card" data-aos="flip-left" data-aos-delay="200">
                <div class="service-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3 class="service-title">Venta</h3>
                <p class="service-description">
                    Vendemos tu propiedad al mejor precio con marketing profesional y negociación experta.
                </p>
            </div>
            
            <div class="service-card" data-aos="flip-left" data-aos-delay="300">
                <div class="service-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="service-title">Valoración</h3>
                <p class="service-description">
                    Evaluamos tu propiedad con análisis de mercado para determinar el precio justo.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Recent Properties -->
<section class="recent-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Propiedades Recientes</h2>
            <p class="section-subtitle">Las últimas propiedades agregadas a nuestro catálogo</p>
        </div>
        
        <div class="properties-slider" id="recent-properties-slider">
            <div class="slider-container">
                <?php foreach ($recentProperties as $property): ?>
                    <div class="property-slide">
                        <div class="property-card">
                            <div class="property-image">
                                <img src="<?= $property['main_image'] ?? '/assets/images/default-property.jpg' ?>" 
                                     alt="<?= htmlspecialchars($property['title']) ?>"
                                     loading="lazy">
                                <div class="property-type <?= $property['property_type'] ?>"><?= ucfirst($property['property_type']) ?></div>
                                <div class="property-overlay">
                                    <a href="/propiedad/<?= $property['id'] ?>" class="view-details">Ver Detalles</a>
                                </div>
                            </div>
                            
                            <div class="property-content">
                                <h3 class="property-title">
                                    <a href="/propiedad/<?= $property['id'] ?>"><?= htmlspecialchars($property['title']) ?></a>
                                </h3>
                                <p class="property-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($property['city']) ?>, <?= htmlspecialchars($property['neighborhood'] ?? '') ?>
                                </p>
                                <div class="property-features">
                                    <?php if ($property['bedrooms'] > 0): ?>
                                        <span class="feature">
                                            <i class="fas fa-bed"></i>
                                            <?= $property['bedrooms'] ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($property['bathrooms'] > 0): ?>
                                        <span class="feature">
                                            <i class="fas fa-bath"></i>
                                            <?= $property['bathrooms'] ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="feature">
                                        <i class="fas fa-ruler-combined"></i>
                                        <?= number_format($property['area'], 0) ?> m²
                                    </span>
                                </div>
                                <div class="property-price">
                                    <?= $siteConfig['currency_symbol'] ?><?= number_format($property['price'], 0) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="slider-controls">
                <button class="slider-btn prev" id="slider-prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-btn next" id="slider-next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-title">¿Quieres vender tu propiedad?</h2>
            <p class="cta-subtitle">Obtén una valoración gratuita y vende al mejor precio</p>
            <a href="/vender" class="btn btn-white btn-lg">Comenzar Ahora</a>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
$additionalJS = ['/assets/js/home.js'];
$additionalCSS = ['/assets/css/home.css'];

include __DIR__ . '/../layout.php';
?>