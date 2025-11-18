/**
 * Camera Component for Selfie Capture
 * Handles webcam access and photo capture
 */

class CameraCapture {
    constructor(videoElementId, canvasElementId) {
        this.videoElement = document.getElementById(videoElementId);
        this.canvasElement = document.getElementById(canvasElementId);
        this.stream = null;
        this.isActive = false;
    }

    /**
     * Start camera stream
     */
    async start() {
        if (this.isActive) {
            console.warn('Camera already active');
            return;
        }

        try {
            // Request camera access with constraints
            const constraints = {
                video: {
                    facingMode: 'user', // Front camera for selfie
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            };

            this.stream = await navigator.mediaDevices.getUserMedia(constraints);

            if (this.videoElement) {
                this.videoElement.srcObject = this.stream;
                await this.videoElement.play();
                this.isActive = true;
                console.log('Camera started');
            }

            return true;
        } catch (err) {
            console.error('Failed to start camera:', err);

            let errorMessage = 'Gagal mengakses kamera';
            if (err.name === 'NotAllowedError') {
                errorMessage = 'Akses kamera ditolak. Mohon izinkan akses kamera di pengaturan browser.';
            } else if (err.name === 'NotFoundError') {
                errorMessage = 'Kamera tidak ditemukan pada perangkat ini.';
            } else if (err.name === 'NotReadableError') {
                errorMessage = 'Kamera sedang digunakan oleh aplikasi lain.';
            }

            throw new Error(errorMessage);
        }
    }

    /**
     * Stop camera stream
     */
    stop() {
        if (!this.isActive) {
            return;
        }

        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }

        if (this.videoElement) {
            this.videoElement.srcObject = null;
        }

        this.isActive = false;
        console.log('Camera stopped');
    }

    /**
     * Capture photo from video stream
     * @returns {string} Base64 encoded image data URL
     */
    capture() {
        if (!this.isActive || !this.videoElement || !this.canvasElement) {
            throw new Error('Camera not active or elements not found');
        }

        const context = this.canvasElement.getContext('2d');

        // Set canvas size to match video
        this.canvasElement.width = this.videoElement.videoWidth;
        this.canvasElement.height = this.videoElement.videoHeight;

        // Draw current video frame to canvas
        context.drawImage(this.videoElement, 0, 0);

        // Convert to base64 image
        const imageDataUrl = this.canvasElement.toDataURL('image/jpeg', 0.85);

        console.log('Photo captured');
        return imageDataUrl;
    }

    /**
     * Capture and return as Blob for upload
     * @returns {Promise<Blob>}
     */
    async captureBlob() {
        if (!this.isActive || !this.videoElement || !this.canvasElement) {
            throw new Error('Camera not active or elements not found');
        }

        const context = this.canvasElement.getContext('2d');

        this.canvasElement.width = this.videoElement.videoWidth;
        this.canvasElement.height = this.videoElement.videoHeight;

        context.drawImage(this.videoElement, 0, 0);

        return new Promise((resolve) => {
            this.canvasElement.toBlob((blob) => {
                resolve(blob);
            }, 'image/jpeg', 0.85);
        });
    }

    /**
     * Check if camera is supported
     */
    static async isSupported() {
        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const hasCamera = devices.some(device => device.kind === 'videoinput');
            return hasCamera && !!navigator.mediaDevices.getUserMedia;
        } catch (err) {
            return false;
        }
    }

    /**
     * Get available cameras
     */
    static async getCameras() {
        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            return devices.filter(device => device.kind === 'videoinput');
        } catch (err) {
            console.error('Failed to get cameras:', err);
            return [];
        }
    }

    /**
     * Switch between front and back camera
     */
    async switchCamera(facingMode = 'user') {
        this.stop();

        const constraints = {
            video: {
                facingMode: facingMode,
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        };

        this.stream = await navigator.mediaDevices.getUserMedia(constraints);

        if (this.videoElement) {
            this.videoElement.srcObject = this.stream;
            await this.videoElement.play();
            this.isActive = true;
        }
    }
}

// Export for global use
window.CameraCapture = CameraCapture;

export default CameraCapture;
