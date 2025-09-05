<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EspDevice;
use Carbon\Carbon;

class EspDeviceController extends Controller
{
    public function updateStatus(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => 'required|string|max:100',
        ]);

        EspDevice::updateOrCreate(
            ['nama_kelas' => $data['nama_kelas']],
            ['last_seen'  => Carbon::now('Asia/Makassar')]
        );

        return response()->noContent(); // 204
    }
}
