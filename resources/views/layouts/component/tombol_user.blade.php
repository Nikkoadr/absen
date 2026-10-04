<div style="display: inline;">
    <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#modalEditUserId{{ $data->id }}" aria-label="Ubah {{ $data->nama }}"><i class="fa-regular fa-pen-to-square" aria-hidden="true"></i></button>
    <button type="button" class="btn btn-warning m-1" data-toggle="modal" data-target="#ubah_password_id{{ $data->id }}" aria-label="Ubah sandi {{ $data->nama }}"><i class="fa-solid fa-unlock-keyhole" aria-hidden="true"></i></button>
    <button type="button" class="btn btn-primary m-1" data-toggle="modal" data-target="#modalLaporanIndividu{{ $data->id }}" aria-label="Cetak laporan {{ $data->nama }}"><i class="fa-solid fa-print" aria-hidden="true"></i></button>
    <form action="{{ route('hapus_data_user', $data->id) }}" method="POST" class="d-inline konfirmasi-form">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-danger m-1" aria-label="Hapus {{ $data->nama }}"><i class="far fa-trash-alt" aria-hidden="true"></i></button>
    </form>
</div>
