    <div class="modal fade" id="modal_tambah_user">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Form Tambah User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <form method="POST" action="/tambah_user">
                        @csrf
                        @method('put')
                        <div class="form-group row">
                            <label for="role" class="col-sm-5 col-form-label">Role</label>
                                <div class="col-sm-7">
                                    <select type="text" class="form-control @error('role') is-invalid @enderror" name="role" id="role">
                                    <option value="">Pilih Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="karyawan">Karyawan</option>
                                    <option value="guru">Guru</option>
                                    <option value="siswa">Siswa</option>
                                    </select>
                                    @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                        <div class="row mb-3">
                            <label for="nik" class="col-sm-5 col-form-label text-md-end">NIK</label>
                            <div class="col-sm-7">
                                <input id="nik" type="number" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik')}}" autocomplete="nik" autofocus>
                                @error('nik')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nuptk" class="col-sm-5 col-form-label text-md-end">NUPTK</label>
                            <div class="col-sm-7">
                                <input id="nuptk" type="number" class="form-control @error('nuptk') is-invalid @enderror" name="nuptk" value="{{ old('nuptk')}}" autocomplete="nuptk" autofocus>
                                @error('nuptk')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nbm" class="col-sm-5 col-form-label text-md-end">NBM</label>
                            <div class="col-sm-7">
                                <input id="nbm" type="number" class="form-control @error('nbm') is-invalid @enderror" name="nbm" value="{{ old('nbm')}}" autocomplete="nbm" autofocus>
                                @error('nbm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-5 col-form-label text-md-end">Nama</label>
                            <div class="col-sm-7">
                                <input id="nama" type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}" required autocomplete="nama" autofocus>
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-sm-5 col-form-label text-md-end">E-mail</label>
                            <div class="col-sm-7">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password" class="col-sm-5 col-form-label text-md-end">Password</label>
                            <div class="col-sm-7">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-sm-5 col-form-label text-md-end">Konfirmasi Password</label>
                            <div class="col-sm-7">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jabatan" class="col-sm-5 col-form-label text-md-end">Jabatan</label>
                            <div class="col-sm-7">
                                <input id="jabatan" type="text" class="form-control @error('jabatan') is-invalid @enderror" name="jabatan" value="{{ old('jabatan') }}" required autocomplete="jabatan" autofocus>
                                @error('jabatan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jam_kerja" class="col-sm-5 col-form-label text-md-end">Jam_kerja</label>
                            <div class="col-sm-7">
                                <input id="jam_kerja" type="time" class="form-control @error('jam_kerja') is-invalid @enderror" name="jam_kerja" value="{{ old('jam_kerja') }}" required autocomplete="jam_kerja" autofocus>
                                @error('jam_kerja')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="lokasi_lang" class="col-sm-5 col-form-label text-md-end">Lokasi Langitude</label>
                            <div class="col-sm-7">
                                <input id="lokasi_lang" type="text" class="form-control @error('lokasi_lang') is-invalid @enderror" name="lokasi_lang" value="{{ old('lokasi_lang') }}" autocomplete="lokasi_lang" autofocus>
                                @error('lokasi_lang')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="lokasi_long" class="col-sm-5 col-form-label text-md-end">Lokasi Longitude</label>
                            <div class="col-sm-7">
                                <input id="lokasi_long" type="text" class="form-control @error('lokasi_long') is-invalid @enderror" name="lokasi_long" value="{{ old('lokasi_long') }}" autocomplete="lokasi_long" autofocus>
                                @error('lokasi_long')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                                <button style="float: right;" type="submit" class="btn btn-primary">
                                    Tambah
                                </button>
                    </form>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->