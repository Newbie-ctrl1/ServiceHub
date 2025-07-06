/**
 * Pusher Diagnostics Tool
 * 
 * File ini berisi fungsi-fungsi untuk mendiagnosis dan memperbaiki masalah koneksi Pusher
 * di aplikasi ServiceHub.
 */

class PusherDiagnostics {
    constructor() {
        this.config = window.chatConfig || {};
        this.pusher = null;
        this.diagnosticResults = {};
    }

    /**
     * Menjalankan semua tes diagnostik
     */
    async runAllTests() {
        console.group('🔍 Pusher Diagnostics');
        
        this.checkConfiguration();
        this.checkEnvironment();
        await this.testConnection();
        this.checkSocketUrl();
        this.suggestFixes();
        
        console.groupEnd();
        
        return this.diagnosticResults;
    }

    /**
     * Memeriksa konfigurasi Pusher
     */
    checkConfiguration() {
        console.log('📋 Memeriksa konfigurasi Pusher...');
        
        const results = {
            hasKey: !!this.config.pusherKey,
            hasCluster: !!this.config.pusherCluster,
            validClusterFormat: /^[a-z]{2}[0-9]?$/.test(this.config.pusherCluster),
            clusterValue: this.config.pusherCluster || 'tidak tersedia'
        };
        
        console.table(results);
        
        this.diagnosticResults.configuration = results;
        return results;
    }

    /**
     * Memeriksa lingkungan aplikasi
     */
    checkEnvironment() {
        console.log('🌐 Memeriksa lingkungan aplikasi...');
        
        const results = {
            isSecure: window.location.protocol === 'https:',
            host: window.location.host,
            savedCluster: localStorage.getItem('pusher_successful_cluster') || 'tidak ada'
        };
        
        console.table(results);
        
        this.diagnosticResults.environment = results;
        return results;
    }

    /**
     * Menguji koneksi Pusher
     */
    async testConnection() {
        console.log('🔌 Menguji koneksi Pusher...');
        
        if (!this.config.pusherKey || !this.config.pusherCluster) {
            console.error('❌ Konfigurasi Pusher tidak lengkap, tidak dapat menguji koneksi');
            
            this.diagnosticResults.connection = {
                status: 'failed',
                error: 'Konfigurasi tidak lengkap'
            };
            
            return this.diagnosticResults.connection;
        }
        
        return new Promise(resolve => {
            try {
                // Buat instance Pusher baru khusus untuk tes
                const testPusher = new Pusher(this.config.pusherKey, {
                    cluster: this.config.pusherCluster,
                    encrypted: true
                });
                
                this.pusher = testPusher;
                
                // Set timeout untuk mencegah tes menggantung terlalu lama
                const timeout = setTimeout(() => {
                    console.error('❌ Koneksi timeout setelah 5 detik');
                    
                    this.diagnosticResults.connection = {
                        status: 'timeout',
                        error: 'Koneksi timeout setelah 5 detik'
                    };
                    
                    testPusher.disconnect();
                    resolve(this.diagnosticResults.connection);
                }, 5000);
                
                // Handler untuk koneksi berhasil
                testPusher.connection.bind('connected', () => {
                    clearTimeout(timeout);
                    console.log('✅ Koneksi Pusher berhasil');
                    
                    this.diagnosticResults.connection = {
                        status: 'connected',
                        socketId: testPusher.connection.socket_id,
                        latency: testPusher.connection.latency || 'tidak tersedia'
                    };
                    
                    resolve(this.diagnosticResults.connection);
                });
                
                // Handler untuk error koneksi
                testPusher.connection.bind('error', error => {
                    clearTimeout(timeout);
                    console.error('❌ Error koneksi Pusher:', error);
                    
                    this.diagnosticResults.connection = {
                        status: 'error',
                        error: error.message || 'Unknown error'
                    };
                    
                    resolve(this.diagnosticResults.connection);
                });
                
                // Handler untuk koneksi terputus
                testPusher.connection.bind('disconnected', () => {
                    clearTimeout(timeout);
                    console.log('⚠️ Koneksi Pusher terputus');
                    
                    this.diagnosticResults.connection = {
                        status: 'disconnected'
                    };
                    
                    resolve(this.diagnosticResults.connection);
                });
            } catch (error) {
                console.error('❌ Error saat membuat instance Pusher:', error);
                
                this.diagnosticResults.connection = {
                    status: 'error',
                    error: error.message || 'Unknown error'
                };
                
                resolve(this.diagnosticResults.connection);
            }
        });
    }

    /**
     * Memeriksa URL WebSocket
     */
    checkSocketUrl() {
        console.log('🔗 Memeriksa URL WebSocket...');
        
        const expectedUrl = `wss://ws-${this.config.pusherCluster}.pusher.com`;
        let actualUrl = 'tidak tersedia (koneksi belum dibuat)';
        let urlMatch = false;
        
        if (this.pusher && this.pusher.connection && this.pusher.connection.socket) {
            actualUrl = this.pusher.connection.socket.url || 'tidak tersedia';
            urlMatch = actualUrl.includes(expectedUrl);
        }
        
        const results = {
            expectedUrl,
            actualUrl,
            urlMatch
        };
        
        console.table(results);
        
        this.diagnosticResults.socketUrl = results;
        return results;
    }

    /**
     * Menyarankan perbaikan berdasarkan hasil diagnostik
     */
    suggestFixes() {
        console.log('🔧 Menyarankan perbaikan...');
        
        const suggestions = [];
        
        // Cek konfigurasi
        if (!this.diagnosticResults.configuration.hasKey) {
            suggestions.push('Pastikan PUSHER_APP_KEY diatur dengan benar di variabel lingkungan Railway');
        }
        
        if (!this.diagnosticResults.configuration.hasCluster) {
            suggestions.push('Pastikan PUSHER_APP_CLUSTER diatur dengan benar di variabel lingkungan Railway');
        }
        
        if (!this.diagnosticResults.configuration.validClusterFormat) {
            suggestions.push(`Format cluster "${this.diagnosticResults.configuration.clusterValue}" tidak valid. Gunakan format seperti ap1, eu, us2, dll.`);
        }
        
        // Cek koneksi
        if (this.diagnosticResults.connection) {
            if (this.diagnosticResults.connection.status === 'timeout') {
                suggestions.push('Koneksi timeout. Periksa firewall atau koneksi internet Anda.');
            }
            
            if (this.diagnosticResults.connection.status === 'error') {
                suggestions.push(`Error koneksi: ${this.diagnosticResults.connection.error}. Periksa konfigurasi Pusher Anda.`);
            }
        }
        
        // Cek URL WebSocket
        if (this.diagnosticResults.socketUrl && !this.diagnosticResults.socketUrl.urlMatch) {
            suggestions.push('URL WebSocket tidak sesuai dengan yang diharapkan. Periksa konfigurasi cluster Pusher.');
        }
        
        // Saran umum
        if (suggestions.length === 0) {
            if (this.diagnosticResults.connection && this.diagnosticResults.connection.status === 'connected') {
                suggestions.push('Koneksi Pusher berhasil, tetapi aplikasi mungkin tidak menggunakan koneksi dengan benar. Periksa kode JavaScript aplikasi.');
            } else {
                suggestions.push('Coba hapus cache browser dan muat ulang halaman.');
                suggestions.push('Pastikan semua variabel lingkungan Pusher diatur dengan benar di Railway.');
                suggestions.push('Coba gunakan cluster Pusher alternatif seperti ap1, eu, us2, dll.');
            }
        }
        
        console.log('Saran perbaikan:');
        suggestions.forEach((suggestion, index) => {
            console.log(`${index + 1}. ${suggestion}`);
        });
        
        this.diagnosticResults.suggestions = suggestions;
        return suggestions;
    }

    /**
     * Menampilkan hasil diagnostik di UI
     */
    displayResults() {
        // Cek apakah elemen diagnostik sudah ada
        let diagnosticElement = document.getElementById('pusher-diagnostics');
        
        // Jika belum ada, buat elemen baru
        if (!diagnosticElement) {
            diagnosticElement = document.createElement('div');
            diagnosticElement.id = 'pusher-diagnostics';
            diagnosticElement.style.position = 'fixed';
            diagnosticElement.style.bottom = '20px';
            diagnosticElement.style.right = '20px';
            diagnosticElement.style.width = '400px';
            diagnosticElement.style.maxHeight = '80vh';
            diagnosticElement.style.overflowY = 'auto';
            diagnosticElement.style.backgroundColor = '#fff';
            diagnosticElement.style.border = '1px solid #ddd';
            diagnosticElement.style.borderRadius = '8px';
            diagnosticElement.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            diagnosticElement.style.padding = '16px';
            diagnosticElement.style.zIndex = '9999';
            diagnosticElement.style.fontFamily = 'Arial, sans-serif';
            diagnosticElement.style.fontSize = '14px';
            diagnosticElement.style.color = '#333';
            
            document.body.appendChild(diagnosticElement);
        }
        
        // Buat konten HTML
        let html = `
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="margin: 0; font-size: 18px;">Pusher Diagnostics</h3>
                <button id="close-diagnostics" style="background: none; border: none; cursor: pointer; font-size: 18px;">&times;</button>
            </div>
        `;
        
        // Tambahkan bagian konfigurasi
        html += `
            <div style="margin-bottom: 16px;">
                <h4 style="margin: 0 0 8px 0; font-size: 16px;">Konfigurasi</h4>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 4px 8px; border-bottom: 1px solid #eee;">Pusher Key</td>
                        <td style="padding: 4px 8px; border-bottom: 1px solid #eee;">${this.diagnosticResults.configuration.hasKey ? '✅ Ada' : '❌ Tidak ada'}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 8px; border-bottom: 1px solid #eee;">Pusher Cluster</td>
                        <td style="padding: 4px 8px; border-bottom: 1px solid #eee;">${this.diagnosticResults.configuration.hasCluster ? '✅ Ada' : '❌ Tidak ada'} (${this.diagnosticResults.configuration.clusterValue})</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 8px; border-bottom: 1px solid #eee;">Format Cluster</td>
                        <td style="padding: 4px 8px; border-bottom: 1px solid #eee;">${this.diagnosticResults.configuration.validClusterFormat ? '✅ Valid' : '❌ Tidak valid'}</td>
                    </tr>
                </table>
            </div>
        `;
        
        // Tambahkan bagian koneksi
        if (this.diagnosticResults.connection) {
            const statusIcon = {
                'connected': '✅',
                'disconnected': '⚠️',
                'error': '❌',
                'timeout': '⏱️',
                'failed': '❌'
            }[this.diagnosticResults.connection.status] || '❓';
            
            html += `
                <div style="margin-bottom: 16px;">
                    <h4 style="margin: 0 0 8px 0; font-size: 16px;">Status Koneksi</h4>
                    <div style="padding: 8px; background-color: ${this.diagnosticResults.connection.status === 'connected' ? '#e6f7e6' : '#fff0f0'}; border-radius: 4px; margin-bottom: 8px;">
                        ${statusIcon} ${this.diagnosticResults.connection.status.charAt(0).toUpperCase() + this.diagnosticResults.connection.status.slice(1)}
                        ${this.diagnosticResults.connection.error ? `<div style="margin-top: 4px; font-size: 12px; color: #d32f2f;">${this.diagnosticResults.connection.error}</div>` : ''}
                    </div>
                </div>
            `;
        }
        
        // Tambahkan bagian URL WebSocket
        if (this.diagnosticResults.socketUrl) {
            html += `
                <div style="margin-bottom: 16px;">
                    <h4 style="margin: 0 0 8px 0; font-size: 16px;">URL WebSocket</h4>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 4px 8px; border-bottom: 1px solid #eee;">Expected URL</td>
                            <td style="padding: 4px 8px; border-bottom: 1px solid #eee; word-break: break-all;">${this.diagnosticResults.socketUrl.expectedUrl}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 8px; border-bottom: 1px solid #eee;">Actual URL</td>
                            <td style="padding: 4px 8px; border-bottom: 1px solid #eee; word-break: break-all;">${this.diagnosticResults.socketUrl.actualUrl}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 8px; border-bottom: 1px solid #eee;">URL Match</td>
                            <td style="padding: 4px 8px; border-bottom: 1px solid #eee;">${this.diagnosticResults.socketUrl.urlMatch ? '✅ Ya' : '❌ Tidak'}</td>
                        </tr>
                    </table>
                </div>
            `;
        }
        
        // Tambahkan bagian saran
        if (this.diagnosticResults.suggestions && this.diagnosticResults.suggestions.length > 0) {
            html += `
                <div>
                    <h4 style="margin: 0 0 8px 0; font-size: 16px;">Saran Perbaikan</h4>
                    <ul style="margin: 0; padding-left: 20px;">
                        ${this.diagnosticResults.suggestions.map(suggestion => `<li style="margin-bottom: 4px;">${suggestion}</li>`).join('')}
                    </ul>
                </div>
            `;
        }
        
        // Tambahkan tombol untuk menjalankan tes ulang
        html += `
            <div style="margin-top: 16px; text-align: center;">
                <button id="rerun-diagnostics" style="background-color: #4CAF50; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Jalankan Tes Ulang</button>
            </div>
        `;
        
        // Atur konten HTML
        diagnosticElement.innerHTML = html;
        
        // Tambahkan event listener untuk tombol tutup
        document.getElementById('close-diagnostics').addEventListener('click', () => {
            diagnosticElement.remove();
        });
        
        // Tambahkan event listener untuk tombol tes ulang
        document.getElementById('rerun-diagnostics').addEventListener('click', async () => {
            await this.runAllTests();
            this.displayResults();
        });
    }

    /**
     * Mencoba koneksi dengan cluster alternatif
     */
    async tryAlternativeClusters() {
        const commonClusters = ['ap1', 'ap2', 'us1', 'us2', 'eu', 'mt1'];
        const results = [];
        
        console.group('🔄 Mencoba cluster alternatif...');
        
        for (const cluster of commonClusters) {
            if (cluster === this.config.pusherCluster) continue;
            
            console.log(`Mencoba cluster: ${cluster}`);
            
            const result = await this.testCluster(cluster);
            results.push({
                cluster,
                ...result
            });
            
            if (result.status === 'connected') {
                console.log(`✅ Berhasil terhubung dengan cluster: ${cluster}`);
                localStorage.setItem('pusher_successful_cluster', cluster);
                break;
            }
        }
        
        console.groupEnd();
        
        return results;
    }

    /**
     * Menguji koneksi dengan cluster tertentu
     */
    async testCluster(cluster) {
        return new Promise(resolve => {
            try {
                const testPusher = new Pusher(this.config.pusherKey, {
                    cluster: cluster,
                    encrypted: true
                });
                
                const timeout = setTimeout(() => {
                    testPusher.disconnect();
                    resolve({
                        status: 'timeout',
                        error: 'Connection timeout'
                    });
                }, 5000);
                
                testPusher.connection.bind('connected', () => {
                    clearTimeout(timeout);
                    testPusher.disconnect();
                    resolve({
                        status: 'connected',
                        socketId: testPusher.connection.socket_id
                    });
                });
                
                testPusher.connection.bind('error', error => {
                    clearTimeout(timeout);
                    testPusher.disconnect();
                    resolve({
                        status: 'error',
                        error: error.message || 'Unknown error'
                    });
                });
            } catch (error) {
                resolve({
                    status: 'error',
                    error: error.message || 'Unknown error'
                });
            }
        });
    }
}

// Fungsi untuk menjalankan diagnostik
function runPusherDiagnostics() {
    const diagnostics = new PusherDiagnostics();
    diagnostics.runAllTests().then(() => {
        diagnostics.displayResults();
    });
}

// Tambahkan tombol diagnostik ke halaman
function addDiagnosticsButton() {
    const button = document.createElement('button');
    button.id = 'run-pusher-diagnostics';
    button.innerHTML = '🔍 Diagnosa Pusher';
    button.style.position = 'fixed';
    button.style.bottom = '20px';
    button.style.right = '20px';
    button.style.backgroundColor = '#4CAF50';
    button.style.color = 'white';
    button.style.border = 'none';
    button.style.borderRadius = '4px';
    button.style.padding = '8px 16px';
    button.style.cursor = 'pointer';
    button.style.zIndex = '9998';
    button.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
    
    button.addEventListener('click', runPusherDiagnostics);
    
    document.body.appendChild(button);
}

// Jalankan saat dokumen dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Tambahkan tombol diagnostik setelah 2 detik
    setTimeout(addDiagnosticsButton, 2000);
});