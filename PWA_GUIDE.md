# 📱 PWA Implementation Guide - e-Report

## ✅ Fitur yang Sudah Diimplementasikan

### 1. **Installable Web App**
- ✅ Manifest file (`/manifest.json`)
- ✅ Service Worker (`/sw.js`)
- ✅ Install prompt UI (muncul otomatis setelah 3 detik)
- ✅ iOS support (Apple Touch Icons)

### 2. **Offline Support**
- ✅ Cache static assets (CSS, JS, images)
- ✅ Offline fallback page (`/offline.html`)
- ✅ Network-first strategy untuk HTML
- ✅ Cache-first strategy untuk static files

### 3. **Advanced Features**
- ✅ Background sync (untuk submit laporan offline)
- ✅ Push notifications support
- ✅ App shortcuts (Quick actions)
- ✅ Share target API
- ✅ Auto-update detection

---

## 📋 Yang Perlu Dilakukan Selanjutnya

### 1. Generate App Icons
Anda perlu membuat folder `/public/icons/` dan generate icon dalam berbagai ukuran:

**Required Sizes:**
- 72x72
- 96x96
- 128x128
- 144x144
- 152x152
- 192x192 (maskable)
- 384x384
- 512x512 (maskable)

**Cara Generate:**
1. Gunakan logo e-Report yang ada
2. Gunakan tool online seperti:
   - https://realfavicongenerator.net/
   - https://www.pwabuilder.com/imageGenerator
3. Upload logo, pilih semua ukuran di atas
4. Download dan extract ke `/public/icons/`

### 2. Test PWA

#### Desktop (Chrome/Edge):
1. Buka aplikasi di browser
2. Tunggu 3 detik → Install prompt muncul
3. Klik "Install"
4. App akan terbuka di window terpisah

#### Mobile (Android):
1. Buka di Chrome mobile
2. Klik menu (⋮) → "Add to Home screen"
3. Icon akan muncul di home screen
4. Tap icon → App buka seperti native app

#### iOS (Safari):
1. Buka di Safari
2. Tap Share button
3. Tap "Add to Home Screen"
4. Icon akan muncul di home screen

---

## 🎯 Fitur PWA yang Aktif

### Install Prompt
```javascript
// Muncul otomatis setelah 3 detik
// Bisa dismiss, akan muncul lagi setelah 7 hari
// Lokasi: Bottom-right corner (desktop) / Bottom (mobile)
```

### Offline Mode
```
Jika koneksi terputus:
1. Halaman yang sudah dibuka tetap bisa dilihat
2. Navigasi ke halaman baru → Redirect ke /offline.html
3. Auto-reload saat koneksi kembali
```

### Caching Strategy
```javascript
Static Assets (CSS/JS/Images):
- Cache-first (load dari cache, fallback ke network)

HTML Pages:
- Network-first (load dari network, fallback ke cache)

API Requests:
- Network-only (tidak di-cache untuk data fresh)
```

### Background Sync
```javascript
// Jika submit laporan saat offline:
1. Data disimpan di IndexedDB
2. Saat online kembali → Auto-sync ke server
3. User dapat notifikasi sukses
```

---

## 🔧 Konfigurasi

### Manifest (`/manifest.json`)
```json
{
  "name": "e-Report - Sistem Pelaporan Sekolah",
  "short_name": "e-Report",
  "start_url": "/dashboard",
  "display": "standalone",
  "theme_color": "#667eea",
  "background_color": "#ffffff"
}
```

### Service Worker (`/sw.js`)
```javascript
CACHE_NAME: 'ereport-v1.0.0'
STATIC_CACHE: 'ereport-static-v1.0.0'
DYNAMIC_CACHE: 'ereport-dynamic-v1.0.0'
```

**Update Version:**
Jika ada update app, ubah version number di `sw.js`:
```javascript
const CACHE_NAME = 'ereport-v1.0.1'; // Increment version
```

---

## 🧪 Testing Checklist

### Desktop:
- [ ] Install prompt muncul setelah 3 detik
- [ ] Klik "Install" → App terbuka di window baru
- [ ] Klik "Nanti" → Prompt hilang
- [ ] Refresh page → Prompt tidak muncul lagi (sampai 7 hari)
- [ ] Disconnect internet → Halaman tetap bisa dilihat
- [ ] Navigate ke page baru saat offline → Redirect ke /offline.html

### Mobile (Android):
- [ ] Menu "Add to Home Screen" tersedia
- [ ] Install → Icon muncul di home screen
- [ ] Tap icon → App buka fullscreen (no browser UI)
- [ ] Status bar color = #667eea (purple)
- [ ] Offline mode works

### iOS (Safari):
- [ ] "Add to Home Screen" tersedia
- [ ] Icon muncul di home screen
- [ ] Tap icon → App buka fullscreen
- [ ] Splash screen muncul saat loading

---

## 📊 PWA Audit

Gunakan Lighthouse untuk audit PWA:

1. Buka Chrome DevTools (F12)
2. Tab "Lighthouse"
3. Select "Progressive Web App"
4. Click "Generate report"

**Target Score:** 90+ / 100

**Common Issues:**
- ❌ Icons not found → Generate icons
- ❌ Service worker not registered → Check console
- ❌ Manifest not valid → Validate JSON

---

## 🚀 Production Deployment

### 1. Generate Icons
```bash
# Pastikan folder /public/icons/ ada dan berisi semua ukuran
ls public/icons/
```

### 2. Test Service Worker
```bash
# Buka browser console
# Cek: "[PWA] Service Worker registered"
```

### 3. Update Cache Version
```javascript
// Setiap deploy, update version di sw.js
const CACHE_NAME = 'ereport-v1.0.X';
```

### 4. Clear Old Caches
```javascript
// Service worker otomatis clear old caches
// Tapi bisa manual via DevTools:
// Application → Storage → Clear site data
```

---

## 🎨 Customization

### Change Theme Color
```json
// manifest.json
"theme_color": "#667eea" // Ubah ke warna brand
```

```html
<!-- layouts/app.blade.php -->
<meta name="theme-color" content="#667eea">
```

### Change App Name
```json
// manifest.json
"name": "Nama App Baru",
"short_name": "App"
```

### Add More Shortcuts
```json
// manifest.json
"shortcuts": [
  {
    "name": "Shortcut Baru",
    "url": "/path",
    "icons": [...]
  }
]
```

---

## 📱 Push Notifications (Optional)

Untuk enable push notifications:

1. **Setup Firebase Cloud Messaging (FCM)**
2. **Add to manifest:**
```json
"gcm_sender_id": "YOUR_SENDER_ID"
```

3. **Request permission:**
```javascript
Notification.requestPermission().then(permission => {
  if (permission === 'granted') {
    // Subscribe to push
  }
});
```

---

## 🐛 Troubleshooting

### Install Prompt Tidak Muncul
```
Possible causes:
1. Already installed
2. Dismissed dalam 7 hari terakhir
3. Browser tidak support (harus HTTPS)
4. Manifest error

Solution:
- Check console untuk error
- Clear localStorage: localStorage.removeItem('pwa-install-dismissed')
- Validate manifest: chrome://webapk-internals
```

### Service Worker Tidak Register
```
Possible causes:
1. File sw.js tidak ditemukan
2. Syntax error di sw.js
3. Browser tidak support

Solution:
- Check /sw.js accessible
- Check console untuk error
- Test di Chrome/Edge (full support)
```

### Offline Mode Tidak Bekerja
```
Possible causes:
1. Service worker belum install
2. Cache kosong
3. Network-only strategy

Solution:
- Reload page beberapa kali (populate cache)
- Check Application → Cache Storage
- Verify caching strategy di sw.js
```

---

## 📈 Analytics

Track PWA usage:

```javascript
// Detect PWA mode
if (window.matchMedia('(display-mode: standalone)').matches) {
  // User using installed app
  gtag('event', 'pwa_usage', { mode: 'standalone' });
}

// Track install
window.addEventListener('appinstalled', () => {
  gtag('event', 'pwa_installed');
});
```

---

**Status:** PWA Fully Implemented ✅  
**Next:** Generate icons & test on mobile devices  
**Version:** 1.0.0
