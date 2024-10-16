<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Manage Dosen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-900 text-white">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Daftar Dosen</h1>

        <!-- Search Bar -->
        <div class="flex items-center mb-4">
            <form action="{{ route('dosen.index') }}" method="GET" class="flex space-x-2">
                <input type="text" name="search" placeholder="Search dosen..."
                    class="rounded bg-gray-800 text-white p-2 focus:outline-none">
                <button type="submit" class="bg-blue-500 p-2 rounded">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <!-- Create Button -->
            <button onclick="openModal('createModal')" class="ml-auto bg-green-500 p-2 rounded">
                <i class="fas fa-plus"></i> Tambah Dosen
            </button>

            <a href="{{ route('dosen.export') }}" class="bg-blue-500 p-2 rounded ml-4">
                <i class="fas fa-file-excel"></i> Export to Excel
            </a>
        </div>

        <!-- Table -->
        <table class="table-auto w-full bg-gray-800 rounded">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-center">No</th>
                    <th class="px-4 py-2 text-center">NIDN</th>
                    <th class="px-4 py-2 text-center">Nama Dosen</th>
                    <th class="px-4 py-2 text-center">Tanggal Mulai Tugas</th>
                    <th class="px-4 py-2 text-center">Jenjang Pendidikan</th>
                    <th class="px-4 py-2 text-center">Bidang Keilmuan</th>
                    <th class="px-4 py-2 text-center">Foto</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($dosens->count())
                    @foreach ($dosens as $dosen)
                        <tr class="bg-gray-700 border-t border-gray-600 text-center">
                            <td class="px-4 py-2 align-middle">{{ $loop->iteration }}</td> <!-- Nomor -->
                            <td class="px-4 py-2 align-middle">{{ $dosen->nidn }}</td>
                            <td class="px-4 py-2 align-middle">{{ $dosen->nama_dosen }}</td>
                            <td class="px-4 py-2 align-middle">{{ $dosen->tgl_mulai_tugas }}</td>
                            <td class="px-4 py-2 align-middle">{{ $dosen->jenjang_pendidikan }}</td>
                            <td class="px-4 py-2 align-middle">{{ $dosen->bidang_keilmuan }}</td>
                            <td class="px-4 py-2 align-middle">
                                @if ($dosen->foto_dosen)
                                    <img src="{{ asset('storage/' . $dosen->foto_dosen) }}" alt="Foto Dosen"
                                        class="w-12 h-12 object-cover rounded-full">
                                @else
                                    <span>Tidak ada foto</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 align-middle flex justify-center space-x-2">
                                <button onclick="openModal('detail-{{ $dosen->nidn }}')"
                                    class="bg-green-500 p-2 rounded">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="openModal('edit-{{ $dosen->nidn }}')"
                                    class="bg-yellow-500 p-2 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('dosen.destroy', $dosen->nidn) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 p-2 rounded"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div id="detail-{{ $dosen->nidn }}"
                            class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
                            <div class="bg-gray-700 p-4 rounded">
                                <h2 class="text-xl font-bold">Detail Dosen</h2>
                                <p>NIDN: {{ $dosen->nidn }}</p>
                                <p>Nama: {{ $dosen->nama_dosen }}</p>
                                <p>Bidang: {{ $dosen->bidang_keilmuan }}</p>
                                <p>Tanggal Mulai Tugas: {{ $dosen->tgl_mulai_tugas }}</p>
                                <p>Jenjang Pendidikan: {{ $dosen->jenjang_pendidikan }}</p>
                                @if ($dosen->foto_dosen)
                                    <img src="{{ asset('storage/' . $dosen->foto_dosen) }}" alt="Foto Dosen"
                                        class="w-24 h-24 object-cover">
                                @endif
                                <button onclick="closeModal('detail-{{ $dosen->nidn }}')"
                                    class="bg-red-500 p-2 mt-2 rounded">Close</button>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div id="edit-{{ $dosen->nidn }}"
                            class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
                            <div class="bg-gray-700 p-4 rounded">
                                <h2 class="text-xl font-bold">Edit Dosen</h2>
                                <form action="{{ route('dosen.update', $dosen->nidn) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="nama_dosen" value="{{ $dosen->nama_dosen }}"
                                        class="bg-gray-600 p-2 rounded w-full mb-2">
                                    <input type="text" name="bidang_keilmuan" value="{{ $dosen->bidang_keilmuan }}"
                                        class="bg-gray-600 p-2 rounded w-full mb-2">
                                    <select name="jenjang_pendidikan" class="bg-gray-600 p-2 rounded w-full mb-2">
                                        <option value="">Pilih Jenjang Pendidikan</option>
                                        <option value="S2"
                                            {{ $dosen->jenjang_pendidikan == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3"
                                            {{ $dosen->jenjang_pendidikan == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                    <input type="date" name="tgl_mulai_tugas" value="{{ $dosen->tgl_mulai_tugas }}"
                                        class="bg-gray-600 p-2 rounded w-full mb-2">
                                    <input type="file" name="foto_dosen" class="bg-gray-600 p-2 rounded w-full mb-2">
                                    <button type="submit" class="bg-yellow-500 p-2 rounded">Update</button>
                                </form>
                                <button onclick="closeModal('edit-{{ $dosen->nidn }}')"
                                    class="bg-red-500 p-2 mt-2 rounded">Close</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center py-4">Tidak dapat menemukan data dosen</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $dosens->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal"
        class="modal hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-gray-700 p-4 rounded">
            <h2 class="text-xl font-bold">Tambah Dosen</h2>
            <form action="{{ route('dosen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="text" name="nidn" placeholder="NIDN" class="bg-gray-600 p-2 rounded w-full mb-2"
                    maxlength="10" pattern="\d*" inputmode="numeric" required>
                <input type="text" name="nama_dosen" placeholder="Nama Dosen"
                    class="bg-gray-600 p-2 rounded w-full mb-2">
                <input type="date" name="tgl_mulai_tugas" placeholder="Tanggal Mulai Tugas"
                    class="bg-gray-600 p-2 rounded w-full mb-2">
                <select name="jenjang_pendidikan" class="bg-gray-600 p-2 rounded w-full mb-2">
                    <option value="">Pilih Jenjang Pendidikan</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </select>
                <input type="text" name="bidang_keilmuan" placeholder="Bidang Keilmuan"
                    class="bg-gray-600 p-2 rounded w-full mb-2">
                <input type="file" name="foto_dosen" class="bg-gray-600 p-2 rounded w-full mb-2">
                <button type="submit" class="bg-green-500 p-2 rounded">Tambah</button>
            </form>
            <button onclick="closeModal('createModal')" class="bg-red-500 p-2 mt-2 rounded">Close</button>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
</body>

</html>
