<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dosen</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 2.5em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff; /* Mengubah warna menjadi biru */
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        img {
            width: 100px;
            height: auto;
        }
        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
            }
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Data Dosen</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIDN</th>
                <th>Nama Dosen</th>
                <th>Tanggal Mulai Tugas</th>
                <th>Jenjang Pendidikan</th>
                <th>Bidang Keilmuan</th>
                <th>Foto Dosen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dosens as $key => $dosen)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $dosen->nidn }}</td>
                    <td>{{ $dosen->nama_dosen }}</td>
                    <td>{{ $dosen->tgl_mulai_tugas }}</td>
                    <td>{{ $dosen->jenjang_pendidikan }}</td>
                    <td>{{ $dosen->bidang_keilmuan }}</td>
                    <td>
                        @if ($dosen->foto_dosen)
                            <img src="{{ public_path('storage/' . $dosen->foto_dosen) }}" alt="Foto Dosen">
                        @else
                            Tidak ada foto
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>