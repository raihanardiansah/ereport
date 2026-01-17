// Browser Notification Handler for Critical Reports
class ReportNotificationManager {
    constructor() {
        this.permission = Notification.permission;
        this.checkInterval = 30000; // Check every 30 seconds
        this.lastCheckTime = null;
        this.isAdmin = document.body.dataset.userRole && 
                       ['super_admin', 'admin_sekolah', 'manajemen_sekolah', 'staf_kesiswaan'].includes(document.body.dataset.userRole);
    }

    async init() {
        if (!this.isAdmin) return;
        
        // Request permission if not granted
        if (this.permission === 'default') {
            await this.requestPermission();
        }

        // Start polling for critical reports
        if (this.permission === 'granted') {
            this.startPolling();
        }
    }

    async requestPermission() {
        try {
            const permission = await Notification.requestPermission();
            this.permission = permission;
            
            if (permission === 'granted') {
                this.showWelcomeNotification();
            }
        } catch (error) {
            console.error('Notification permission error:', error);
        }
    }

    showWelcomeNotification() {
        new Notification('e-Report Notifications Enabled', {
            body: 'You will receive alerts for critical reports',
            icon: '/favicon.ico',
            badge: '/favicon.ico'
        });
    }

    async checkForCriticalReports() {
        try {
            const response = await fetch('/api/critical-reports/check', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) return;

            const data = await response.json();
            
            if (data.hasNew && data.reports && data.reports.length > 0) {
                data.reports.forEach(report => {
                    this.showCriticalReportNotification(report);
                });
            }
        } catch (error) {
            console.error('Error checking critical reports:', error);
        }
    }

    showCriticalReportNotification(report) {
        if (this.permission !== 'granted') return;

        const notification = new Notification('🚨 Laporan Critical Baru!', {
            body: `${report.title}\nDari: ${report.reporter}\nKategori: ${report.category}`,
            icon: '/favicon.ico',
            badge: '/favicon.ico',
            tag: `critical-report-${report.id}`,
            requireInteraction: true,
            data: {
                reportId: report.id,
                url: `/reports/${report.id}`
            }
        });

        notification.onclick = (event) => {
            event.preventDefault();
            window.focus();
            window.location.href = event.target.data.url;
        };
    }

    startPolling() {
        // Initial check
        this.checkForCriticalReports();

        // Poll every 30 seconds
        setInterval(() => {
            this.checkForCriticalReports();
        }, this.checkInterval);
    }

    showPermissionPrompt() {
        // Show a custom UI prompt for enabling notifications
        const banner = document.createElement('div');
        banner.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-md';
        banner.innerHTML = `
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold mb-1">Aktifkan Notifikasi?</p>
                    <p class="text-sm opacity-90 mb-3">Dapatkan alert untuk laporan critical</p>
                    <div class="flex gap-2">
                        <button onclick="notificationManager.requestPermission(); this.closest('div').parentElement.parentElement.remove();" 
                                class="bg-white text-blue-500 px-4 py-1 rounded text-sm font-medium hover:bg-blue-50">
                            Aktifkan
                        </button>
                        <button onclick="this.closest('div').parentElement.parentElement.remove();" 
                                class="bg-blue-600 text-white px-4 py-1 rounded text-sm hover:bg-blue-700">
                            Nanti
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(banner);

        // Auto-dismiss after 10 seconds
        setTimeout(() => {
            if (banner.parentElement) {
                banner.remove();
            }
        }, 10000);
    }
}

// Initialize notification manager
const notificationManager = new ReportNotificationManager();

// Auto-init on page load for admin users
document.addEventListener('DOMContentLoaded', () => {
    if (notificationManager.isAdmin) {
        // Show prompt after 3 seconds if permission not granted
        if (Notification.permission === 'default') {
            setTimeout(() => {
                notificationManager.showPermissionPrompt();
            }, 3000);
        } else if (Notification.permission === 'granted') {
            notificationManager.init();
        }
    }
});
