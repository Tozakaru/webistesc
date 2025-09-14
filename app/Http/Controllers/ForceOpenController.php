<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForceOpenController extends Controller
{
    /**
     * Terima submit dari modal Force Open.
     * - Hanya Admin (role_id = 1)
     * - Insert command sekali tembak (expires_at 15s)
     */
    public function execute(Request $request)
    {
        if ($request->user()->role_id !== 1) {
            abort(403);
        }

        $data = $request->validate([
            'ruangan' => 'required|in:ruangan1,ruangan2',
        ]);

        DB::table('esp_commands')->insert([
            'device_code' => $data['ruangan'],
            'command'     => 'FORCE_OPEN',
            'payload'     => json_encode(['duration_ms' => 3000]),
            'status'      => 'pending',
            'issued_by'   => $request->user()->id,
            'expires_at'  => now()->addSeconds(15), // <-- hangus cepat
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return back()->with('success', 'Perintah dikirim. Menunggu perangkat mengambil perintah.');
    }
}
