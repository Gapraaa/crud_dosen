<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'nidn' => 'required|unique:dosen|max:10',
            'nama_dosen' => 'required|max:50',
            'tgl_mulai_tugas' => 'required|date',
            'jenjang_pendidikan' => 'required|max:10',
            'bidang_keilmuan' => 'required|max:50',
            'foto_dosen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_dosen')) {
            $originalName = pathinfo($request->file('foto_dosen')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->file('foto_dosen')->getClientOriginalExtension();

            // Trim the filename to a reasonable length (e.g., 40 characters to keep within 50 characters total)
            $trimmedName = substr($originalName, 0, 40);

            // Generate the full filename with timestamp and extension
            $fileName = $trimmedName . '_' . time() . '.' . $extension;

            // Save in 'dosen_photos' directory
            $filePath = $request->file('foto_dosen')->storeAs('dosen_photos', $fileName, 'public');
            $validated['foto_dosen'] = $filePath;
        }

        Dosen::create($validated);

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
            $filePath = $request->file('foto_dosen')->store('dosen_photos', 'public');
            $validated['foto_dosen'] = $filePath;
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
}
