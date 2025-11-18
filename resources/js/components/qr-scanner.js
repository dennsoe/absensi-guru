/**
 * QR Code Scanner Component
 * Using html5-qrcode library
 */

import { Html5Qrcode } from 'html5-qrcode';

class QRScanner {
    constructor(elementId, onScanSuccess, onScanFailure = null) {
        this.elementId = elementId;
        this.onScanSuccess = onScanSuccess;
        this.onScanFailure = onScanFailure || ((error) => console.warn('QR Scan failed:', error));
        this.html5QrCode = null;
        this.isScanning = false;
    }

    /**
     * Start QR Code scanning
     */
    async start() {
        if (this.isScanning) {
            console.warn('Scanner already running');
            return;
        }

        try {
            this.html5QrCode = new Html5Qrcode(this.elementId);

            const config = {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            await this.html5QrCode.start(
                { facingMode: "environment" }, // Prefer back camera
                config,
                (decodedText, decodedResult) => {
                    this.onScanSuccess(decodedText, decodedResult);
                },
                (errorMessage) => {
                    // Scan failure callback (optional)
                    // This will be called on every frame without QR code
                }
            );

            this.isScanning = true;
            console.log('QR Scanner started');
        } catch (err) {
            console.error('Failed to start scanner:', err);
            this.onScanFailure(err);
            throw err;
        }
    }

    /**
     * Stop QR Code scanning
     */
    async stop() {
        if (!this.isScanning || !this.html5QrCode) {
            return;
        }

        try {
            await this.html5QrCode.stop();
            this.isScanning = false;
            console.log('QR Scanner stopped');
        } catch (err) {
            console.error('Failed to stop scanner:', err);
        }
    }

    /**
     * Get available cameras
     */
    static async getCameras() {
        try {
            const devices = await Html5Qrcode.getCameras();
            return devices;
        } catch (err) {
            console.error('Failed to get cameras:', err);
            return [];
        }
    }

    /**
     * Check if camera is supported
     */
    static async isSupported() {
        try {
            await navigator.mediaDevices.getUserMedia({ video: true });
            return true;
        } catch (err) {
            return false;
        }
    }
}

// Export for global use
window.QRScanner = QRScanner;

export default QRScanner;
