/**
 * Service Worker - PWA Offline Support & Caching
 * Version: 1.0.0
 */

const CACHE_NAME = 'absensi-guru-v1.0.0';
const OFFLINE_URL = '/offline.html';

// Assets untuk di-cache saat install
const CACHE_URLS = [
    '/',
    '/offline.html',
    '/assets/css/style.css',
    '/assets/css/mobile.css',
    '/assets/js/app.js',
    '/assets/js/pwa.js',
    '/assets/images/icons/icon-192x192.png',
    '/assets/images/icons/icon-512x512.png',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',
    'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js'
];

// Install Event - Cache assets
self.addEventListener('install', (event) => {
    console.log('[SW] Installing Service Worker...', CACHE_NAME);
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Caching app shell');
                return cache.addAll(CACHE_URLS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate Event - Cleanup old caches
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating Service Worker...', CACHE_NAME);
    
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('[SW] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event - Network First, fallback to Cache
self.addEventListener('fetch', (event) => {
    // Skip chrome-extension dan non-http requests
    if (!event.request.url.startsWith('http')) {
        return;
    }

    // Strategi berbeda untuk API calls vs static assets
    if (event.request.url.includes('/api/')) {
        // API: Network Only (dengan offline fallback)
        event.respondWith(
            fetch(event.request)
                .catch(() => {
                    return new Response(
                        JSON.stringify({
                            success: false,
                            message: 'Anda sedang offline. Silakan coba lagi nanti.'
                        }),
                        {
                            headers: { 'Content-Type': 'application/json' }
                        }
                    );
                })
        );
    } else {
        // Static Assets: Cache First, fallback to Network
        event.respondWith(
            caches.match(event.request)
                .then((response) => {
                    if (response) {
                        return response;
                    }

                    return fetch(event.request)
                        .then((response) => {
                            // Clone response karena hanya bisa dibaca sekali
                            const responseToCache = response.clone();

                            // Cache jika GET request dan response OK
                            if (event.request.method === 'GET' && response.status === 200) {
                                caches.open(CACHE_NAME)
                                    .then((cache) => {
                                        cache.put(event.request, responseToCache);
                                    });
                            }

                            return response;
                        })
                        .catch(() => {
                            // Jika offline dan navigasi, tampilkan offline page
                            if (event.request.mode === 'navigate') {
                                return caches.match(OFFLINE_URL);
                            }

                            // Untuk image, bisa return placeholder
                            if (event.request.destination === 'image') {
                                return new Response(
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect fill="#ddd" width="200" height="200"/></svg>',
                                    { headers: { 'Content-Type': 'image/svg+xml' } }
                                );
                            }
                        });
                })
        );
    }
});

// Push Notification Event
self.addEventListener('push', (event) => {
    console.log('[SW] Push notification received');

    let data = {
        title: 'Sistem Absensi Guru',
        body: 'Anda memiliki pemberitahuan baru',
        icon: '/assets/images/icons/icon-192x192.png',
        badge: '/assets/images/icons/badge-72x72.png',
        tag: 'notification',
        requireInteraction: false,
        data: {
            url: '/'
        }
    };

    if (event.data) {
        try {
            data = { ...data, ...event.data.json() };
        } catch (e) {
            console.error('[SW] Error parsing push data:', e);
        }
    }

    const options = {
        body: data.body,
        icon: data.icon,
        badge: data.badge,
        tag: data.tag,
        requireInteraction: data.requireInteraction,
        data: data.data,
        vibrate: [200, 100, 200],
        actions: [
            {
                action: 'open',
                title: 'Buka'
            },
            {
                action: 'close',
                title: 'Tutup'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// Notification Click Event
self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Notification clicked:', event.action);

    event.notification.close();

    if (event.action === 'open' || !event.action) {
        const urlToOpen = event.notification.data?.url || '/';

        event.waitUntil(
            clients.matchAll({ type: 'window', includeUncontrolled: true })
                .then((clientList) => {
                    // Jika sudah ada window terbuka, fokus ke sana
                    for (let client of clientList) {
                        if (client.url === urlToOpen && 'focus' in client) {
                            return client.focus();
                        }
                    }

                    // Jika belum ada, buka window baru
                    if (clients.openWindow) {
                        return clients.openWindow(urlToOpen);
                    }
                })
        );
    }
});

// Background Sync Event (untuk retry failed requests)
self.addEventListener('sync', (event) => {
    console.log('[SW] Background sync:', event.tag);

    if (event.tag === 'sync-absensi') {
        event.waitUntil(
            // Sync logic - retry failed absensi submissions
            syncAbsensi()
        );
    }
});

// Function untuk sync absensi yang gagal
async function syncAbsensi() {
    try {
        // Ambil pending absensi dari IndexedDB atau local storage
        // Kirim ke server
        console.log('[SW] Syncing pending absensi...');
        
        // Implementation here
        
        return Promise.resolve();
    } catch (error) {
        console.error('[SW] Sync failed:', error);
        return Promise.reject(error);
    }
}

// Message Event - untuk komunikasi dengan client
self.addEventListener('message', (event) => {
    console.log('[SW] Message received:', event.data);

    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'CLEAR_CACHE') {
        event.waitUntil(
            caches.keys().then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => caches.delete(cacheName))
                );
            })
        );
    }

    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: CACHE_NAME });
    }
});
