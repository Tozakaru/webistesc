<div wire:poll.10s>
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <div class="text-muted small">
      <i class="fas fa-clock"></i>
      <span id="live-clock">{{ \Carbon\Carbon::now('Asia/Makassar')->format('d M Y, H:i') }} WITA</span>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-3 col-md-6 mb-4 fade-in-up">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Mahasiswa Terdaftar</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMahasiswa ?? 0 }}</div>
            </div>
            <div class="col-auto"><i class="fas fa-user-graduate fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4 fade-in-up" style="animation-delay:0.1s;">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aktivitas Hari Ini</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Masuk: {{ $jumlahMasuk ?? 0 }}</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Keluar: {{ $jumlahKeluar ?? 0 }}</div>
            </div>
            <div class="col-auto"><i class="fas fa-door-open fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4 fade-in-up" style="animation-delay:0.2s;">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Aktivitas Invalid Hari Ini</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $aktivitasInvalid ?? 0 }} log</div>
            </div>
            <div class="col-auto"><i class="fas fa-user-slash fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4 fade-in-up" style="animation-delay:0.3s;">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-uppercase mb-1">Status Koneksi ESP32</div>
              @foreach($statusEspKelas as $kelas)
                <div class="small mb-1">
                  <strong>{{ $kelas['nama_kelas'] }}</strong>:
                  <span class="text-{{ $kelas['status'] == 'Aktif' ? 'success' : 'danger' }}">{{ $kelas['status'] }}</span>
                </div>
              @endforeach
            </div>
            <div class="col-auto"><i class="fas fa-wifi fa-2x text-gray-300"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-8 col-lg-4 fade-in-up" style="animation-delay:0.4s;">
      <div class="card shadow mb-4 card-border-highlight">
        <div class="card-header card-header-custom py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-white">
            <i class="fas fa-chart-line mr-2"></i>Statistik Validasi Masuk Mahasiswa
          </h6>
          <div class="dropdown no-arrow">
            <select class="form-control form-control-sm"
                    id="trendPeriod"
                    wire:model="chartDays"
                    wire:change="$wire.setChartDays($event.target.value)">
              <option value="7">7 Hari Terakhir</option>
              <option value="30">30 Hari Terakhir</option>
              <option value="90">90 Hari Terakhir</option>
            </select>
          </div>
        </div>
        <div class="card-body">
          <div class="chart-area" wire:ignore>
            <canvas id="trendChart" width="100%" height="40"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-4 col-lg-5 fade-in-up" style="animation-delay:0.5s;">
      <div class="card shadow mb-4 card-border-highlight">
        <div class="card-header card-header-custom py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-history mr-2"></i>Aktivitas Terbaru</h6>
          <small class="text-muted">{{ count($aktivitasTerbaru ?? []) }} aktivitas</small>
        </div>
        <div class="card-body">
          @if(isset($aktivitasTerbaru) && count($aktivitasTerbaru) > 0)
            @foreach($aktivitasTerbaru as $aktivitas)
              <div class="d-flex align-items-center mb-3">
                <div class="mr-3">
                  <div class="icon-circle
                    {{ $aktivitas->jenis == 'masuk' ? 'bg-success' : ($aktivitas->jenis == 'keluar' ? 'bg-danger' : 'bg-info') }}">
                    <span class="text-white font-weight-bold">{{ strtoupper(substr($aktivitas->nama ?? 'N/A', 0, 2)) }}</span>
                  </div>
                </div>
                <div class="flex-grow-1">
                  <div class="small font-weight-bold text-gray-800">{{ $aktivitas->nama ?? 'Nama tidak tersedia' }}</div>
                  <div class="small text-muted">
                    <i class="fas fa-{{ $aktivitas->jenis == 'masuk' ? 'sign-in-alt text-success' : 'sign-out-alt text-danger' }}"></i>
                    {{ ucfirst($aktivitas->jenis ?? 'aktivitas') }}
                    @if(isset($aktivitas->ruangan)) - {{ $aktivitas->ruangan }} @endif
                  </div>
                  <div class="small text-muted"><i class="fas fa-clock"></i> {{ $aktivitas->waktu ?? 'Waktu tidak tersedia' }}</div>
                </div>
              </div>
            @endforeach
          @else
            <div class="text-center py-4">
              <i class="fas fa-clock text-muted mb-2" style="font-size: 48px;"></i>
              <p class="text-muted">Belum ada aktivitas hari ini</p>
              <small class="text-muted">Aktivitas akan muncul setelah mahasiswa melakukan scan RFID</small>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
