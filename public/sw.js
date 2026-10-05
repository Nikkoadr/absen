'use strict';

/* Service worker: statis stale-while-revalidate, API/POST selalu jaringan. */
const VERSI = 'presensi-v1';
const STATIS = [
    '/',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/assets/css/presensi-tokens.css',
    '/assets/css/presensi-admin.css',
];

self.addEventListener('install', (e) => {
    e.waitUntil(
        caches.open(VERSI).then((c) => c.addAll(STATIS)).then(() => self.skipWaiting()).catch(() => {})
    );
});

self.addEventListener('activate', (e) => {
    e.waitUntil(
        caches.keys()
            .then((kunci) => Promise.all(kunci.filter((k) => k !== VERSI).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (e) => {
    const url = new URL(e.request.url);

    if (e.request.method !== 'GET' || url.pathname.startsWith('/api/')) {
        return; // API dan form selalu ke jaringan.
    }

    if (url.origin !== location.origin) {
        return;
    }

    e.respondWith(
        caches.match(e.request).then((cocok) => {
            const ambil = fetch(e.request).then((res) => {
                if (res.ok) {
                    const salin = res.clone();
                    caches.open(VERSI).then((c) => c.put(e.request, salin));
                }
                return res;
            }).catch(() => cocok);

            return cocok || ambil;
        })
    );
});
