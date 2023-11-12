    <div class="modal fade" id="modalLaporanIndividu{{ $data->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Pilih Bulan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
                <form method="POST" action="/printLaporanIndividu{{ $data->id }}" target="_blank">
                        @csrf
                        @method('put')
                        <div class="row">
                            <input type="hidden" value="{{ $data->id }}">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="bulan">Bulan:</label>
                                        <select class="form-control" id="bulan" name="bulan">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>
                                                {{ Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tahun">Tahun:</label>
                                        <input type="text" class="form-control" id="tahun" name="tahun" placeholder="Masukkan tahun" value="{{ $tahun }}" readonly>
                                    </div>
                                </div>
                        </div>
                        <button style="float: right;" type="submit" class="btn btn-primary">
                            Lihat Laporan
                        </button>
                    </form>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->