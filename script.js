// HGW Landing Page Script
// Author: AI Assistant
// Description: Replicable marketing page with GSAP animations and affiliate system

document.addEventListener('DOMContentLoaded', function() {
    // Register GSAP plugins
    gsap.registerPlugin(ScrollTrigger);

    // =========================================
    // 1. URL Parameters & Replicability Engine
    // =========================================
    
    const urlParams = new URLSearchParams(window.location.search);
    
    // Extract affiliate parameters
    const affiliateId = urlParams.get('affiliateId') || 'default';
    const webhookUrl = urlParams.get('webhookUrl') || 'https://hooks.zapier.com/hooks/catch/default/webhook';
    const affiliateName = urlParams.get('affiliateName') || null;
    const redirectUrl = urlParams.get('redirectUrl') || 'thank-you.html';
    
    // Personalize content if affiliate name is provided
    if (affiliateName) {
        const affiliateNamePlaceholder = document.getElementById('affiliate-name-placeholder');
        if (affiliateNamePlaceholder) {
            affiliateNamePlaceholder.textContent = affiliateName + ' y miles de';
        }
    }
    
    console.log('Affiliate System Initialized:', {
        affiliateId,
        webhookUrl,
        affiliateName,
        redirectUrl
    });

    // =========================================
    // 2. GSAP Animations & ScrollTrigger Setup
    // =========================================
    
    // Problem Cards Stagger Animation
    function initProblemCardsAnimation() {
        const problemCards = document.querySelectorAll('.problem-card');
        
        gsap.from(problemCards, {
            duration: 0.8,
            y: 50,
            opacity: 0,
            stagger: 0.2,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#problem",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });
    }

    // Social Proof Cards Animation
    function initSocialProofAnimation() {
        const testimonialCards = document.querySelectorAll('.testimonial-card');
        
        gsap.from(testimonialCards, {
            duration: 0.8,
            y: 50,
            opacity: 0,
            stagger: 0.3,
            ease: "power2.out",
            scrollTrigger: {
                trigger: "#social-proof",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });
    }

    // Horizontal Products Scroll Animation
    function initProductsScrollAnimation() {
        const productScroller = document.querySelector('.product-scroller');
        const pinContainer = document.querySelector('#pin-container');
        
        if (productScroller && pinContainer) {
            // Calculate scroll distance
            const scrollWidth = productScroller.scrollWidth;
            const containerWidth = pinContainer.offsetWidth;
            const scrollDistance = scrollWidth - containerWidth;
            
            gsap.to(productScroller, {
                x: -scrollDistance,
                ease: "none",
                scrollTrigger: {
                    trigger: "#pin-container",
                    pin: true,
                    scrub: 1,
                    start: "top top",
                    end: () => `+=${scrollDistance}`,
                    invalidateOnRefresh: true
                }
            });
        }
    }

    // Opportunity Infographic Animation
    function initOpportunityInfographicAnimation() {
        const infographic = document.getElementById('opportunity-infographic');
        
        if (infographic) {
            const circles = infographic.querySelectorAll('.level-circle');
            const lines = infographic.querySelectorAll('.connection-line');
            const texts = infographic.querySelectorAll('.level-text');
            
            // Create timeline
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: "#opportunity-infographic",
                    start: "top 70%",
                    end: "bottom 30%",
                    scrub: true,
                    toggleActions: "play none none reverse"
                }
            });
            
            // Animate base circle first
            tl.to(circles[0], {
                opacity: 1,
                scale: 1.2,
                duration: 0.5,
                ease: "back.out(1.7)"
            })
            // Animate connection lines
            .to(lines, {
                strokeDashoffset: 0,
                duration: 1,
                stagger: 0.2
            }, "-=0.3")
            // Animate remaining circles
            .to(circles.slice(1), {
                opacity: 1,
                scale: 1.1,
                duration: 0.3,
                stagger: 0.1,
                ease: "back.out(1.7)"
            }, "-=0.5")
            // Animate text labels
            .to(texts, {
                opacity: 1,
                duration: 0.5,
                stagger: 0.1
            }, "-=0.3");
        }
    }

    // Steps Animation
    function initStepsAnimation() {
        const steps = document.querySelectorAll('.step');
        
        gsap.from(steps, {
            duration: 0.6,
            y: 30,
            opacity: 0,
            stagger: 0.2,
            ease: "power2.out",
            scrollTrigger: {
                trigger: ".steps",
                start: "top 80%",
                toggleActions: "play none none reverse"
            }
        });
    }

    // Hero Content Animation
    function initHeroAnimation() {
        const heroContent = document.querySelector('.hero-content');
        
        if (heroContent) {
            gsap.from(heroContent.children, {
                duration: 1,
                y: 30,
                opacity: 0,
                stagger: 0.3,
                ease: "power2.out",
                delay: 0.5
            });
        }
    }

    // Initialize all animations
    function initAnimations() {
        initHeroAnimation();
        initProblemCardsAnimation();
        initProductsScrollAnimation();
        initOpportunityInfographicAnimation();
        initSocialProofAnimation();
        initStepsAnimation();
    }

    // =========================================
    // 3. Form Validation & Sanitization
    // =========================================
    
    // HTML Sanitization function
    function sanitizeHTML(str) {
        if (typeof str !== 'string') return '';
        
        const entityMap = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;'
        };
        
        return str.replace(/[&<>"'\/]/g, function(s) {
            return entityMap[s];
        });
    }

    // Email validation
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Phone validation (basic international format)
    function isValidPhone(phone) {
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        const cleanPhone = phone.replace(/[\s\-\(\)]/g, '');
        return phoneRegex.test(cleanPhone);
    }

    // Form validation
    function validateForm(formData) {
        const errors = [];
        
        // Required fields validation
        if (!formData.firstName.trim()) {
            errors.push('El nombre es requerido');
        }
        
        if (!formData.lastName.trim()) {
            errors.push('El apellido es requerido');
        }
        
        if (!formData.email.trim()) {
            errors.push('El email es requerido');
        } else if (!isValidEmail(formData.email)) {
            errors.push('El formato del email no es válido');
        }
        
        if (!formData.phone.trim()) {
            errors.push('El teléfono es requerido');
        } else if (!isValidPhone(formData.phone)) {
            errors.push('El formato del teléfono no es válido');
        }
        
        if (!formData.country) {
            errors.push('Debes seleccionar un país');
        }
        
        return errors;
    }

    // Show form errors
    function showFormErrors(errors) {
        const errorContainer = document.getElementById('form-errors');
        if (errorContainer) {
            if (errors.length > 0) {
                errorContainer.innerHTML = '<ul>' + errors.map(error => `<li>${error}</li>`).join('') + '</ul>';
                errorContainer.classList.add('show');
            } else {
                errorContainer.classList.remove('show');
            }
        }
    }

    // =========================================
    // 4. Form Submission & Webhook Integration
    // =========================================
    
    async function handleSubmit(event) {
        event.preventDefault();
        
        const form = event.target;
        const submitBtn = document.getElementById('submit-btn');
        const errorContainer = document.getElementById('form-errors');
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';
        
        try {
            // Collect form data
            const formData = {
                firstName: sanitizeHTML(form.firstName.value.trim()),
                lastName: sanitizeHTML(form.lastName.value.trim()),
                email: sanitizeHTML(form.email.value.trim()),
                phone: sanitizeHTML(form.phone.value.trim()),
                country: sanitizeHTML(form.country.value)
            };
            
            // Validate form
            const validationErrors = validateForm(formData);
            if (validationErrors.length > 0) {
                showFormErrors(validationErrors);
                return;
            }
            
            // Clear previous errors
            showFormErrors([]);
            
            // Create payload for webhook
            const payload = {
                firstName: formData.firstName,
                lastName: formData.lastName,
                email: formData.email,
                phone: formData.phone,
                country: formData.country,
                affiliateId: affiliateId,
                affiliateName: affiliateName,
                source: 'HGW Landing Page',
                timestamp: new Date().toISOString(),
                userAgent: navigator.userAgent,
                language: navigator.language,
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                referrer: document.referrer,
                currentUrl: window.location.href
            };
            
            console.log('Sending payload:', payload);
            
            // Send to webhook
            const response = await fetch(webhookUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            });
            
            if (response.ok) {
                // Success - redirect to thank you page
                console.log('Form submitted successfully');
                
                // Add success animation
                gsap.to(form, {
                    scale: 0.95,
                    opacity: 0.8,
                    duration: 0.3,
                    onComplete: () => {
                        window.location.href = redirectUrl;
                    }
                });
                
            } else {
                // Server error
                throw new Error(`Server responded with status: ${response.status}`);
            }
            
        } catch (error) {
            console.error('Form submission error:', error);
            
            // Show error message
            errorContainer.innerHTML = '<p>Ocurrió un error al enviar el formulario. Por favor, inténtalo de nuevo.</p>';
            errorContainer.classList.add('show');
            
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.textContent = 'Comenzar Mi Transformación';
        }
    }

    // =========================================
    // 5. Event Listeners & Initialization
    // =========================================
    
    // Form submission
    const registrationForm = document.getElementById('registration-form');
    if (registrationForm) {
        registrationForm.addEventListener('submit', handleSubmit);
    }

    // Smooth scroll for CTA button
    const ctaScroll = document.querySelector('.cta-scroll');
    if (ctaScroll) {
        ctaScroll.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                gsap.to(window, {
                    duration: 1,
                    scrollTo: targetElement,
                    ease: "power2.inOut"
                });
            }
        });
    }

    // Real-time form validation feedback
    function addFormValidationListeners() {
        const formInputs = document.querySelectorAll('#registration-form input, #registration-form select');
        
        formInputs.forEach(input => {
            input.addEventListener('blur', function() {
                const value = this.value.trim();
                const fieldName = this.name;
                
                // Add visual feedback based on validation
                this.classList.remove('valid', 'invalid');
                
                if (value) {
                    switch (fieldName) {
                        case 'email':
                            if (isValidEmail(value)) {
                                this.classList.add('valid');
                            } else {
                                this.classList.add('invalid');
                            }
                            break;
                        case 'phone':
                            if (isValidPhone(value)) {
                                this.classList.add('valid');
                            } else {
                                this.classList.add('invalid');
                            }
                            break;
                        default:
                            if (value.length >= 2) {
                                this.classList.add('valid');
                            } else {
                                this.classList.add('invalid');
                            }
                    }
                }
            });
        });
    }

    // FAQ accordion functionality
    function initFAQAccordions() {
        const faqDetails = document.querySelectorAll('.faq details');
        
        faqDetails.forEach(detail => {
            detail.addEventListener('toggle', function() {
                if (this.open) {
                    // Close other open details
                    faqDetails.forEach(otherDetail => {
                        if (otherDetail !== this && otherDetail.open) {
                            otherDetail.open = false;
                        }
                    });
                    
                    // Animate opening
                    const content = this.querySelector('p');
                    if (content) {
                        gsap.from(content, {
                            height: 0,
                            opacity: 0,
                            duration: 0.3,
                            ease: "power2.out"
                        });
                    }
                }
            });
        });
    }

    // Video thumbnail click handlers
    function initVideoThumbnails() {
        const playButtons = document.querySelectorAll('.play-button');
        
        playButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Simulate video play (in real implementation, this would open a modal or navigate to video)
                gsap.to(this, {
                    scale: 1.2,
                    duration: 0.1,
                    yoyo: true,
                    repeat: 1,
                    ease: "power2.inOut"
                });
                
                console.log('Video play requested for testimonial');
                // Here you would typically open a video modal or redirect to video
            });
        });
    }

    // Scroll progress indicator
    function initScrollProgress() {
        const progressBar = document.createElement('div');
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
            z-index: 1001;
            transition: width 0.1s ease;
        `;
        document.body.appendChild(progressBar);
        
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            progressBar.style.width = scrollPercent + '%';
        });
    }

    // Initialize all functionality
    function init() {
        console.log('HGW Landing Page Initialized');
        
        // Initialize animations
        initAnimations();
        
        // Initialize form validation
        addFormValidationListeners();
        
        // Initialize FAQ accordions
        initFAQAccordions();
        
        // Initialize video thumbnails
        initVideoThumbnails();
        
        // Initialize scroll progress
        initScrollProgress();
        
        // Log successful initialization
        console.log('All systems initialized successfully');
    }

    // Start the application
    init();

    // =========================================
    // 6. Performance Optimizations
    // =========================================
    
    // Lazy load images when they come into view
    function initLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }

    // Debounce scroll events for better performance
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Initialize performance optimizations
    initLazyLoading();
    
    // Refresh ScrollTrigger on window resize (debounced)
    window.addEventListener('resize', debounce(() => {
        ScrollTrigger.refresh();
    }, 250));

    // =========================================
    // 7. Analytics & Tracking (Optional)
    // =========================================
    
    // Track form interactions
    function trackFormInteraction(action, field = null) {
        console.log('Form Interaction:', { action, field, affiliateId, timestamp: new Date().toISOString() });
        
        // Here you would typically send to your analytics service
        // Example: gtag('event', action, { 'custom_parameter': field });
    }

    // Track section views
    function initSectionTracking() {
        const sections = document.querySelectorAll('section[id]');
        
        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sectionId = entry.target.id;
                    console.log('Section viewed:', sectionId);
                    // Track section view
                }
            });
        }, { threshold: 0.5 });
        
        sections.forEach(section => sectionObserver.observe(section));
    }

    // Initialize tracking
    initSectionTracking();

    // =========================================
    // 8. Error Handling & Fallbacks
    // =========================================
    
    // Global error handler
    window.addEventListener('error', function(event) {
        console.error('Global error:', event.error);
        // You could send this to an error tracking service
    });

    // GSAP fallback for older browsers
    if (!window.gsap) {
        console.warn('GSAP not loaded, falling back to CSS animations');
        document.body.classList.add('fallback-animations');
    }

    console.log('HGW Landing Page Script Loaded Successfully');
});

// =========================================
// 9. Utility Functions
// =========================================

// Get cookie value
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

// Set cookie
function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
}

// Format phone number for display
function formatPhoneNumber(phone) {
    const cleaned = phone.replace(/\D/g, '');
    const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
    if (match) {
        return '(' + match[1] + ') ' + match[2] + '-' + match[3];
    }
    return phone;
}

// Export functions for external use
window.HGW = {
    sanitizeHTML: sanitizeHTML,
    getCookie: getCookie,
    setCookie: setCookie,
    formatPhoneNumber: formatPhoneNumber
};