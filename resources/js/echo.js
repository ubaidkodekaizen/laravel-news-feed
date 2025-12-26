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

  // Get scheme from env, default to http for local
  const scheme = import.meta.env.VITE_REVERB_SCHEME || 'http';
  const useTLS = scheme === 'https';

  console.log("Echo config:", {
    key: import.meta.env.VITE_REVERB_APP_KEY,
    host: import.meta.env.VITE_REVERB_HOST,
    port: import.meta.env.VITE_REVERB_PORT,
    scheme: scheme,
    useTLS: useTLS
  });

  window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: useTLS,
    disableStats: true,
    enabledTransports: useTLS ? ['wss'] : ['ws'], // Only use the appropriate transport
    auth: {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Authorization': token,
      },
    },
  });

  console.log("Echo initialized:", window.Echo);

  // Join presence channel to indicate online status
  if (window.userId) {
    window.Echo.join(`presence-online`)
      .here((users) => {
        console.log('Online users:', users);
      })
      .joining((user) => {
        console.log('User joined:', user);
      })
      .leaving((user) => {
        console.log('User left:', user);
      })
      .error((error) => {
        console.error('Presence channel error:', error);
      });

    // Ping server every 5 minutes
    setInterval(() => {
      fetch(userPing, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Authorization': token
        }
      }).catch(err => console.warn('Failed to ping server for online status'));
    }, 5 * 60 * 1000);
  }
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

// Handle window unload to mark user as offline
window.addEventListener('beforeunload', () => {
  const token = localStorage.getItem("sanctum-token");
  if (token && window.Echo) {
    try {
      window.Echo.leave('presence-online');
      navigator.sendBeacon(userOffline, JSON.stringify({
        token: token
      }));
    } catch (e) {
      console.error('Error during logout:', e);
    }
  }
});
