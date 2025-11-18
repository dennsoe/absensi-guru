/**
 * Geolocation Component
 * Handles GPS location detection and validation
 */

class Geolocation {
    constructor() {
        this.currentPosition = null;
        this.watchId = null;
    }

    /**
     * Get current position once
     * @returns {Promise<Object>} Position object with latitude, longitude, accuracy
     */
    async getCurrentPosition() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Geolocation tidak didukung di browser ini'));
                return;
            }

            const options = {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            };

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const result = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy,
                        altitude: position.coords.altitude,
                        heading: position.coords.heading,
                        speed: position.coords.speed,
                        timestamp: position.timestamp
                    };

                    this.currentPosition = result;
                    resolve(result);
                },
                (error) => {
                    const errorMessage = this.getErrorMessage(error);
                    reject(new Error(errorMessage));
                },
                options
            );
        });
    }

    /**
     * Watch position continuously
     * @param {Function} onSuccess Callback on position update
     * @param {Function} onError Callback on error
     */
    watchPosition(onSuccess, onError) {
        if (!navigator.geolocation) {
            onError(new Error('Geolocation tidak didukung'));
            return;
        }

        const options = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 5000
        };

        this.watchId = navigator.geolocation.watchPosition(
            (position) => {
                const result = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy,
                    timestamp: position.timestamp
                };

                this.currentPosition = result;
                onSuccess(result);
            },
            (error) => {
                const errorMessage = this.getErrorMessage(error);
                onError(new Error(errorMessage));
            },
            options
        );
    }

    /**
     * Stop watching position
     */
    clearWatch() {
        if (this.watchId !== null) {
            navigator.geolocation.clearWatch(this.watchId);
            this.watchId = null;
            console.log('GPS watch cleared');
        }
    }

    /**
     * Calculate distance between two coordinates (Haversine formula)
     * @param {number} lat1 Latitude point 1
     * @param {number} lon1 Longitude point 1
     * @param {number} lat2 Latitude point 2
     * @param {number} lon2 Longitude point 2
     * @returns {number} Distance in meters
     */
    static calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3; // Earth radius in meters
        const φ1 = lat1 * Math.PI / 180;
        const φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;

        const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                Math.cos(φ1) * Math.cos(φ2) *
                Math.sin(Δλ / 2) * Math.sin(Δλ / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        const distance = R * c; // Distance in meters
        return Math.round(distance);
    }

    /**
     * Check if coordinates are within radius
     * @param {number} userLat User latitude
     * @param {number} userLon User longitude
     * @param {number} targetLat Target latitude (e.g., school)
     * @param {number} targetLon Target longitude
     * @param {number} maxRadius Maximum allowed radius in meters
     * @returns {Object} Object with isWithin boolean and distance
     */
    static isWithinRadius(userLat, userLon, targetLat, targetLon, maxRadius) {
        const distance = this.calculateDistance(userLat, userLon, targetLat, targetLon);

        return {
            isWithin: distance <= maxRadius,
            distance: distance,
            maxRadius: maxRadius
        };
    }

    /**
     * Get human-readable error message
     * @param {GeolocationPositionError} error
     * @returns {string}
     */
    getErrorMessage(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                return 'Akses lokasi ditolak. Mohon izinkan akses lokasi di pengaturan browser.';
            case error.POSITION_UNAVAILABLE:
                return 'Informasi lokasi tidak tersedia. Pastikan GPS aktif.';
            case error.TIMEOUT:
                return 'Waktu permintaan lokasi habis. Coba lagi.';
            default:
                return 'Terjadi kesalahan saat mendapatkan lokasi.';
        }
    }

    /**
     * Check if geolocation is supported
     * @returns {boolean}
     */
    static isSupported() {
        return 'geolocation' in navigator;
    }

    /**
     * Request permission for geolocation
     * @returns {Promise<boolean>}
     */
    static async requestPermission() {
        if (!this.isSupported()) {
            return false;
        }

        try {
            const result = await navigator.permissions.query({ name: 'geolocation' });
            return result.state === 'granted' || result.state === 'prompt';
        } catch (err) {
            // Fallback: try to get position directly
            try {
                await new Geolocation().getCurrentPosition();
                return true;
            } catch {
                return false;
            }
        }
    }

    /**
     * Format coordinates for display
     * @param {number} lat Latitude
     * @param {number} lon Longitude
     * @returns {string}
     */
    static formatCoordinates(lat, lon) {
        const latDir = lat >= 0 ? 'N' : 'S';
        const lonDir = lon >= 0 ? 'E' : 'W';

        return `${Math.abs(lat).toFixed(6)}° ${latDir}, ${Math.abs(lon).toFixed(6)}° ${lonDir}`;
    }
}

// Export for global use
window.Geolocation = Geolocation;

export default Geolocation;
