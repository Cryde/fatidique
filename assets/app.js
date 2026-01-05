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

