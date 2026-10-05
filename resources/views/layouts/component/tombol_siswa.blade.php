<div style="display: inline;">
    <button type="button" class="btn btn-info m-1" data-toggle="modal" data-target="#modalEditSiswa{{ $data->id }}" aria-label="Ubah {{ $data->nama }}"><i class="fa-regular fa-pen-to-square" aria-hidden="true"></i></button>
    <form action="{{ route('siswa.destroy', $data->id) }}" method="POST" class="d-inline konfirmasi-form">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-danger m-1" aria-label="Hapus {{ $data->nama }}"><i class="far fa-trash-alt" aria-hidden="true"></i></button>
    </form>
</div>
