@extends('layouts.main_mobile')
@section('link')

@endsection
@section('content')
<div class="presencetab mt-2">
    <h5 style="font-weight: bold; text-align: center; font-size: 1.5rem;">Request Izin</h5>
    <div class="tab-content mt-2" style="margin-bottom: 100px">
        <div class="tab-pane fade show active" id="dataDiri" role="tabpanel">
            <div class="section mt-3 mb-5">
                <div class="card">
                    <form action="/request_izin_user_id{{ Auth::user()->id }}" method="POST">
                        @csrf
                        <div class="col">
                            <div class="row mb-3">
                                <label for="nama" class="col-sm-3 col-form-label text-md-end">Nama <span style="color: red">*</span> : </label>
                                <div class="col-sm-9">
                                    <input id="nama" readonly type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ Auth::user()->nama }}" autocomplete="nama" autofocus>
                                    @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Form tambahan untuk Request Izin -->
                            <div class="row mb-3">
                                <label for="izin" class="col-sm-3 col-form-label text-md-end">Jenis Izin <span style="color: red">*</span> : </label>
                                <div class="col-sm-9">
                                    <select id="izin" class="form-control @error('izin') is-invalid @enderror" name="izin">
                                        <option value="" disabled selected>Pilih Jenis Izin</option>
                                        <option value="Sakit">Sakit</option>
                                        <option value="Dinas Luar">Dinas Luar</option>
                                        <option value="Izin Lainnya">Izin Lainnya</option>
                                    </select>
                                    @error('izin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="keterangan" class="col-sm-3 col-form-label text-md-end">Keterangan :</label>
                                <div class="col-sm-9">
                                    <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="3" placeholder="Isi keterangan izin Anda"></textarea>
                                    @error('keterangan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- End of Request Izin -->
                            <div style="margin-bottom: 50px" class="form-group boxed">
                                <div class="input-wrapper">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <ion-icon name="refresh-outline"></ion-icon>
                                        Izin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
    {{-- <script>
        $(document).ready(function() {
            Swal.fire({
                title: 'Maaf !!!',
                text: 'Form Izin Masih Dalam Pengembangan ICT !!!',
                icon: 'warning',
                confirmButtonText: 'Siap Kapten !!!',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/home";
                }
            });
        });
    </script> --}}
@endsection
</body>
</html>