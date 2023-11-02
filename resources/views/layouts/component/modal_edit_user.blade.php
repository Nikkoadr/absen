    <div class="modal fade" id="modalEditUserId{{ $data->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Form Tambah User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <form method="POST" action="/editUserId{{ $data->id }}">
                        @csrf
                        @method('put')
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-5 col-form-label text-md-end">Nama</label>
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
                            <label for="email" class="col-sm-5 col-form-label text-md-end">E-mail</label>
                            <div class="col-sm-7">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $data->email }}" autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role" class="col-sm-5 col-form-label">Role</label>
                                <div class="col-sm-7">
                                    <select type="text" class="form-control @error('role') is-invalid @enderror" name="role" id="role">
                                    <option value="admin" @if ($data->role =="admin") selected @endif>Admin</option>
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