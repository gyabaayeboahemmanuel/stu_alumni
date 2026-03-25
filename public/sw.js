// Service Worker for STU Alumni PWA
const CACHE_NAME = 'stu-alumni-v1';
const RUNTIME_CACHE = 'stu-alumni-runtime-v1';
const PRECACHE_ASSETS = ['/', '/manifest.json'];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_ASSETS)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.filter((n) => n !== CACHE_NAME && n !== RUNTIME_CACHE).map((n) => caches.delete(n))
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET' || !event.request.url.startsWith(self.location.origin) || 
      event.request.url.includes('/admin') || event.request.url.includes('/api')) {
    return;
  }

  event.respondWith(
    // Network-first: always try the network so auth-sensitive pages (like `/`)
    // don't become stale. Only fall back to cache when the network fails.
    fetch(event.request)
      .then((response) => {
        if (!response || response.status !== 200) return response;

        // Cache successful responses for offline fallback.
        const clone = response.clone();
        caches.open(RUNTIME_CACHE).then((cache) => cache.put(event.request, clone));

        return response;
      })
      .catch(() => {
        // If the network fails, return the cached version (if any).
        return caches.match(event.request).then((cached) => {
          if (cached) return cached;
          // Final fallback for document navigations.
          if (event.request.destination === 'document') return caches.match('/');
          return null;
        });
      })
  );
});

