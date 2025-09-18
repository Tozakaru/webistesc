<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EspDevice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EspDeviceController extends Controller
{
    /**
     * Firmware POST JSON: {"nama_kelas":"SmartClass 1"}
     * Response: {"device":"ruangan1","commands":[{id,type,payload}]}
     */
    public function heartbeat(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => 'required|string|max:100',
        ]);

        $now        = Carbon::now('Asia/Makassar');
        $namaKelas  = $data['nama_kelas'];
        $deviceCode = $this->mapDeviceCode($namaKelas); // ruangan1/ruangan2

        EspDevice::updateOrCreate(
            ['nama_kelas' => $namaKelas],
            ['last_seen'  => $now]
        );

        DB::table('esp_commands')
            ->where('device_code', $deviceCode)
            ->where('status', 'sent')
            ->where('command', '!=', 'FORCE_OPEN')
            ->where('sent_at', '<', $now->copy()->subSeconds(10))
            ->where(function ($q) {
                $q->whereNull('retry_count')->orWhere('retry_count', '<', 3);
            })
            ->update([
                'status'      => 'pending',
                'updated_at'  => $now,
                'retry_count' => DB::raw('COALESCE(retry_count,0) + 1'),
            ]);

        // Ambil 1 pending (belum expired)
        $cmd = DB::table('esp_commands')
            ->where('device_code', $deviceCode)
            ->where('status', 'pending')
            ->where(function($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', $now);
            })
            ->orderBy('id')
            ->first();

        if ($cmd) {
            DB::table('esp_commands')->where('id', $cmd->id)->update([
                'status'     => 'sent',
                'sent_at'    => $now,
                'updated_at' => $now,
            ]);
        }

        return response()->json([
            'device'   => $deviceCode,
            'commands' => $cmd ? [[
                'id'      => $cmd->id,
                'type'    => $cmd->command,
                'payload' => $cmd->payload ? json_decode($cmd->payload, true) : null,
            ]] : [],
        ], 200);
    }

    /**
     * ACK setelah ESP eksekusi
     * body: { "id": 123, "status": "done" | "failed" }
     */
    public function ack(Request $request)
    {
        $data = $request->validate([
            'id'     => 'required|integer',
            'status' => 'required|in:done,failed',
        ]);

        DB::table('esp_commands')->where('id', $data['id'])->update([
            'status'      => $data['status'],
            'executed_at' => $data['status'] === 'done' ? Carbon::now('Asia/Makassar') : null,
            'updated_at'  => Carbon::now('Asia/Makassar'),
        ]);

        return response()->json(['ok' => true]);
    }

    /** Map nama_kelas -> device_code */
    private function mapDeviceCode(string $namaKelas): string
    {
        $n = Str::lower($namaKelas);
        if (Str::contains($n, ['smartclass 1','ruangan 1','ruang 1'])) return 'ruangan1';
        if (Str::contains($n, ['smartclass 2','ruangan 2','ruang 2'])) return 'ruangan2';
        return 'ruangan1';
    }
}
