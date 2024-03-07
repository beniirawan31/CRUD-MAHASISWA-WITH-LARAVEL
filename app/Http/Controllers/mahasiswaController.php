<?php

namespace App\Http\Controllers;

use App\Models\mahasiswa;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class mahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $katakunci = $request->input('katakunci');
        $jumlahbaris = 2;
        if (strlen($katakunci)) {
            $data = mahasiswa::where('nim', 'like', "%$katakunci")
                ->orwhere('nama', 'like', "%$katakunci")
                ->orwhere('jurusan', 'like', "%$katakunci")
                ->paginate($jumlahbaris);
        } else {
            $data = Mahasiswa::orderBy('nim', 'desc')->paginate($jumlahbaris);
        }
        return view('mahasiswa.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data   = mahasiswa::orderBy('nim', 'desc')->get();

        return view('mahasiswa.create')->with('data', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        session()->flash('nim', $request->nim);
        session()->flash('nama', $request->nama);
        session()->flash('jurusan', $request->jurusan);


        $request->validate([
            'nim' => 'required|numeric|unique:mahasiswa,nim',
            'nama' => 'required',
            'jurusan' => 'required',
        ], [
            'nim.required' => 'Nim Wajib Di isi',
            'nim.unique' => 'Nim sudah ada di Databases',
            'nama.required' => 'Nama Wajib Di isi',
            'jurusan.required' => 'Jurusan Wajib Di isi',
        ]);

        $data = [
            'nim' => $request->nim,
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ];

        mahasiswa::create($data);
        return redirect()->to('mahasiswa')->with('success', 'Berhasil Menambahkan data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = mahasiswa::where('nim', $id)->first();

        return view('mahasiswa.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required',
            'jurusan' => 'required',
        ], [
            'nama.required' => 'Nama Wajib Di isi',
            'jurusan.required' => 'Jurusan Wajib Di isi',
        ]);

        $data = [
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ];

        mahasiswa::where('nim', $id)->update($data);
        return redirect()->to('mahasiswa')->with('success', 'Berhasil Edit data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        mahasiswa::where('nim', $id)->delete();

        return redirect()->to('mahasiswa')->with('success', 'Berhasil Hapus Data');
    }
}
