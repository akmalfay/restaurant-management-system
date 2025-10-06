<!DOCTYPE html>
<html>
<head>
    <title>Edit Staff</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<div class="container">
    <h1>Edit Staff</h1>

    <form action="{{ route('staffs.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ $staff->name }}" required>
        </div>

        <div class="mb-3">
            <label>Posisi</label>
            <input type="text" name="position" class="form-control" value="{{ $staff->position }}" required>
        </div>

        <div class="mb-3">
            <label>Status Aktif</label>
            <select name="active" class="form-control">
                <option value="1" {{ $staff->active ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ !$staff->active ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Telepon</label>
            <input type="text" name="phone" class="form-control" value="{{ $staff->phone }}">
        </div>

        <div class="mb-3">
            <label>Foto</label><br>
            @if($staff->image)
                <img src="{{ asset('storage/'.$staff->image) }}" width="80" class="mb-2"><br>
            @endif
            <input type="file" name="image" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('staffs.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>
