/**
 * PWA JavaScript - Install Prompt, Push Notifications, Update Handler
 */

let deferredPrompt;
let isStandalone = false;

// Check if running as PWA
if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) {
    isStandalone = true;
    document.body.classList.add('pwa-standalone');
}

// ==================== SERVICE WORKER REGISTRATION ====================
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                console.log('[PWA] Service Worker registered:', registration.scope);
                
                // Check for updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            // Ada update tersedia
                            showUpdateNotification();
                        }
                    });
                });
            })
            .catch((error) => {
                console.error('[PWA] Service Worker registration failed:', error);
            });
        
        // Check for updates setiap 1 jam
        setInterval(() => {
            navigator.serviceWorker.getRegistration().then((registration) => {
                if (registration) {
                    registration.update();
                }
            });
        }, 3600000); // 1 hour
    });
}

// ==================== INSTALL PROMPT ====================
window.addEventListener('beforeinstallprompt', (e) => {
    console.log('[PWA] Install prompt available');
    
    // Prevent default prompt
    e.preventDefault();
    
    // Simpan event untuk trigger nanti
    deferredPrompt = e;
    
    // Tampilkan install banner
    showInstallBanner();
});

// Function untuk show install banner
function showInstallBanner() {
    const banner = document.getElementById('install-banner');
    if (banner) {
        banner.style.display = 'block';
    }
}

// Function untuk install PWA
async function installPWA() {
    if (!deferredPrompt) {
        console.log('[PWA] Install prompt not available');
        return;
    }
    
    // Show install prompt
    deferredPrompt.prompt();
    
    // Wait for user response
    const { outcome } = await deferredPrompt.userChoice;
    console.log(`[PWA] User response: ${outcome}`);
    
    if (outcome === 'accepted') {
        console.log('[PWA] App installed');
        hideInstallBanner();
    }
    
    // Clear prompt
    deferredPrompt = null;
}

// Hide install banner
function hideInstallBanner() {
    const banner = document.getElementById('install-banner');
    if (banner) {
        banner.style.display = 'none';
    }
}

// Detect when app is installed
window.addEventListener('appinstalled', () => {
    console.log('[PWA] App installed successfully');
    hideInstallBanner();
    
    // Track installation (optional analytics)
    if (typeof gtag !== 'undefined') {
        gtag('event', 'app_installed', {
            'event_category': 'PWA'
        });
    }
});

// ==================== IOS ADD TO HOME SCREEN ====================
function isIOS() {
    return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
}

function isInStandaloneMode() {
    return ('standalone' in window.navigator) && (window.navigator.standalone);
}

// Show iOS install instructions
if (isIOS() && !isInStandaloneMode()) {
    // Tampilkan banner khusus iOS
    setTimeout(() => {
        const iosPrompt = document.getElementById('ios-install-prompt');
        if (iosPrompt) {
            iosPrompt.style.display = 'block';
        }
    }, 3000);
}

// ==================== PUSH NOTIFICATIONS ====================
async function requestNotificationPermission() {
    if (!('Notification' in window)) {
        console.log('[PWA] Browser tidak support notifikasi');
        return false;
    }
    
    if (Notification.permission === 'granted') {
        console.log('[PWA] Notifikasi sudah diizinkan');
        await subscribeToPushNotifications();
        return true;
    }
    
    if (Notification.permission !== 'denied') {
        const permission = await Notification.requestPermission();
        
        if (permission === 'granted') {
            console.log('[PWA] Notifikasi diizinkan');
            await subscribeToPushNotifications();
            return true;
        }
    }
    
    console.log('[PWA] Notifikasi ditolak');
    return false;
}

// Subscribe to push notifications
async function subscribeToPushNotifications() {
    try {
        const registration = await navigator.serviceWorker.ready;
        
        // Check if already subscribed
        let subscription = await registration.pushManager.getSubscription();
        
        if (!subscription) {
            // Subscribe to push
            const vapidPublicKey = document.querySelector('meta[name="vapid-public-key"]')?.content;
            
            if (!vapidPublicKey) {
                console.error('[PWA] VAPID public key not found');
                return;
            }
            
            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidPublicKey)
            });
            
            console.log('[PWA] Push subscription:', subscription);
            
            // Send subscription to server
            await sendSubscriptionToServer(subscription);
        }
        
        return subscription;
    } catch (error) {
        console.error('[PWA] Push subscription failed:', error);
    }
}

// Send subscription to server
async function sendSubscriptionToServer(subscription) {
    try {
        const response = await fetch('/api/push/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(subscription)
        });
        
        if (response.ok) {
            console.log('[PWA] Subscription sent to server');
        }
    } catch (error) {
        console.error('[PWA] Failed to send subscription:', error);
    }
}

// Helper function untuk convert VAPID key
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');
    
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    
    return outputArray;
}

// ==================== UPDATE NOTIFICATION ====================
function showUpdateNotification() {
    if (confirm('Versi baru aplikasi tersedia! Muat ulang untuk update?')) {
        // Tell service worker to skip waiting
        navigator.serviceWorker.controller.postMessage({ type: 'SKIP_WAITING' });
        
        // Reload page
        window.location.reload();
    }
}

// ==================== ONLINE/OFFLINE STATUS ====================
window.addEventListener('online', () => {
    console.log('[PWA] Online');
    showOnlineStatus();
    
    // Sync data saat online kembali
    syncOfflineData();
});

window.addEventListener('offline', () => {
    console.log('[PWA] Offline');
    showOfflineStatus();
});

function showOnlineStatus() {
    const statusBar = document.getElementById('connection-status');
    if (statusBar) {
        statusBar.textContent = 'Online';
        statusBar.className = 'status-online';
        
        setTimeout(() => {
            statusBar.style.display = 'none';
        }, 3000);
    }
}

function showOfflineStatus() {
    const statusBar = document.getElementById('connection-status');
    if (statusBar) {
        statusBar.textContent = 'Anda sedang offline';
        statusBar.className = 'status-offline';
        statusBar.style.display = 'block';
    }
}

// Sync offline data
async function syncOfflineData() {
    if ('serviceWorker' in navigator && 'SyncManager' in window) {
        try {
            const registration = await navigator.serviceWorker.ready;
            await registration.sync.register('sync-absensi');
            console.log('[PWA] Background sync registered');
        } catch (error) {
            console.error('[PWA] Background sync failed:', error);
        }
    }
}

// ==================== APP BADGE (untuk notification count) ====================
async function updateAppBadge(count) {
    if ('setAppBadge' in navigator) {
        try {
            if (count > 0) {
                await navigator.setAppBadge(count);
            } else {
                await navigator.clearAppBadge();
            }
        } catch (error) {
            console.error('[PWA] Badge update failed:', error);
        }
    }
}

// ==================== EXPOSE FUNCTIONS ====================
window.PWA = {
    install: installPWA,
    requestNotificationPermission,
    updateAppBadge,
    isStandalone
};

// Auto-request notification jika belum
if (isStandalone && Notification.permission === 'default') {
    setTimeout(() => {
        requestNotificationPermission();
    }, 5000); // Tunggu 5 detik setelah app dibuka
}

console.log('[PWA] PWA Module loaded');
