<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class FirebaseController extends Controller
{
    /**
     * Generate and return the Firebase Messaging Service Worker
     * This must be accessible at /firebase-messaging-sw.js for FCM to work
     */
    public function serviceWorker()
    {
        $config = [
            'apiKey' => env('VITE_FIREBASE_API_KEY'),
            'authDomain' => env('VITE_FIREBASE_AUTH_DOMAIN'),
            'databaseURL' => env('VITE_FIREBASE_DATABASE_URL'),
            'projectId' => env('VITE_FIREBASE_PROJECT_ID'),
            'storageBucket' => env('VITE_FIREBASE_STORAGE_BUCKET'),
            'messagingSenderId' => env('VITE_FIREBASE_MESSAGING_SENDER_ID'),
            'appId' => env('VITE_FIREBASE_APP_ID'),
            'measurementId' => env('VITE_FIREBASE_MEASUREMENT_ID'),
        ];

        // Filter out null values - Firebase doesn't like null config values
        $config = array_filter($config, function($value) {
            return $value !== null && $value !== '';
        });

        $serviceWorkerContent = <<<'SW'
// Firebase Cloud Messaging Service Worker
// NOTE: This service worker ONLY handles push notifications (messaging)
// It does NOT interfere with chat functionality which uses database/auth in the main app
// Chat runs in the main JavaScript context, this runs in isolated service worker context
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

const firebaseConfig = CONFIG_PLACEHOLDER;

// Initialize Firebase app (same project as main app, but isolated context)
firebase.initializeApp(firebaseConfig);
// Only initialize messaging - we don't use database/auth here
const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  
  const notificationTitle = payload.notification?.title || 'New Notification';
  const notificationOptions = {
    body: payload.notification?.body || '',
    icon: payload.notification?.icon || '/favicon.ico',
    badge: '/favicon.ico',
    data: payload.data || {},
    requireInteraction: false,
    tag: payload.data?.type || 'notification',
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});

self.addEventListener('notificationclick', (event) => {
  console.log('[firebase-messaging-sw.js] Notification click received.');
  
  event.notification.close();

  const data = event.notification.data || {};
  let urlToOpen = '/news-feed';

  if (data.type === 'new_message' && data.conversation_id) {
    urlToOpen = `/inbox?conversation=${data.conversation_id}`;
  } else if (data.type === 'message_reaction' && data.conversation_id) {
    urlToOpen = `/inbox?conversation=${data.conversation_id}`;
  } else if (data.type === 'post_reaction' || data.type === 'post_comment' || data.type === 'post_share' || data.type === 'comment_reply') {
    if (data.post_slug) {
      urlToOpen = `/news-feed/posts/${data.post_slug}`;
      if (data.comment_id) {
        urlToOpen += `#comment-${data.comment_id}`;
      } else if (data.parent_comment_id) {
        urlToOpen += `#comment-${data.parent_comment_id}`;
      }
    }
  } else if (data.type === 'profile_view' && data.viewer_id) {
    urlToOpen = `/user/profile/${data.viewer_slug || data.viewer_id}`;
  } else if (data.type === 'new_follower' && data.follower_id) {
    urlToOpen = `/user/profile/${data.follower_slug || data.follower_id}`;
  } else if (data.type === 'new_service' && data.service_id) {
    urlToOpen = `/services#service-${data.service_id}`;
  } else if (data.type === 'new_product' && data.product_id) {
    urlToOpen = `/products#product-${data.product_id}`;
  }

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
      for (let i = 0; i < clientList.length; i++) {
        const client = clientList[i];
        if (client.url === urlToOpen && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(urlToOpen);
      }
    })
  );
});
SW;

        $configJson = json_encode($config, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $serviceWorkerContent = str_replace('CONFIG_PLACEHOLDER', $configJson, $serviceWorkerContent);

        return response($serviceWorkerContent, 200)
            ->header('Content-Type', 'application/javascript')
            ->header('Service-Worker-Allowed', '/');
    }
}
