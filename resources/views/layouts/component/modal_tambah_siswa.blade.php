    <div class="modal fade" id="modalTambahSiswa">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Tambah Siswa</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <form method="POST" action="{{ route('siswa.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tambah_nama">Nama <span style="color: #b91c1c;" aria-hidden="true">*</span></label>
                                <input id="tambah_nama" type="text" class="form-control" name="nama" value="{{ old('nama') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tambah_email">Email <span style="color: #b91c1c;" aria-hidden="true">*</span></label>
                                <input id="tambah_email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tambah_password">Kata sandi <span style="color: #b91c1c;" aria-hidden="true">*</span></label>
                                <input id="tambah_password" type="password" class="form-control" name="password" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tambah_password_confirmation">Konfirmasi kata sandi <span style="color: #b91c1c;" aria-hidden="true">*</span></label>
                                <input id="tambah_password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="tambah_nis">NIS</label>
                                <input id="tambah_nis" type="text" class="form-control" name="nis" value="{{ old('nis') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="tambah_nisn">NISN</label>
                                <input id="tambah_nisn" type="text" class="form-control" name="nisn" value="{{ old('nisn') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="tambah_tanggal_lahir">Tanggal lahir</label>
                                <input id="tambah_tanggal_lahir" type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tambah_kelas">Kelas</label>
                            <select id="tambah_kelas" class="form-control" name="kelas_id">
                                <option value="">- Belum ditentukan -</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->tingkat }} {{ $k->nama }} ({{ $k->kompetensi->singkatan }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tambah_ortu">Nama orang tua/wali</label>
                                <input id="tambah_ortu" type="text" class="form-control" name="nama_ortu" value="{{ old('nama_ortu') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="tambah_hp_ortu">Nomor HP orang tua</label>
                                <input id="tambah_hp_ortu" type="text" class="form-control" name="nomor_hp_ortu" value="{{ old('nomor_hp_ortu') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tambah_chat">ID chat Telegram orang tua</label>
                            <input id="tambah_chat" type="text" class="form-control" name="telegram_chat_id" value="{{ old('telegram_chat_id') }}" placeholder="cth: 123456789">
                            <small class="form-text text-muted">Diisi admin agar notifikasi presensi terkirim ke orang tua.</small>
                        </div>
                        <div class="form-group">
                            <label for="tambah_rfid">UID kartu RFID</label>
                            <input id="tambah_rfid" type="text" class="form-control" name="rfid_uid" value="{{ old('rfid_uid') }}" placeholder="cth: A1B2C3D4">
                            <small class="form-text text-muted">Tempel kartu di alat lalu salin UID yang terbaca.</small>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">
                            Simpan
                        </button>
                    </form>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
