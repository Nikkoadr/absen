    <div class="modal fade" id="modalEditSiswa{{ $data->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Ubah Siswa</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <form method="POST" action="{{ route('siswa.update', $data->id) }}">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nama{{ $data->id }}">Nama <span style="color: #b91c1c;" aria-hidden="true">*</span></label>
                                <input id="nama{{ $data->id }}" type="text" class="form-control" name="nama" value="{{ $data->nama }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email{{ $data->id }}">Email <span style="color: #b91c1c;" aria-hidden="true">*</span></label>
                                <input id="email{{ $data->id }}" type="email" class="form-control" name="email" value="{{ $data->email }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nis{{ $data->id }}">NIS</label>
                                <input id="nis{{ $data->id }}" type="text" class="form-control" name="nis" value="{{ $data->siswa->nis ?? '' }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nisn{{ $data->id }}">NISN</label>
                                <input id="nisn{{ $data->id }}" type="text" class="form-control" name="nisn" value="{{ $data->siswa->nisn ?? '' }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lahir{{ $data->id }}">Tanggal lahir</label>
                                <input id="lahir{{ $data->id }}" type="date" class="form-control" name="tanggal_lahir" value="{{ $data->tanggal_lahir?->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kelas{{ $data->id }}">Kelas</label>
                            <select id="kelas{{ $data->id }}" class="form-control" name="kelas_id">
                                <option value="">- Belum ditentukan -</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}" @selected(($data->siswa->kelas_id ?? null) == $k->id)>{{ $k->tingkat }} {{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="ortu{{ $data->id }}">Nama orang tua/wali</label>
                                <input id="ortu{{ $data->id }}" type="text" class="form-control" name="nama_ortu" value="{{ $data->siswa->nama_ortu ?? '' }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="hp_ortu{{ $data->id }}">Nomor HP orang tua</label>
                                <input id="hp_ortu{{ $data->id }}" type="text" class="form-control" name="nomor_hp_ortu" value="{{ $data->siswa->nomor_hp_ortu ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chat{{ $data->id }}">ID chat Telegram orang tua</label>
                            <input id="chat{{ $data->id }}" type="text" class="form-control" name="telegram_chat_id" value="{{ $data->siswa->telegram_chat_id ?? '' }}" placeholder="cth: 123456789">
                        </div>
                        <div class="form-group">
                            <label for="rfid{{ $data->id }}">UID kartu RFID</label>
                            <input id="rfid{{ $data->id }}" type="text" class="form-control" name="rfid_uid" value="{{ $data->siswa->rfid_uid ?? '' }}" placeholder="cth: A1B2C3D4">
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
