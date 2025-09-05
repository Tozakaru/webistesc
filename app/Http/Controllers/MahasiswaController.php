<?php

namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('pages.mahasiswa.index');
    }
    

    public function create()
    {
        return view('pages.mahasiswa.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nim' => ['required', 'max:10'],
            'nama' => ['required', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in('laki-laki', 'perempuan')],
            'kelas' => ['required', 'max:10'],
            'uid_rfid' => ['required', 'max:50'],
        ]);

        Mahasiswa::create($validatedData);

        return redirect('/mahasiswa')->with('success', 'Berhasil menambahkan data');
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        return view('pages.mahasiswa.edit', [
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nim' => ['required', 'max:10'],
            'nama' => ['required', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in('laki-laki', 'perempuan')],
            'kelas' => ['required', 'max:10'],
            'uid_rfid' => ['required', 'max:50'],
        ]);

        Mahasiswa::findOrFail($id)->update($validatedData);

        return redirect('/mahasiswa')->with('success', 'Berhasil mengubah data');
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return redirect('/mahasiswa')->with('success', 'Berhasil menghapus data');
    }

    public function toggleUidStatus($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->status_uid = !$mahasiswa->status_uid;
        $mahasiswa->save();

        return redirect()->back()->with('success', 'Status UID berhasil diubah.');
    }

} 