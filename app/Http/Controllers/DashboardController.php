<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\LogAktivitas;
use App\Models\EspDevice;
use App\Models\LogInvalid;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }
    
    // (opsional) API chart harian berbasis WITA
    public function getChartData(Request $request)
    {
        $days      = (int) $request->query('days', 7);
        $endDate   = Carbon::now('Asia/Makassar')->startOfDay();
        $startDate = $endDate->copy()->subDays($days - 1);

        $labels = [];
        $dataValid = [];
        $dataInvalid = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $tanggal = $date->toDateString();
            $labels[] = $date->format('d M');

            $validCount = LogAktivitas::whereDate('tanggal', $tanggal)
                ->whereHas('mahasiswa')->count();
            $dataValid[] = $validCount;

            $invalidCount = LogInvalid::whereDate('waktu', $tanggal)->count();
            $dataInvalid[] = $invalidCount;
        }

        return response()->json([
            'labels' => $labels,
            'valid'  => $dataValid,
            'invalid'=> $dataInvalid
        ]);
    }
}
