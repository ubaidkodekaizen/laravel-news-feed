import './bootstrap.js';
import '../css/chat.css';
import React, { useEffect, useState } from 'react';
import { createRoot } from 'react-dom/client';
import ChatBox from './components/ChatBox.jsx';

const App = () => {
  const [isTokenAvailable, setIsTokenAvailable] = useState(false);

  useEffect(() => {
    // Check if the token is available in localStorage
    const token = localStorage.getItem("sanctum-token");

    if (token) {
      setIsTokenAvailable(true);
    } else {
      // If token is not available, listen for changes in localStorage
      const handleStorageChange = (event) => {
        if (event.key === "sanctum-token" && event.newValue) {
          setIsTokenAvailable(true);
        }
      };

      // Listen for the custom event
      const handleCustomEvent = () => {
        setIsTokenAvailable(true);
      };

      window.addEventListener("storage", handleStorageChange);
      window.addEventListener("sanctum-token-set", handleCustomEvent);

      // Cleanup
      return () => {
        window.removeEventListener("storage", handleStorageChange);
        window.removeEventListener("sanctum-token-set", handleCustomEvent);
      };
    }
  }, []);

  // Render ChatBox only if the token is available
  return isTokenAvailable ? <ChatBox /> : <div>Loading...</div>;
};

// Check if the chat container exists and user is authenticated
if (document.getElementById('chat-container')) {
  const container = document.getElementById('chat-container');
  const root = createRoot(container);
  root.render(<App />);
}
