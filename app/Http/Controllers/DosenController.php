<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Exports\DosenExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class DosenController extends Controller
{
    public function index()
    {
        $dosens = Dosen::paginate(10);  // Menggunakan paginate, bukan all()
        return view('index', compact('dosens'));
    }


    public function create()
    {
        return view('dosen.create');
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validated = $request->validate([
            'nidn' => 'required|unique:dosen|max:10',
            'nama_dosen' => 'required|max:50',
            'tgl_mulai_tugas' => 'required|date',
            'jenjang_pendidikan' => 'required|max:10',
            'bidang_keilmuan' => 'required|max:50',
            'foto_dosen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file gambar
        ]);
    
        // Cek apakah ada file foto yang diupload
        if ($request->hasFile('foto_dosen')) {
            $extension = $request->file('foto_dosen')->getClientOriginalExtension();
    
            // Generate nama file acak dengan panjang 10 karakter
            $fileName = Str::random(10) . '.' . $extension;
    
            // Simpan file di direktori 'dosen_photos' di penyimpanan publik
            $filePath = $request->file('foto_dosen')->storeAs('dosen_photos', $fileName, 'public');
            $validated['foto_dosen'] = $filePath;
        }
    
        // Buat record baru di database
        Dosen::create($validated);
    
        // Redirect ke halaman daftar dosen dengan pesan sukses
        return redirect()->route('dosen.index')->with('success', 'Dosen created successfully.');
    }
    
    public function show($nidn)
    {
        $dosen = Dosen::findOrFail($nidn);
        return view('dosen.show', compact('dosen'));
    }

    public function edit($nidn)
    {
        $dosen = Dosen::findOrFail($nidn);
        return view('dosen.edit', compact('dosen'));
    }

    public function update(Request $request, $nidn)
{
    // Validate the input data
    $validated = $request->validate([
        'nama_dosen' => 'required|max:50',
        'tgl_mulai_tugas' => 'required|date',
        'jenjang_pendidikan' => 'required|max:10',
        'bidang_keilmuan' => 'required|max:50',
        'foto_dosen' => 'nullable|image|max:2048', // Validate file as an image with max size of 2MB
    ]);

    $dosen = Dosen::findOrFail($nidn);

    if ($request->hasFile('foto_dosen')) {
        // Generate random string for file name
        $randomName = Str::random(10) . '.' . $request->file('foto_dosen')->getClientOriginalExtension();

        // Store the file with the random name
        $filePath = $request->file('foto_dosen')->storeAs('dosen_photos', $randomName, 'public');
        $validated['foto_dosen'] = $filePath;

        // Delete the old photo if it exists
        if ($dosen->foto_dosen) {
            Storage::disk('public')->delete($dosen->foto_dosen);
        }
    }

    $dosen->update($validated);

    return redirect()->route('dosen.index')->with('success', 'Dosen updated successfully.');
}

    public function destroy($nidn)
    {
        $dosen = Dosen::findOrFail($nidn);
        $dosen->delete();

        return redirect()->route('dosen.index')->with('success', 'Dosen deleted successfully.');
    }
    
    public function exportPDF()
    {
        // Ambil semua data dosen
        $dosens = Dosen::all();

        // Load view yang akan dijadikan PDF
        $pdf = PDF::loadView('pdf', compact('dosens'));

        // Unduh file PDF dengan nama 'data_dosen.pdf'
        return $pdf->download('data_dosen.pdf');
    }

    public function exportEXCEL()
    {
        return Excel::download(new DosenExport, 'dosen.xlsx');
    }
}
