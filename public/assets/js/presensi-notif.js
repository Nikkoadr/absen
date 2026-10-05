'use strict';

/* Notifikasi dan konfirmasi terpusat (SweetAlert2 + toast).
 * Semua keberhasilan/kegagalan tampil seragam; kegagalan tak terduga
 * selalu jadi toast merah, tidak pernah diam. */
window.Presensi = (function () {
    function perluSwal() {
        return typeof Swal !== 'undefined';
    }

    function toast(jenis, pesan) {
        if (!perluSwal()) return;
        Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        }).fire({ icon: jenis, title: String(pesan || '') });
    }

    return {
        sukses: function (pesan) { toast('success', pesan); },
        galat: function (pesan) { toast('error', pesan || 'Terjadi kesalahan. Coba lagi.'); },

        info: function (judul, teks) {
            if (!perluSwal()) return;
            Swal.fire({ title: judul, text: teks, icon: 'info' });
        },

        /* Konfirmasi hapus/aksi destruktif. Mengembalikan Promise<boolean>. */
        konfirmasi: function (teks, teksTombol) {
            if (!perluSwal()) {
                return Promise.resolve(window.confirm(teks));
            }
            return Swal.fire({
                text: teks,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: teksTombol || 'Ya, lanjutkan!',
                cancelButtonText: 'Batal',
            }).then(function (hasil) { return hasil.isConfirmed; });
        },

        /* Ikat semua form.konfirmasi-form agar selalu konfirmasi dulu. */
        konfirmasiForm: function (wadah, teks, teksTombol) {
            (wadah || document).addEventListener('submit', function (e) {
                var form = e.target.closest ? e.target.closest('.konfirmasi-form') : null;
                if (!form) return;
                e.preventDefault();
                var pesan = (form.dataset && form.dataset.konfirmasi) || teks || 'Anda yakin ingin menghapus data ini?';
                window.Presensi.konfirmasi(pesan, teksTombol || 'Ya, Hapus!').then(function (ya) {
                    if (ya) form.submit();
                });
            });
        },

        /* Bungkus operasi async: galat jaringan/kode jadi toast merah. */
        aman: function (janji, pesanGagal) {
            return Promise.resolve()
                .then(janji)
                .catch(function (e) {
                    console.error(e);
                    window.Presensi.galat(pesanGagal);
                    throw e;
                });
        },
    };
})();
