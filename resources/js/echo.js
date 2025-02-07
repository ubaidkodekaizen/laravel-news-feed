import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

function initializeEcho() {
  const token = localStorage.getItem("sanctum-token");

  if (!token) {
    console.warn("Sanctum token not found. Echo will not initialize yet.");
    return;
  }

  console.log("Initializing Echo with token:", token);

  window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    host: import.meta.env.VITE_REVERB_HOST || window.location.hostname,
    port: import.meta.env.VITE_REVERB_PORT || 8080,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: false,
    auth: {
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Authorization': `Bearer ${token}`, // Use backticks
        },
      },
    enabledTransports: ['ws', 'wss'],
  });

  console.log("Echo initialized:", window.Echo);
}

// Listen for token changes in localStorage
window.addEventListener("storage", (event) => {
  if (event.key === "sanctum-token" && event.newValue) {
    console.log("Sanctum token updated, re-initializing Echo...");
    initializeEcho();
  }
});

// Initialize Echo when the page loads (if token is already set)
if (localStorage.getItem("sanctum-token")) {
  initializeEcho();
}