    <div class="modal fade" id="modalEditUserId{{ $data->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <form method="POST" action="/editUserId{{ $data->id }}">
                        @csrf
                        @method('put')
                        <div class="form-group row">
                            <label for="role" class="col-sm-5 col-form-label">Role <span style="color: red">*</span></label>
                                <div class="col-sm-7">
                                    <select type="text" class="form-control @error('role') is-invalid @enderror" name="role" id="role">
                                    <option value="admin" @if ($data->role =="admin") selected @endif>Admin</option>
                                    <option value="karyawan" @if ($data->role =="karyawan") selected @endif>Karyawan</option>
                                    <option value="guru" @if ($data->role =="guru") selected @endif>Guru</option>
                                    <option value="siswa" @if ($data->role =="siswa") selected @endif>Siswa</option>
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
                                <input id="nik" type="number" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ $data->nik }}" autocomplete="nik" autofocus>
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
                                <input id="nuptk" type="number" class="form-control @error('nuptk') is-invalid @enderror" name="nuptk" value="{{ $data->nuptk }}" autocomplete="nuptk" autofocus>
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
                                <input id="nbm" type="number" class="form-control @error('nbm') is-invalid @enderror" name="nbm" value="{{ $data->nbm }}" autocomplete="nbm" autofocus>
                                @error('nbm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-5 col-form-label text-md-end">Nama <span style="color: red">*</span></label>
                            <div class="col-sm-7">
                                <input id="nama" type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ $data->nama }}" autocomplete="nama" autofocus>
                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nomor_hp" class="col-sm-5 col-form-label text-md-end">Nomor HP <span style="color: red">*</span></label>
                            <div class="col-sm-7">
                                <input id="nomor_hp" type="text" class="form-control @error('nomor_hp') is-invalid @enderror" name="nomor_hp" value="{{ $data->nomor_hp }}" autocomplete="nomor_hp" autofocus>
                                @error('nomor_hp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-sm-5 col-form-label text-md-end">E-mail <span style="color: red">*</span></label>
                            <div class="col-sm-7">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $data->email }}" autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jabatan" class="col-sm-5 col-form-label text-md-end">Jabatan</label>
                            <div class="col-sm-7">
                                <input id="jabatan" type="text" class="form-control @error('jabatan') is-invalid @enderror" name="jabatan" value="{{ $data->jabatan }}" autocomplete="jabatan" autofocus>
                                @error('jabatan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jam_kerja" class="col-sm-5 col-form-label text-md-end">Jam Kerja</label>
                            <div class="col-sm-7">
                                <input id="jam_kerja" type="time" class="form-control @error('jam_kerja') is-invalid @enderror" name="jam_kerja" value="{{ $data->jam_kerja }}" required autocomplete="jam_kerja" autofocus>
                                @error('jam_kerja')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jam_pulang" class="col-sm-5 col-form-label text-md-end">Jam Pulang</label>
                            <div class="col-sm-7">
                                <input id="jam_pulang" type="time" class="form-control @error('jam_pulang') is-invalid @enderror" name="jam_pulang" value="{{ $data->jam_pulang }}" required autocomplete="jam_pulang" autofocus>
                                @error('jam_pulang')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <button style="float: right;" type="submit" class="btn btn-primary">
                            Edit
                        </button>
                    </form>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->