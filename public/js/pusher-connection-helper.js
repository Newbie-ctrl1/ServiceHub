/**
 * Pusher Connection Helper
 * 
 * File ini berisi fungsi-fungsi untuk membantu mengatasi masalah koneksi Pusher
 * dengan menambahkan logika fallback dan retry yang lebih kuat.
 */

class PusherConnectionHelper {
    constructor() {
        this.config = window.chatConfig || {};
        this.pusher = null;
        this.channel = null;
        this.isConnected = false;
        this.retryCount = 0;
        this.maxRetries = 5;
        this.retryDelay = 2000; // 2 detik
        this.successCallback = null;
        this.errorCallback = null;
        this.messageCallback = null;
        this.commonClusters = ['ap1', 'ap2', 'us1', 'us2', 'eu', 'mt1'];
        this.triedClusters = [];
        this.currentCluster = this.config.pusherCluster;
        
        // Coba ambil cluster yang berhasil sebelumnya dari localStorage
        const savedCluster = localStorage.getItem('pusher_successful_cluster');
        if (savedCluster) {
            console.log(`[PusherHelper] Menggunakan cluster yang berhasil sebelumnya: ${savedCluster}`);
            this.currentCluster = savedCluster;
        }
    }

    /**
     * Inisialisasi koneksi Pusher dengan callback
     * 
     * @param {Function} successCallback - Callback saat koneksi berhasil
     * @param {Function} errorCallback - Callback saat koneksi gagal
     * @param {Function} messageCallback - Callback saat menerima pesan
     */
    init(successCallback, errorCallback, messageCallback) {
        this.successCallback = successCallback;
        this.errorCallback = errorCallback;
        this.messageCallback = messageCallback;
        
        // Validasi konfigurasi
        if (!this.validateConfig()) {
            if (this.errorCallback) {
                this.errorCallback('Konfigurasi Pusher tidak valid');
            }
            return false;
        }
        
        // Coba koneksi
        this.connect();
        return true;
    }

    /**
     * Validasi konfigurasi Pusher
     */
    validateConfig() {
        if (!this.config) {
            console.error('[PusherHelper] Konfigurasi chat tidak tersedia');
            return false;
        }
        
        if (!this.config.pusherKey) {
            console.error('[PusherHelper] Pusher key tidak tersedia');
            return false;
        }
        
        if (!this.currentCluster) {
            console.error('[PusherHelper] Pusher cluster tidak tersedia');
            return false;
        }
        
        return true;
    }

    /**
     * Mencoba koneksi Pusher dengan cluster saat ini
     */
    connect() {
        try {
            console.log(`[PusherHelper] Mencoba koneksi dengan cluster: ${this.currentCluster}`);
            
            // Tambahkan ke daftar cluster yang sudah dicoba
            if (!this.triedClusters.includes(this.currentCluster)) {
                this.triedClusters.push(this.currentCluster);
            }
            
            // Buat instance Pusher baru
            this.pusher = new Pusher(this.config.pusherKey, {
                cluster: this.currentCluster,
                encrypted: true,
                enabledTransports: ['ws', 'wss'],
                disabledTransports: [],
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }
            });
            
            // Binding event koneksi
            this.pusher.connection.bind('connected', () => {
                console.log(`[PusherHelper] Koneksi berhasil dengan cluster: ${this.currentCluster}`);
                this.isConnected = true;
                this.retryCount = 0;
                
                // Simpan cluster yang berhasil ke localStorage
                localStorage.setItem('pusher_successful_cluster', this.currentCluster);
                
                // Subscribe ke channel
                this.subscribeToChannel();
                
                // Panggil callback sukses
                if (this.successCallback) {
                    this.successCallback(this.currentCluster);
                }
            });
            
            this.pusher.connection.bind('error', (error) => {
                console.error(`[PusherHelper] Error koneksi dengan cluster ${this.currentCluster}:`, error);
                this.isConnected = false;
                
                // Coba cluster lain atau retry
                this.handleConnectionError();
            });
            
            this.pusher.connection.bind('disconnected', () => {
                console.log(`[PusherHelper] Koneksi terputus dari cluster: ${this.currentCluster}`);
                this.isConnected = false;
                
                // Coba reconnect
                this.handleDisconnect();
            });
            
            // Set timeout untuk mencegah hanging
            setTimeout(() => {
                if (!this.isConnected) {
                    console.log(`[PusherHelper] Koneksi timeout dengan cluster: ${this.currentCluster}`);
                    this.handleConnectionError();
                }
            }, 5000);
            
        } catch (error) {
            console.error('[PusherHelper] Error saat membuat instance Pusher:', error);
            this.handleConnectionError();
        }
    }

    /**
     * Subscribe ke channel chat
     */
    subscribeToChannel() {
        if (!this.pusher || !this.config.userId) return;
        
        try {
            this.channel = this.pusher.subscribe(`chat.${this.config.userId}`);
            
            this.channel.bind('pusher:subscription_succeeded', () => {
                console.log('[PusherHelper] Berhasil subscribe ke channel chat');
            });
            
            this.channel.bind('pusher:subscription_error', (error) => {
                console.error('[PusherHelper] Error saat subscribe ke channel:', error);
            });
            
            this.channel.bind('message.sent', (data) => {
                console.log('[PusherHelper] Menerima pesan:', data);
                if (this.messageCallback) {
                    this.messageCallback(data);
                }
            });
        } catch (error) {
            console.error('[PusherHelper] Error saat subscribe ke channel:', error);
        }
    }

    /**
     * Menangani error koneksi
     */
    handleConnectionError() {
        // Coba cluster lain jika masih ada yang belum dicoba
        const nextCluster = this.getNextCluster();
        
        if (nextCluster) {
            console.log(`[PusherHelper] Mencoba cluster alternatif: ${nextCluster}`);
            this.currentCluster = nextCluster;
            
            // Disconnect instance Pusher saat ini jika ada
            if (this.pusher) {
                this.pusher.disconnect();
            }
            
            // Coba koneksi dengan cluster baru setelah delay
            setTimeout(() => {
                this.connect();
            }, this.retryDelay);
        } else if (this.retryCount < this.maxRetries) {
            // Jika semua cluster sudah dicoba, coba retry dengan cluster awal
            this.retryCount++;
            console.log(`[PusherHelper] Mencoba ulang koneksi (${this.retryCount}/${this.maxRetries})`);
            
            // Reset daftar cluster yang sudah dicoba
            this.triedClusters = [];
            
            // Kembali ke cluster awal
            this.currentCluster = this.config.pusherCluster;
            
            // Disconnect instance Pusher saat ini jika ada
            if (this.pusher) {
                this.pusher.disconnect();
            }
            
            // Coba koneksi lagi setelah delay
            setTimeout(() => {
                this.connect();
            }, this.retryDelay * this.retryCount);
        } else {
            // Jika semua upaya gagal, panggil callback error
            console.error('[PusherHelper] Semua upaya koneksi gagal');
            if (this.errorCallback) {
                this.errorCallback('Tidak dapat terhubung ke Pusher setelah beberapa percobaan');
            }
        }
    }

    /**
     * Menangani disconnect
     */
    handleDisconnect() {
        if (this.retryCount < this.maxRetries) {
            this.retryCount++;
            console.log(`[PusherHelper] Mencoba reconnect (${this.retryCount}/${this.maxRetries})`);
            
            // Coba koneksi lagi setelah delay
            setTimeout(() => {
                // Disconnect instance Pusher saat ini jika ada
                if (this.pusher) {
                    this.pusher.disconnect();
                }
                
                this.connect();
            }, this.retryDelay * this.retryCount);
        } else {
            // Jika semua upaya gagal, panggil callback error
            console.error('[PusherHelper] Semua upaya reconnect gagal');
            if (this.errorCallback) {
                this.errorCallback('Koneksi terputus dan tidak dapat reconnect');
            }
        }
    }

    /**
     * Mendapatkan cluster berikutnya yang belum dicoba
     */
    getNextCluster() {
        for (const cluster of this.commonClusters) {
            if (!this.triedClusters.includes(cluster)) {
                return cluster;
            }
        }
        return null; // Semua cluster sudah dicoba
    }

    /**
     * Disconnect dari Pusher
     */
    disconnect() {
        if (this.pusher) {
            this.pusher.disconnect();
            this.isConnected = false;
            console.log('[PusherHelper] Disconnected from Pusher');
        }
    }

    /**
     * Mendapatkan status koneksi saat ini
     */
    getConnectionStatus() {
        if (!this.pusher) return 'disconnected';
        return this.pusher.connection.state;
    }

    /**
     * Mendapatkan URL WebSocket saat ini
     */
    getWebSocketUrl() {
        if (!this.pusher || !this.pusher.connection || !this.pusher.connection.socket) {
            return 'tidak tersedia (koneksi belum dibuat)';
        }
        return this.pusher.connection.socket.url || 'tidak tersedia';
    }

    /**
     * Mendapatkan instance Pusher
     */
    getPusher() {
        return this.pusher;
    }

    /**
     * Mendapatkan channel
     */
    getChannel() {
        return this.channel;
    }
}

// Buat instance global
window.pusherHelper = new PusherConnectionHelper();

// Fungsi untuk menginisialisasi koneksi Pusher
function initializePusherConnection(successCallback, errorCallback, messageCallback) {
    return window.pusherHelper.init(successCallback, errorCallback, messageCallback);
}

// Fungsi untuk mendapatkan status koneksi
function getPusherConnectionStatus() {
    return window.pusherHelper.getConnectionStatus();
}

// Fungsi untuk mendapatkan URL WebSocket
function getPusherWebSocketUrl() {
    return window.pusherHelper.getWebSocketUrl();
}

// Fungsi untuk mendapatkan instance Pusher
function getPusherInstance() {
    return window.pusherHelper.getPusher();
}

// Fungsi untuk mendapatkan channel
function getPusherChannel() {
    return window.pusherHelper.getChannel();
}

// Fungsi untuk disconnect
function disconnectPusher() {
    window.pusherHelper.disconnect();
}

// Export fungsi-fungsi untuk digunakan di file lain
window.initializePusherConnection = initializePusherConnection;
window.getPusherConnectionStatus = getPusherConnectionStatus;
window.getPusherWebSocketUrl = getPusherWebSocketUrl;
window.getPusherInstance = getPusherInstance;
window.getPusherChannel = getPusherChannel;
window.disconnectPusher = disconnectPusher;