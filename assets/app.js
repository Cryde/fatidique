import './countdown.js';
import './particles.js';

// Emits the static files Twig reaches via asset(), which resolves through the
// Vite manifest (see config/packages/assets.yaml). 'eager' is required: without
// it Vite 8 tree-shakes the unused glob away, the assets never reach the
// manifest, and every page 500s on "not found in manifest file".
import.meta.glob([
  './images/**',
  './*.png',
  './*.ico',
], { eager: true });

// Copy link button
const copyLinkBtn = document.querySelector('#copy-link');
if (copyLinkBtn) {
  copyLinkBtn.addEventListener('click', async () => {
    try {
      await navigator.clipboard.writeText(window.location.href);
      const originalText = copyLinkBtn.querySelector('span').textContent;
      copyLinkBtn.querySelector('span').textContent = 'Copié !';
      copyLinkBtn.classList.add('btn-success');
      setTimeout(() => {
        copyLinkBtn.querySelector('span').textContent = originalText;
        copyLinkBtn.classList.remove('btn-success');
      }, 2000);
    } catch (err) {
      console.error('Failed to copy:', err);
    }
  });
}

// Like button
const likeBtn = document.querySelector('#like-btn');
if (likeBtn) {
  const likeIcon = likeBtn.querySelector('.like-icon');
  const likeCount = likeBtn.querySelector('#like-count');

  likeBtn.addEventListener('click', async () => {
    // Disable button during request
    likeBtn.disabled = true;

    // Add animation
    likeIcon.classList.add('scale-125');
    setTimeout(() => likeIcon.classList.remove('scale-125'), 150);

    try {
      const response = await fetch(likeBtn.dataset.url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
      });

      if (response.ok) {
        const data = await response.json();

        // Update count
        likeCount.textContent = data.count;

        // Update button state
        if (data.liked) {
          likeBtn.classList.remove('btn-outline');
          likeBtn.classList.add('btn-error');
          likeIcon.setAttribute('fill', 'currentColor');
          likeBtn.dataset.liked = '1';
        } else {
          likeBtn.classList.add('btn-outline');
          likeBtn.classList.remove('btn-error');
          likeIcon.setAttribute('fill', 'none');
          likeBtn.dataset.liked = '0';
        }
      }
    } catch (err) {
      console.error('Failed to like:', err);
    } finally {
      likeBtn.disabled = false;
    }
  });
}

// Real-time theme preview on create page
const themeRadios = document.querySelectorAll('input[name*="[theme]"]');
if (themeRadios.length > 0) {
  const removeThemeClasses = () => {
    document.body.classList.forEach(cls => {
      if (cls.startsWith('theme-')) {
        document.body.classList.remove(cls);
      }
    });
  };

  themeRadios.forEach(radio => {
    radio.addEventListener('change', (e) => {
      removeThemeClasses();
      if (e.target.value) {
        document.body.classList.add(`theme-${e.target.value}`);

        // Add celebration animation to selected theme card
        const card = e.target.nextElementSibling;
        if (card) {
          card.classList.add('animate-scale-pop');
          setTimeout(() => card.classList.remove('animate-scale-pop'), 300);
        }
      }
    });

    // Set initial theme if one is already selected
    if (radio.checked && radio.value) {
      removeThemeClasses();
      document.body.classList.add(`theme-${radio.value}`);
    }
  });
}

// ===== MICRO-INTERACTIONS =====

// Enhanced form field focus effects
const initFormFieldEffects = () => {
  document.querySelectorAll('.fieldset').forEach(fieldset => {
    const input = fieldset.querySelector('input, textarea, select');
    if (!input) return;

    input.addEventListener('focus', () => {
      fieldset.style.transform = 'scale(1.01)';
      fieldset.style.transition = 'transform 0.2s ease';
    });

    input.addEventListener('blur', () => {
      fieldset.style.transform = 'scale(1)';
    });
  });
};

// Hint the compositor before a card lifts, and release the hint afterwards so
// we don't keep a layer alive for every card on the page.
const initHoverFeedback = () => {
  document.querySelectorAll('.card-hover-lift').forEach(card => {
    card.addEventListener('mouseenter', () => {
      card.style.willChange = 'transform, box-shadow';
    });

    card.addEventListener('mouseleave', () => {
      setTimeout(() => {
        card.style.willChange = 'auto';
      }, 300);
    });
  });
};

// Progress steps on the create page. Each fieldset declares its step via
// data-step, so adding or reordering fields cannot desync the indicator.
const initProgressSteps = () => {
  const steps = document.querySelectorAll('.steps-playful .step');
  const fieldsets = document.querySelectorAll('.fieldset[data-step]');

  if (steps.length === 0 || fieldsets.length === 0) return;

  const activateStep = (activeStep) => {
    steps.forEach((step, index) => {
      step.classList.toggle('step-primary', index <= activeStep);
    });
  };

  fieldsets.forEach(fieldset => {
    const step = Number(fieldset.dataset.step);

    // 'focusin' bubbles up from nested inputs, unlike 'focus'. 'change' covers
    // the theme picker, whose radios are hidden and never receive focus.
    fieldset.addEventListener('focusin', () => activateStep(step));
    fieldset.addEventListener('change', () => activateStep(step));
  });
};

// Initialize all micro-interactions
document.addEventListener('DOMContentLoaded', () => {
  initFormFieldEffects();
  initHoverFeedback();
  initProgressSteps();

  // Add loaded class for any initial animations
  document.body.classList.add('js-loaded');
});

