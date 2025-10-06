<!DOCTYPE html>
<html>
<head>
    <title>Tambah Staff</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<div class="container">
    <h1>Tambah Staff Baru</h1>

    <form action="{{ route('staffs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Posisi</label>
            <input type="text" name="position" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status Aktif</label>
            <select name="active" class="form-control">
                <option value="1">Aktif</option>
                <option value="0">Tidak Aktif</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Telepon</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label>Foto</label>
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('staffs.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>
