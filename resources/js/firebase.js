import { initializeApp } from 'firebase/app';
import { getDatabase, ref, onValue, onChildAdded, onChildChanged, onChildRemoved, set, remove, query, orderByChild, limitToLast } from 'firebase/database';
import { getAuth, signInWithCustomToken } from 'firebase/auth';

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

// Setup presence system
export const setupPresence = async (userId) => {
  const userStatusRef = ref(database, `presence/users/${userId}`);

  try {
    await set(userStatusRef, {
      online: true,
      last_active: Date.now()
    });

    console.log('✅ User presence set to online');
  } catch (error) {
    console.error('❌ Error setting user presence:', error);
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
  limitToLast
};
