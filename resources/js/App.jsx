import './bootstrap.js';
import '../css/chat.css';
import React, { useEffect, useState } from 'react';
import { createRoot } from 'react-dom/client';
import ChatBox from './components/ChatBox.jsx';
import UnreadCountBadge from './components/UnreadCountBadge.jsx';
// Firebase removed - newsfeed boilerplate doesn't use Firebase
// import { authenticateFirebase, setupPresence, requestNotificationPermission, setupForegroundMessageListener } from './firebase.js';

const App = () => {
  const [isTokenAvailable, setIsTokenAvailable] = useState(false);

  useEffect(() => {
    const token = localStorage.getItem("sanctum-token");

    if (token) {
      setIsTokenAvailable(true);
      // ✅ Initialize Firebase when token is available
      initializeFirebase();
    } else {
      const handleStorageChange = (event) => {
        if (event.key === "sanctum-token" && event.newValue) {
          setIsTokenAvailable(true);
          // ✅ Initialize Firebase when token is set
          initializeFirebase();
        }
      };

      const handleCustomEvent = () => {
        setIsTokenAvailable(true);
        // ✅ Initialize Firebase when token is set
        initializeFirebase();
      };

      window.addEventListener("storage", handleStorageChange);
      window.addEventListener("sanctum-token-set", handleCustomEvent);

      return () => {
        window.removeEventListener("storage", handleStorageChange);
        window.removeEventListener("sanctum-token-set", handleCustomEvent);
      };
    }
  }, []);

  // ✅ Initialize Firebase authentication and presence
  const initializeFirebase = async () => {
    if (window.userId) {
      try {
        const authenticated = await authenticateFirebase();
        if (authenticated) {
          await setupPresence(window.userId);
          
          // Register service worker for FCM
          if ('serviceWorker' in navigator) {
            try {
              const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
              console.log('Service Worker registered:', registration);
              
              // Request notification permission and register FCM token
              await requestNotificationPermission();
              
              // Setup foreground message listener
              setupForegroundMessageListener();
            } catch (error) {
              console.error('Service Worker registration failed:', error);
            }
          }
          console.log('✅ Firebase initialized and presence setup complete');
        }
      } catch (error) {
        console.error('❌ Failed to initialize Firebase:', error);
      }
    }
  };

  return isTokenAvailable ? <ChatBox /> : <div>Loading...</div>;
};

// Render ChatBox
if (document.getElementById('chat-container')) {
  const container = document.getElementById('chat-container');
  const root = createRoot(container);
  root.render(<App />);
}

// ✅ Render Unread Badge in Header
if (document.getElementById('inbox-unread-badge')) {
  const badgeContainer = document.getElementById('inbox-unread-badge');
  const badgeRoot = createRoot(badgeContainer);
  badgeRoot.render(<UnreadCountBadge />);
}
