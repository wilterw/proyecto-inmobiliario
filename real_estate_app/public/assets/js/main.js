// Main JavaScript for InmobiliariaApp
class InmobiliariaApp {
    constructor() {
        this.init();
        this.bindEvents();
        this.initScrollEffects();
        this.initSearch();
    }

    init() {
        // Mobile menu toggle
        this.mobileToggle = document.getElementById('mobile-toggle');
        this.navbarMenu = document.getElementById('navbar-menu');
        
        // Header scroll effect
        this.header = document.getElementById('header');
        
        // Search functionality
        this.searchInput = document.getElementById('search-input');
        this.searchBtn = document.getElementById('search-btn');
        
        // Initialize animations
        this.initAnimations();
    }

    bindEvents() {
        // Mobile menu toggle
        if (this.mobileToggle) {
            this.mobileToggle.addEventListener('click', () => this.toggleMobileMenu());
        }

        // Header scroll effect
        window.addEventListener('scroll', () => this.handleScroll());

        // Search functionality
        if (this.searchBtn) {
            this.searchBtn.addEventListener('click', (e) => this.handleSearch(e));
        }

        if (this.searchInput) {
            this.searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.handleSearch(e);
                }
            });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => this.smoothScroll(e));
        });

        // Property card 3D effects
        this.init3DCards();
    }

    toggleMobileMenu() {
        this.navbarMenu.classList.toggle('active');
        this.mobileToggle.classList.toggle('active');
    }

    handleScroll() {
        const scrollTop = window.pageYOffset;
        
        // Header background opacity
        if (scrollTop > 50) {
            this.header.classList.add('scrolled');
        } else {
            this.header.classList.remove('scrolled');
        }

        // Parallax effect for hero section
        const hero = document.querySelector('.hero');
        if (hero) {
            const heroContent = hero.querySelector('.hero-content');
            const offset = scrollTop * 0.5;
            heroContent.style.transform = `translateY(${offset}px)`;
        }
    }

    handleSearch(e) {
        e.preventDefault();
        const query = this.searchInput.value.trim();
        
        if (query.length < 2) {
            this.showNotification('Por favor ingresa al menos 2 caracteres', 'warning');
            return;
        }

        this.performSearch(query);
    }

    async performSearch(query) {
        try {
            this.showLoader();
            
            const response = await fetch(`/api/properties/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            this.hideLoader();
            
            if (data.success && data.data.length > 0) {
                this.displaySearchResults(data.data);
            } else {
                this.showNotification('No se encontraron propiedades', 'info');
            }
        } catch (error) {
            this.hideLoader();
            this.showNotification('Error al buscar propiedades', 'error');
            console.error('Search error:', error);
        }
    }

    displaySearchResults(properties) {
        // Create and show search results modal
        const modal = this.createSearchModal(properties);
        document.body.appendChild(modal);
        
        // Animate modal appearance
        requestAnimationFrame(() => {
            modal.classList.add('active');
        });
    }

    createSearchModal(properties) {
        const modal = document.createElement('div');
        modal.className = 'search-modal';
        modal.innerHTML = `
            <div class="search-modal-content">
                <div class="search-modal-header">
                    <h3>Resultados de búsqueda</h3>
                    <button class="close-modal" onclick="this.closest('.search-modal').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="search-results">
                    ${properties.map(property => this.createPropertyCard(property)).join('')}
                </div>
            </div>
            <div class="search-modal-overlay" onclick="this.closest('.search-modal').remove()"></div>
        `;
        
        return modal;
    }

    createPropertyCard(property) {
        return `
            <div class="search-result-card">
                <div class="result-image">
                    <img src="${property.main_image || '/assets/images/default-property.jpg'}" 
                         alt="${property.title}" loading="lazy">
                    <div class="property-type ${property.property_type}">
                        ${property.property_type.charAt(0).toUpperCase() + property.property_type.slice(1)}
                    </div>
                </div>
                <div class="result-content">
                    <h4><a href="/propiedad/${property.id}">${property.title}</a></h4>
                    <p class="result-location">
                        <i class="fas fa-map-marker-alt"></i>
                        ${property.city}, ${property.neighborhood || ''}
                    </p>
                    <div class="result-features">
                        ${property.bedrooms > 0 ? `<span><i class="fas fa-bed"></i> ${property.bedrooms}</span>` : ''}
                        ${property.bathrooms > 0 ? `<span><i class="fas fa-bath"></i> ${property.bathrooms}</span>` : ''}
                        <span><i class="fas fa-ruler-combined"></i> ${Number(property.area).toLocaleString()} m²</span>
                    </div>
                    <div class="result-price">$${Number(property.price).toLocaleString()}</div>
                </div>
            </div>
        `;
    }

    smoothScroll(e) {
        const targetId = e.currentTarget.getAttribute('href');
        if (targetId.startsWith('#')) {
            e.preventDefault();
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerHeight = this.header.offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        }
    }

    init3DCards() {
        const cards = document.querySelectorAll('.property-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => this.activate3DCard(card));
            card.addEventListener('mouseleave', () => this.deactivate3DCard(card));
            card.addEventListener('mousemove', (e) => this.handle3DCardMove(card, e));
        });
    }

    activate3DCard(card) {
        card.style.transition = 'transform 0.1s ease-out';
    }

    deactivate3DCard(card) {
        card.style.transition = 'transform 0.5s ease-out';
        card.style.transform = 'rotateX(0deg) rotateY(0deg) translateZ(0px)';
    }

    handle3DCardMove(card, e) {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const rotateX = (y - centerY) / 10;
        const rotateY = (centerX - x) / 10;
        
        card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(20px)`;
    }

    initAnimations() {
        // Fade up animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);

        // Observe elements with animation classes
        document.querySelectorAll('[data-aos], .animate-fade-up, .stat-card').forEach(el => {
            observer.observe(el);
        });

        // Count up animation for stats
        this.initCountUp();
    }

    initCountUp() {
        const counters = document.querySelectorAll('.stat-number[data-count]');
        
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        counters.forEach(counter => observer.observe(counter));
    }

    animateCounter(element) {
        const target = parseInt(element.getAttribute('data-count'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                element.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target;
            }
        };

        updateCounter();
    }

    initScrollEffects() {
        // Parallax effects for background elements
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            
            // Hero video parallax
            const heroVideo = document.querySelector('.hero-video');
            if (heroVideo) {
                heroVideo.style.transform = `translateY(${scrolled * 0.3}px)`;
            }

            // Background shapes parallax
            const shapes = document.querySelectorAll('.bg-shape');
            shapes.forEach((shape, index) => {
                const speed = 0.1 + (index * 0.05);
                shape.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
    }

    showLoader() {
        const loader = document.createElement('div');
        loader.className = 'global-loader';
        loader.innerHTML = `
            <div class="loader-spinner">
                <div class="spinner"></div>
                <p>Buscando propiedades...</p>
            </div>
        `;
        document.body.appendChild(loader);
    }

    hideLoader() {
        const loader = document.querySelector('.global-loader');
        if (loader) {
            loader.remove();
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${this.getNotificationIcon(type)}"></i>
                <span>${message}</span>
                <button onclick="this.closest('.notification').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
}

// Utility functions
const utils = {
    formatPrice: (price) => {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0
        }).format(price);
    },

    formatArea: (area) => {
        return `${Number(area).toLocaleString()} m²`;
    },

    debounce: (func, delay) => {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(null, args), delay);
        };
    },

    throttle: (func, delay) => {
        let timeoutId;
        let lastExecTime = 0;
        return (...args) => {
            const currentTime = Date.now();
            
            if (currentTime - lastExecTime > delay) {
                func.apply(null, args);
                lastExecTime = currentTime;
            } else {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    func.apply(null, args);
                    lastExecTime = Date.now();
                }, delay - (currentTime - lastExecTime));
            }
        };
    }
};

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new InmobiliariaApp();
});

// Export for global use
window.InmobiliariaApp = InmobiliariaApp;
window.utils = utils;