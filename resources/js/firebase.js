import { initializeApp } from 'firebase/app';
import { getDatabase, ref, onValue, onChildAdded, onChildChanged, onChildRemoved, set, remove, query, orderByChild, limitToLast, onDisconnect } from 'firebase/database';
import { getAuth, signInWithCustomToken } from 'firebase/auth';
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

export {
  database,
  auth,
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
