<!DOCTYPE html>
<html>
<head>
    <title>Data Staff</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<div class="container">
    <h1 class="mb-4">Daftar Staff</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('staffs.create') }}" class="btn btn-primary mb-3">+ Tambah Staff</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Posisi</th>
                <th>Aktif</th>
                <th>Telepon</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staffs as $staff)
                <tr>
                    <td>{{ $staff->name }}</td>
                    <td>{{ $staff->position }}</td>
                    <td>{{ $staff->active ? 'Ya' : 'Tidak' }}</td>
                    <td>{{ $staff->phone }}</td>
                    <td>
                        @if($staff->image)
                            <img src="{{ asset('storage/'.$staff->image) }}" width="60">
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('staffs.edit', $staff->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('staffs.destroy', $staff->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin ingin hapus?')" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
