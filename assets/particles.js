// Theme particles configuration
const themeParticles = {
  'default': { symbol: '✦', count: 8, speed: 0.3, size: '14px' },
  'party': { symbol: '✨', count: 20, speed: 0.5, size: '16px' },
  'romantic': { symbol: '💗', count: 15, speed: 0.3, size: '18px' },
  'professional': { symbol: '•', count: 10, speed: 0.2, size: '8px' },
  'spooky': { symbol: '🦇', count: 12, speed: 0.4, size: '20px' },
  'tropical': { symbol: '🌺', count: 12, speed: 0.3, size: '20px' },
  'christmas': { symbol: '❄️', count: 25, speed: 0.4, size: '18px' },
  'rainy': { symbol: '💧', count: 30, speed: 1.2, size: '14px', direction: 'down' },
};

let particlesContainer = null;
let particles = [];
let animationId = null;

function createParticle(config, index, total) {
  const particle = document.createElement('div');
  particle.className = 'theme-particle';
  particle.textContent = config.symbol;
  particle.style.cssText = `
    position: fixed;
    font-size: ${config.size};
    pointer-events: none;
    z-index: 0;
    opacity: 0;
    transition: opacity 0.5s ease;
    user-select: none;
  `;

  // Random starting position
  const startX = Math.random() * 100;
  const startY = config.direction === 'down' ? -10 : Math.random() * 100;
  particle.style.left = `${startX}%`;
  particle.style.top = `${startY}%`;

  // Animation properties
  particle.dataset.x = startX;
  particle.dataset.y = startY;
  particle.dataset.speedX = (Math.random() - 0.5) * 0.5;
  particle.dataset.speedY = config.direction === 'down' ? config.speed : (Math.random() - 0.5) * config.speed;
  particle.dataset.wobble = Math.random() * Math.PI * 2;
  particle.dataset.wobbleSpeed = 0.02 + Math.random() * 0.02;

  return particle;
}

function animateParticles(config) {
  particles.forEach((particle, i) => {
    let x = parseFloat(particle.dataset.x);
    let y = parseFloat(particle.dataset.y);
    let wobble = parseFloat(particle.dataset.wobble);
    const speedX = parseFloat(particle.dataset.speedX);
    const speedY = parseFloat(particle.dataset.speedY);
    const wobbleSpeed = parseFloat(particle.dataset.wobbleSpeed);

    // Update position
    if (config.direction === 'down') {
      // Rain falls down with slight wobble
      y += speedY;
      x += Math.sin(wobble) * 0.1;
      wobble += wobbleSpeed;

      // Reset when off screen
      if (y > 110) {
        y = -10;
        x = Math.random() * 100;
      }
    } else {
      // Floating particles
      x += speedX + Math.sin(wobble) * 0.1;
      y += speedY + Math.cos(wobble) * 0.05;
      wobble += wobbleSpeed;

      // Wrap around screen
      if (x > 105) x = -5;
      if (x < -5) x = 105;
      if (y > 105) y = -5;
      if (y < -5) y = 105;
    }

    particle.dataset.x = x;
    particle.dataset.y = y;
    particle.dataset.wobble = wobble;
    particle.style.left = `${x}%`;
    particle.style.top = `${y}%`;
  });

  animationId = requestAnimationFrame(() => animateParticles(config));
}

function initParticles(themeName) {
  // Clean up existing particles
  destroyParticles();

  const config = themeParticles[themeName];
  if (!config || themeName === 'default') return;

  // Create container
  particlesContainer = document.createElement('div');
  particlesContainer.id = 'particles-container';
  particlesContainer.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
  `;
  document.body.appendChild(particlesContainer);

  // Create particles
  for (let i = 0; i < config.count; i++) {
    const particle = createParticle(config, i, config.count);
    particles.push(particle);
    particlesContainer.appendChild(particle);

    // Fade in with delay
    setTimeout(() => {
      particle.style.opacity = config.direction === 'down' ? '0.6' : '0.4';
    }, i * 50);
  }

  // Start animation
  animateParticles(config);
}

function destroyParticles() {
  if (animationId) {
    cancelAnimationFrame(animationId);
    animationId = null;
  }
  if (particlesContainer) {
    particlesContainer.remove();
    particlesContainer = null;
  }
  particles = [];
}

function getCurrentTheme() {
  const bodyClasses = document.body.className;
  const match = bodyClasses.match(/theme-(\w+)/);
  return match ? match[1] : null;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
  const theme = getCurrentTheme();
  if (theme) {
    initParticles(theme);
  }
});

// Listen for theme changes (for create page preview)
const observer = new MutationObserver((mutations) => {
  mutations.forEach((mutation) => {
    if (mutation.attributeName === 'class') {
      const theme = getCurrentTheme();
      initParticles(theme || 'default');
    }
  });
});

observer.observe(document.body, { attributes: true });

// Export for use in other modules
export { initParticles, destroyParticles };
