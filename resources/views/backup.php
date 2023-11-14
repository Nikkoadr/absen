                            <?php $no = 1; ?>
                            @foreach ($leaderboard as $data)
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>{{ $data->nama }}</td>
                                <td><img style="width: 15%" src="{{ asset('storage/absen_file/'. $data->foto_masuk) }}" alt="image" class="image" /></td>
                                <td><span class="badge 
                                        @if($data->jam_masuk > " 07:00") badge-warning @else badge-success @endif ">{{ $data->jam_masuk }}</span></td>
                                    <td>@if ($data->foto_keluar == null)
                                        <small>Belum Pulang</small>
                                    @else
                                        <img style=" width: 15%" src="{{ asset('storage/absen_file/'. $data->foto_keluar) }}" alt="image" class="image" />
                                    @endif
                                </td>
                                <td>
                                    @if ($data->jam_keluar == null)
                                    <small>Belum Pulang</small>
                                    @else
                                    {{ $data->jam_keluar }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                            <script>
                                $(function() {
                                    $("#table_rekap").DataTable({
                                        "responsive": true,
                                        "lengthChange": false,
                                        "autoWidth": false,
                                        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                                    }).buttons().container().appendTo('#table_rekap_wrapper .col-md-6:eq(0)');
                                });
                            </script>

                            <script>
                                $(document).ready(function() {
                                    $('#table_rekap').DataTable({
                                        processing: true,
                                        serverSide: true,
                                        ajax: {
                                            url: "{{ route('GetDataLeaderboard') }}",
                                            type: "POST",
                                            data: {
                                                "_token": "{{ csrf_token() }}"
                                            }
                                        },
                                        columns: [{
                                                data: 'DT_RowIndex',
                                                name: 'DT_RowIndex',
                                                orderable: false,
                                                searchable: false,
                                            },
                                            {
                                                data: 'nama',
                                                name: 'nama'
                                            },
                                            {
                                                data: 'foto_masuk',
                                                name: 'foto_masuk',
                                                render: function(data, type, full, meta) {
                                                    return '<img style="width: 50px" src="{{ asset('
                                                    storage / absen_file / ') }}/' + data + '" alt="image" class="image" />';
                                                }
                                            },
                                            {
                                                data: 'jam_masuk',
                                                name: 'jam_masuk'
                                            },
                                            {
                                                data: 'foto_keluar',
                                                name: 'foto_keluar',
                                                render: function(data, type, full, meta) {
                                                    if (data) {
                                                        return '<img style="width: 50px" src="{{ asset('
                                                        storage / absen_file / ') }}/' + data + '" alt="image" class="image" />';
                                                    } else {
                                                        return 'Belum ada foto pulang';
                                                    }
                                                }
                                            },
                                            {
                                                data: 'jam_keluar',
                                                name: 'jam_keluar',
                                                render: function(data, type, full, meta) {
                                                    if (data) {
                                                        return data;
                                                    } else {
                                                        return 'Belum ada jam pulang';
                                                    }
                                                }
                                            },
                                        ]
                                    });
                                });
                            </script>