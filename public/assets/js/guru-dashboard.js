/**
 * Guru Dashboard JavaScript
 * Handle alert notifications and auto-refresh
 */

document.addEventListener('DOMContentLoaded', function() {

    // NONAKTIFKAN Bootstrap Alert Plugin pada alert-permanent
    const permanentAlerts = document.querySelectorAll('.alert-permanent');
    permanentAlerts.forEach(function(alert) {

        // Hapus semua atribut data-bs-dismiss
        const dismissElements = alert.querySelectorAll('[data-bs-dismiss="alert"]');
        dismissElements.forEach(function(el) {
            el.removeAttribute('data-bs-dismiss');
        });

        // Remove close button if exists
        const closeBtn = alert.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.remove();
        }

        // Destroy Bootstrap Alert instance jika ada
        if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
            const bsAlert = bootstrap.Alert.getInstance(alert);
            if (bsAlert) {
                bsAlert.dispose();
            }
        }

        // Blokir event Bootstrap Alert
        alert.addEventListener('close.bs.alert', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });

        alert.addEventListener('closed.bs.alert', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        });

        // Prevent dismiss on click
        alert.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-close')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
    });

    // Auto-refresh untuk update status absensi
    // Refresh setiap 2 menit jika ada alert permanent (belum absen)
    if (document.querySelector('.alert-danger.alert-permanent')) {
        setInterval(function() {
            // Reload page untuk update status
            location.reload();
        }, 120000); // 2 menit
    }

    // Refresh setiap 1 menit jika ada jadwal upcoming (akan dimulai)
    if (document.querySelector('.alert-warning.alert-permanent')) {
        setInterval(function() {
            // Reload page untuk update status
            location.reload();
        }, 60000); // 1 menit
    }

    // Notification sound (optional)
    // Uncomment if you want notification sound
    /*
    const playNotificationSound = function() {
        const audio = new Audio('/assets/sounds/notification.mp3');
        audio.play().catch(e => console.log('Audio play failed:', e));
    };

    // Play sound for urgent alert
    if (document.querySelector('.alert-danger.alert-permanent')) {
        playNotificationSound();
    }
    */
});
