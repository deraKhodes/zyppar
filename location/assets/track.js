(() => {
  const status = document.getElementById('location-status');
  if (!status) return;
  const setStatus = (text, bad = false) => {
    status.textContent = text;
    status.classList.toggle('status-error', bad);
  };
  const requestLocation = () => {
    if (!('geolocation' in navigator)) {
      setStatus('This browser does not support location access.', true);
      return;
    }
    if (!window.isSecureContext && !['localhost', '127.0.0.1'].includes(location.hostname)) {
      setStatus('Location access needs HTTPS. Please open the secure version of this link.', true);
      return;
    }
    setStatus('Waiting for your browser permission…');
    navigator.geolocation.getCurrentPosition(async position => {
      try {
        const response = await fetch('save_location.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({
            token: status.dataset.token,
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            accuracy: position.coords.accuracy
          })
        });
        const data = await response.json();
        if (!response.ok || !data.ok) throw new Error(data.message || 'Could not save location.');
        setStatus("Thanks, you're all set.");
      } catch (error) {
        setStatus(error.message || 'We could not complete this request. Please try again later.', true);
      }
    }, error => {
      const messages = {
        1: 'Permission was not granted, so no location was sent.',
        2: 'Your device could not determine its location.',
        3: 'The location request timed out.'
      };
      setStatus(messages[error.code] || 'Location could not be retrieved.', true);
    }, {enableHighAccuracy: true, timeout: 20000, maximumAge: 0});
  };

  if (document.readyState === 'complete') {
    requestLocation();
  } else {
    window.addEventListener('load', requestLocation, {once: true});
  }
})();
