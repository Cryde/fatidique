import './countdown.js';

import.meta.glob([
  './images/**',
  './*.png',
  './*.ico',
]);

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
      }
    });

    // Set initial theme if one is already selected
    if (radio.checked && radio.value) {
      removeThemeClasses();
      document.body.classList.add(`theme-${radio.value}`);
    }
  });
}

