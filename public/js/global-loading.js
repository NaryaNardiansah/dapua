/**
 * Global Loading Screen Manager
 * Handles loading screens across the entire application
 */

class GlobalLoadingManager {
    constructor() {
        this.activeLoaders = new Set();
        this.defaultDelay = 300; // Minimum time to show loader
        this.init();
    }

    init() {
        // Handle page transitions
        this.handlePageTransitions();
        
        // Handle AJAX requests
        this.handleAjaxRequests();
        
        // Handle form submissions
        this.handleFormSubmissions();
        
        // Handle fetch requests
        this.handleFetchRequests();
        
        // Handle page visibility changes
        this.handlePageVisibility();
        
        // Handle window focus/blur
        this.handleWindowFocus();
        
        // Auto-hide loading on page load
        this.autoHideOnLoad();
    }

    // Show loading screen with custom text
    show(title = 'Dapur Sakura', subtitle = 'Loading...', type = 'luxury') {
        const loaderId = `${type}-${Date.now()}`;
        
        if (type === 'luxury' && window.loadingScreen) {
            window.loadingScreen.show(title, subtitle);
        } else if (type === 'simple' && window.simpleLoadingScreen) {
            window.simpleLoadingScreen.show(subtitle);
        }
        
        this.activeLoaders.add(loaderId);
        return loaderId;
    }

    // Hide loading screen
    hide(loaderId = null, delay = 0) {
        setTimeout(() => {
            if (loaderId && this.activeLoaders.has(loaderId)) {
                this.activeLoaders.delete(loaderId);
            }
            
            if (this.activeLoaders.size === 0) {
                if (window.loadingScreen) {
                    window.loadingScreen.hide();
                }
                if (window.simpleLoadingScreen) {
                    window.simpleLoadingScreen.hide();
                }
            }
        }, delay);
    }

    // Handle page transitions
    handlePageTransitions() {
        // Handle link clicks
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && link.href && !link.href.startsWith('javascript:') && !link.href.startsWith('#')) {
                const currentDomain = window.location.origin;
                if (link.href.startsWith(currentDomain) || link.href.startsWith('/')) {
                    this.show('Dapur Sakura', 'Navigating...');
                }
            }
        });

        // Handle back/forward navigation
        window.addEventListener('popstate', () => {
            this.show('Dapur Sakura', 'Loading...');
        });
    }

    // Handle AJAX requests
    handleAjaxRequests() {
        // jQuery AJAX
        if (window.jQuery) {
            $(document).ajaxStart(() => {
                this.show('Dapur Sakura', 'Processing...');
            });

            $(document).ajaxComplete(() => {
                this.hide(null, this.defaultDelay);
            });
        }

        // Native AJAX
        const originalXHR = window.XMLHttpRequest;
        const self = this;
        
        window.XMLHttpRequest = function() {
            const xhr = new originalXHR();
            const originalOpen = xhr.open;
            const originalSend = xhr.send;
            
            xhr.open = function(method, url, async, user, password) {
                this._url = url;
                return originalOpen.apply(this, arguments);
            };
            
            xhr.send = function(data) {
                if (this._url && !this._url.includes('refresh-sales')) {
                    self.show('Dapur Sakura', 'Processing...');
                }
                
                this.addEventListener('loadend', () => {
                    self.hide(null, self.defaultDelay);
                });
                
                return originalSend.apply(this, arguments);
            };
            
            return xhr;
        };
    }

    // Handle form submissions
    handleFormSubmissions() {
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.tagName === 'FORM' && !form.classList.contains('no-loading')) {
                this.show('Dapur Sakura', 'Processing...');
            }
        });
    }

    // Handle fetch requests
    handleFetchRequests() {
        const originalFetch = window.fetch;
        const self = this;
        
        window.fetch = async function(...args) {
            const url = args[0];
            
            // Don't show loader for certain requests
            if (typeof url === 'string' && (
                url.includes('refresh-sales') ||
                url.includes('tracking') ||
                url.includes('chat')
            )) {
                return originalFetch.apply(this, args);
            }
            
            self.show('Dapur Sakura', 'Loading...');
            
            try {
                const response = await originalFetch.apply(this, args);
                self.hide(null, self.defaultDelay);
                return response;
            } catch (error) {
                self.hide(null, self.defaultDelay);
                throw error;
            }
        };
    }

    // Show loading for specific actions
    showForAction(action, customText = null) {
        const actionTexts = {
            'save': 'Menyimpan...',
            'delete': 'Menghapus...',
            'update': 'Memperbarui...',
            'create': 'Membuat...',
            'search': 'Mencari...',
            'filter': 'Memfilter...',
            'export': 'Mengekspor...',
            'import': 'Mengimpor...',
            'upload': 'Mengunggah...',
            'download': 'Mengunduh...',
            'refresh': 'Memperbarui...',
            'sync': 'Menyinkronkan...',
            'validate': 'Memvalidasi...',
            'process': 'Memproses...',
            'calculate': 'Menghitung...',
            'generate': 'Membuat...',
            'analyze': 'Menganalisis...',
            'backup': 'Mencadangkan...',
            'restore': 'Memulihkan...',
            'login': 'Masuk...',
            'logout': 'Keluar...',
            'register': 'Mendaftar...',
            'reset': 'Mereset...',
            'verify': 'Memverifikasi...',
            'send': 'Mengirim...',
            'receive': 'Menerima...',
            'connect': 'Menghubungkan...',
            'disconnect': 'Memutuskan...',
            'sync': 'Menyinkronkan...',
            'backup': 'Mencadangkan...',
            'restore': 'Memulihkan...'
        };

        const text = customText || actionTexts[action] || 'Memproses...';
        return this.show('Dapur Sakura', text);
    }

    // Show loading with progress
    showWithProgress(title, subtitle, progress = 0) {
        const loaderId = this.show(title, subtitle);
        
        // Update progress if progress bar exists
        const progressBar = document.querySelector('.progress-bar');
        if (progressBar) {
            progressBar.style.width = `${progress}%`;
        }
        
        return loaderId;
    }

    // Show loading with custom duration
    showTemporary(title, subtitle, duration = 2000) {
        const loaderId = this.show(title, subtitle);
        setTimeout(() => {
            this.hide(loaderId);
        }, duration);
        return loaderId;
    }

    // Handle page visibility changes
    handlePageVisibility() {
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                // Page is visible again, hide any loading screens
                setTimeout(() => {
                    this.hideAll();
                }, 500);
            }
        });
    }

    // Handle window focus/blur
    handleWindowFocus() {
        window.addEventListener('focus', () => {
            // Window is focused, hide any loading screens
            setTimeout(() => {
                this.hideAll();
            }, 500);
        });

        window.addEventListener('blur', () => {
            // Window is blurred, this might be due to opening new tab
            // Don't hide loading screens immediately
        });
    }

    // Auto-hide loading on page load
    autoHideOnLoad() {
        // Hide loading screen after page is fully loaded
        window.addEventListener('load', () => {
            setTimeout(() => {
                this.hideAll();
            }, 1000);
        });

        // Also hide after DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    this.hideAll();
                }, 500);
            });
        } else {
            // DOM is already ready
            setTimeout(() => {
                this.hideAll();
            }, 500);
        }
    }

    // Hide all loading screens
    hideAll() {
        this.activeLoaders.clear();
        if (window.loadingScreen) {
            window.loadingScreen.hide();
        }
        if (window.simpleLoadingScreen) {
            window.simpleLoadingScreen.hide();
        }
    }
}

// Initialize global loading manager
document.addEventListener('DOMContentLoaded', () => {
    window.globalLoadingManager = new GlobalLoadingManager();
});

// Global functions for easy access
window.showLoading = (title, subtitle, type) => {
    if (window.globalLoadingManager) {
        return window.globalLoadingManager.show(title, subtitle, type);
    }
};

window.hideLoading = (loaderId, delay) => {
    if (window.globalLoadingManager) {
        window.globalLoadingManager.hide(loaderId, delay);
    }
};

window.showLoadingForAction = (action, customText) => {
    if (window.globalLoadingManager) {
        return window.globalLoadingManager.showForAction(action, customText);
    }
};

window.showLoadingWithProgress = (title, subtitle, progress) => {
    if (window.globalLoadingManager) {
        return window.globalLoadingManager.showWithProgress(title, subtitle, progress);
    }
};

window.showTemporaryLoading = (title, subtitle, duration) => {
    if (window.globalLoadingManager) {
        return window.globalLoadingManager.showTemporary(title, subtitle, duration);
    }
};

window.hideAllLoading = () => {
    if (window.globalLoadingManager) {
        window.globalLoadingManager.hideAll();
    }
};

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GlobalLoadingManager;
}








