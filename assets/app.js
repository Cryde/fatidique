import './countdown.js';
import './particles.js';

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
      }
    });

    // Set initial theme if one is already selected
    if (radio.checked && radio.value) {
      removeThemeClasses();
      document.body.classList.add(`theme-${radio.value}`);
    }
  });
}

