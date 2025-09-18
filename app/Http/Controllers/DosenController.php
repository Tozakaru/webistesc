<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    // Tabel + search + pagination. Modals di-include dari index.
    public function index(Request $r)
    {
        $q = trim((string) $r->get('q', ''));

        $dosens = Dosen::when($q !== '', function ($qq) use ($q) {
                        $qq->where('nama','like',"%{$q}%")
                           ->orWhere('nip','like',"%{$q}%")
                           ->orWhere('uid_rfid','like',"%{$q}%");
                    })
                    ->orderBy('nama')
                    ->paginate(10)
                    ->withQueryString();

        return view('pages.Dosen.index', compact('dosens'));
    }

    // Simpan dari modal "Tambah"
    public function store(Request $r)
    {
        $v = Validator::make($r->all(), [
            'nip'           => 'nullable|string|max:30|unique:dosens,nip',
            'nama'          => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'uid_rfid'      => 'required|string|max:50|unique:dosens,uid_rfid',
            'status_uid'    => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            // kembali ke index + buka modal tambah via marker di view
            return redirect()->route('dosen.index')->withErrors($v)->withInput();
        }

        $data = $v->validated();
        $data['status_uid'] = $r->boolean('status_uid', true);

        Dosen::create($data);

        return redirect()->route('dosen.index')->with('ok','Dosen ditambahkan');
    }

    // Update dari modal "Edit"
    public function update(Request $r, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $v = Validator::make($r->all(), [
            'nip'           => 'nullable|string|max:30|unique:dosens,nip,'.$dosen->id,
            'nama'          => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'uid_rfid'      => 'required|string|max:50|unique:dosens,uid_rfid,'.$dosen->id,
            'status_uid'    => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            // set marker supaya view auto-buka modal edit dengan nilai old()
            return redirect()->route('dosen.index')
                ->withErrors($v)
                ->withInput()
                ->with('edit_dosen_id', $dosen->id);
        }

        $data = $v->validated();
        $data['status_uid'] = $r->boolean('status_uid', true);

        $dosen->update($data);

        return redirect()->route('dosen.index')->with('ok','Dosen diperbarui');
    }

    // Hapus
    public function destroy($id)
    {
        Dosen::findOrFail($id)->delete();
        return back()->with('ok','Dosen dihapus');
    }

    // Aktif/nonaktif UID
    public function toggleUidStatus($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->status_uid = !$dosen->status_uid;
        $dosen->save();
        return back()->with('ok','Status UID diperbarui');
    }

    // Route lama jika ada – redirect ke index (karena pakai modal)
    public function create(){ return redirect()->route('dosen.index'); }
    public function edit($id){ return redirect()->route('dosen.index')->with('edit_dosen_id',$id); }
}
