    <div class="modal fade" id="modalImporSiswa">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Impor Siswa (Excel)</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('siswa.impor') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('berkas') is-invalid @enderror" id="berkas_siswa" name="berkas" accept=".xlsx,.xls,.csv">
                        <label class="custom-file-label" for="berkas_siswa">Pilih file</label>
                    </div>
                    @error('berkas')<span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>@enderror
                </div>
                <button type="submit" class="btn btn-primary float-right">Upload</button>
            </form>
            @if (session('warning_impor'))
                <div class="alert alert-warning mt-2 mb-0">
                    <ul class="mb-0 pl-3">
                        @foreach (session('warning_impor') as $w)
                            <li>{{ $w }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        </div>
    </div>
    </div>
