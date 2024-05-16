<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <form action="update_absen_{{ $data -> id }}" method="POST">
            @method('put')
            @csrf
            <label for="nama">Nama</label>
            <input type="text" id="nama" name="nama" value="{{ $data ->user -> nama }}" disabled>
            <label for="tanggal_absen">Tanggal</label>
            <input type="text" id="tanggal_absen" name="tanggal_absen" value="{{ $data -> tanggal_absen }}" required>
            <label for="jam_masuk">Jam Masuk</label>
            <input type="text" id="jam_masuk" name="jam_masuk" value="{{ $data -> jam_masuk }}"required>
            <label for="jam_keluar">Jam Pulang</label>
            <input type="text" id="jam_keluar" name="jam_keluar" value="{{ $data -> jam_keluar }}">
            <button type="submit">Edit</button>
            <a href="/attendance">kembali</a>
        </form>
    </body>
</html>