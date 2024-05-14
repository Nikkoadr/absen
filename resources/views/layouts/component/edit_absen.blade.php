<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="update_absen" method="post">
        @method('put')
        <label for="nama"></label>
        @dd($data)
        <input type="text" id="nama" name="nama" value="{{ $data -> nama }}">
    </form>
</body>
</html>