(() => {
  const toast = document.getElementById('toast');
  let toastTimer;
  function showToast(message) {
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('visible');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('visible'), 2600);
  }
  document.querySelectorAll('[data-copy]').forEach(button => {
    button.addEventListener('click', async () => {
      const value = button.getAttribute('data-copy') || '';
      try {
        if (navigator.clipboard && window.isSecureContext) await navigator.clipboard.writeText(value);
        else {
          const temp = document.createElement('textarea');
          temp.value = value; temp.style.position = 'fixed'; temp.style.opacity = '0';
          document.body.appendChild(temp); temp.select();
          const ok = document.execCommand('copy'); temp.remove();
          if (!ok) throw new Error('Copy failed');
        }
        showToast('Link copied to clipboard');
      } catch (_) { showToast('Copy failed. Select and copy the link manually.'); }
    });
  });
})();
