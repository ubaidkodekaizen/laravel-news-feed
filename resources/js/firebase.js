import { initializeApp } from 'firebase/app';
import { getDatabase, ref, onValue, onChildAdded, onChildChanged, onChildRemoved, set, remove, query, orderByChild, limitToLast, onDisconnect } from 'firebase/database';
import { getAuth, signInWithCustomToken } from 'firebase/auth';
import { getMessaging, getToken, onMessage } from 'firebase/messaging';
import axios from 'axios';

const firebaseConfig = {
  apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
  authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
  databaseURL: import.meta.env.VITE_FIREBASE_DATABASE_URL,
  projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
  storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
  messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
  appId: import.meta.env.VITE_FIREBASE_APP_ID,
  measurementId: import.meta.env.VITE_FIREBASE_MEASUREMENT_ID
};

const app = initializeApp(firebaseConfig);
const database = getDatabase(app);
const auth = getAuth(app);

// Authenticate with Firebase using custom token from Laravel
export const authenticateFirebase = async () => {
  try {
    const token = localStorage.getItem("sanctum-token");
    const response = await axios.get('/api/firebase-token', {
      headers: { Authorization: token }
    });

    await signInWithCustomToken(auth, response.data.firebase_token);
    console.log('✅ Firebase authenticated successfully');

    return true;
  } catch (error) {
    console.error('❌ Firebase authentication failed:', error);
    return false;
  }
};

// ✅ Enhanced presence system with automatic reconnection
export const setupPresence = async (userId) => {
  if (!userId) {
    console.warn("No userId provided for presence");
    return;
  }

  const userIdString = String(userId);
  const userStatusRef = ref(database, `presence/users/${userIdString}`);

  try {
    // Set user as online
    await set(userStatusRef, {
      online: true,
      last_active: Date.now()
    });

    // ✅ Setup automatic offline on disconnect
    await onDisconnect(userStatusRef).set({
      online: false,
      last_active: Date.now()
    });

    // ✅ Update last_active every 30 seconds while user is active
    const updateInterval = setInterval(async () => {
      try {
        await set(userStatusRef, {
          online: true,
          last_active: Date.now()
        });
      } catch (error) {
        console.error("Error updating presence:", error);
      }
    }, 30000); // Update every 30 seconds

    // ✅ Cleanup on page unload
    const cleanup = () => {
      clearInterval(updateInterval);
    };

    window.addEventListener('beforeunload', cleanup);
    window.addEventListener('unload', cleanup);

    // Store cleanup function for later use
    window.__presenceCleanup = cleanup;

    console.log('✅ User presence set to online with auto-disconnect');
  } catch (error) {
    console.error('❌ Error setting user presence:', error);
  }
};

// ✅ Listen to a user's online status
export const listenToUserStatus = (userId, callback) => {
  if (!userId) {
    console.warn("No userId provided for status listener");
    return null;
  }

  const userIdString = String(userId);
  const userStatusRef = ref(database, `presence/users/${userIdString}`);

  const unsubscribe = onValue(userStatusRef, (snapshot) => {
    const data = snapshot.val();

    if (!data) {
      callback({ online: false, last_active: null });
      return;
    }

    const lastActive = data.last_active || 0;
    const isOnline = data.online || false;
    const now = Date.now();
    const timeDiff = now - lastActive;

    // Consider user online if they were active in the last 60 seconds
    const isRecentlyActive = timeDiff < 60000; // 60 seconds

    callback({
      online: isOnline && isRecentlyActive,
      last_active: lastActive
    });
  });

  return unsubscribe;
};

// ✅ Cleanup presence on logout
export const cleanupPresence = async (userId) => {
  if (!userId) return;

  const userIdString = String(userId);
  const userStatusRef = ref(database, `presence/users/${userIdString}`);

  try {
    await set(userStatusRef, {
      online: false,
      last_active: Date.now()
    });

    // Call stored cleanup function if it exists
    if (window.__presenceCleanup) {
      window.__presenceCleanup();
      delete window.__presenceCleanup;
    }

    console.log('✅ User presence cleaned up');
  } catch (error) {
    console.error('❌ Error cleaning up presence:', error);
  }
};

// Initialize Firebase Messaging
let messaging = null;
try {
  messaging = getMessaging(app);
} catch (error) {
  console.warn('Firebase Messaging not available:', error);
}

/**
 * Request notification permission and register FCM token
 */
export const requestNotificationPermission = async () => {
  if (!messaging) {
    console.warn('Firebase Messaging is not available');
    return null;
  }

  try {
    // Request permission
    const permission = await Notification.requestPermission();
    
    if (permission !== 'granted') {
      console.warn('Notification permission denied');
      return null;
    }

    // Get FCM token
    const vapidKey = import.meta.env.VITE_FIREBASE_VAPID_KEY;
    if (!vapidKey) {
      console.error('VAPID key is not configured');
      return null;
    }

    const token = await getToken(messaging, { vapidKey });
    
    if (token) {
      console.log('FCM token obtained:', token);
      
      // Register token with backend
      await registerFCMToken(token);
      
      return token;
    } else {
      console.warn('No FCM token available');
      return null;
    }
  } catch (error) {
    console.error('Error requesting notification permission:', error);
    return null;
  }
};

/**
 * Register FCM token with backend API
 */
export const registerFCMToken = async (fcmToken) => {
  try {
    const token = localStorage.getItem("sanctum-token");
    if (!token) {
      console.warn('No auth token found, skipping FCM registration');
      return;
    }

    const response = await axios.post('/api/device-token/register', {
      fcm_token: fcmToken,
      device_type: 'web',
      device_name: navigator.userAgent,
    }, {
      headers: { 
        Authorization: token,
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
      }
    });

    console.log('FCM token registered successfully');
    return response.data;
  } catch (error) {
    console.error('Failed to register FCM token:', error);
    throw error;
  }
};

/**
 * Listen for foreground messages
 */
export const setupForegroundMessageListener = () => {
  if (!messaging) {
    return;
  }

  onMessage(messaging, (payload) => {
    console.log('Message received in foreground:', payload);
    
    // Show notification manually (browser will handle background)
    if (Notification.permission === 'granted') {
      const notificationTitle = payload.notification?.title || 'New Notification';
      const notificationOptions = {
        body: payload.notification?.body || '',
        icon: payload.notification?.icon || '/favicon.ico',
        badge: '/favicon.ico',
        data: payload.data || {},
        tag: payload.data?.type || 'notification',
      };

      new Notification(notificationTitle, notificationOptions);
    }
  });
};

export {
  database,
  auth,
  messaging,
  ref,
  onValue,
  onChildAdded,
  onChildChanged,
  onChildRemoved,
  set,
  remove,
  query,
  orderByChild,
  limitToLast,
  onDisconnect
};
