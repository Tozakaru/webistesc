<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\EspDevice;

class ForceOpenController extends Controller
{
    public function execute(Request $request)
    {
        if (!in_array($request->user()->role_id, [1, 2], true)) {
            abort(403);
        }

        // Validasi input memilih 'code'
        $validated = $request->validate([
            'ruangan' => 'required|in:ruangan1,ruangan2',
        ]);
        $code = $validated['ruangan'];

        // Temukan device berdasarkan 'code'
        $device = EspDevice::where('code', $code)->firstOrFail();

        $now = now();
        $data = [
            'esp_device_id' => $device->id,
            'command'       => 'FORCE_OPEN',
            'payload'       => json_encode(['duration_ms' => 3000]),
            'status'        => 'pending',
            'issued_by'     => $request->user()->id,
            'expires_at'    => $now->copy()->addSeconds(15),
            'created_at'    => $now,
            'updated_at'    => $now,
        ];

        // kompatibilitas bila kolom lama masih ada
        if (Schema::hasColumn('esp_commands', 'device_code')) {
            $data['device_code'] = $device->code; // "SmartClass 1/2"
        }

        DB::table('esp_commands')->insert($data);

        return back()->with('success', 'Perintah dikirim. Menunggu perangkat mengambil perintah.');
    }
}
