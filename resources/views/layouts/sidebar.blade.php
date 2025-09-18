@php
    $menus = [
        1 => [
            (object)['title'=>'Dashboard','path'=>'dashboard','icon'=>'fas fa-fw fa-tachometer-alt'],
            (object)['title'=>'Log Aktivitas','path'=>'logaktivitas','icon'=>'fas fa-list fa-sm'],
            (object)['title'=>'Aktivitas Invalid','path'=>'aktivitas-invalid','icon'=>'fas fa-exclamation-triangle'],
            (object)['title'=>'Mahasiswa','path'=>'mahasiswa','icon'=>'fas fa-solid fa-table'], // collapse Data Pengguna
            (object)['title'=>'Rekapan Aktivitas','path'=>'rekapanaktivitas','icon'=>'fas fa-solid fa-folder'],
            (object)['title'=>'Daftar Akun','path'=>'account-list','icon'=>'fas fa-solid fa-user'],
            (object)['title'=>'Force Open','path'=>'force-open','icon'=>'fas fa-exclamation-triangle'],
        ],
        2 => [
            (object)['title'=>'Dashboard','path'=>'dashboard','icon'=>'fas fa-fw fa-tachometer-alt'],
            (object)['title'=>'Log Aktivitas','path'=>'logaktivitas','icon'=>'fas fa-list fa-sm'],
            (object)['title'=>'Force Open','path'=>'force-open','icon'=>'fas fa-exclamation-triangle'],
        ],
    ];

    function isMenuActive($path) {
        return request()->is($path.'*') ? 'active' : '';
    }

    // STATE untuk Log Aktivitas (ruangan)
    $isLogRuangan = request()->routeIs('log.ruangan1') || request()->routeIs('log.ruangan2');

    // STATE untuk collapse "Mahasiswa" (alias Data Pengguna)
    $isPeople = request()->is('mahasiswa*') || request()->is('dosen*');

    // Ambil daftar device untuk modal (code + nama_kelas)
    $devices = \App\Models\EspDevice::orderBy('code')->get(['code','nama_kelas']);
@endphp

<link href="https://fonts.googleapis.com/css2?family=League+Spartan&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('template/css/mahasiswa.css') }}">

<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
    {{-- Brand --}}
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand-icon rotate-n-15"></div>
        <div class="sidebar-brand-text mx-3">SmartClass</div>
    </a>

    <hr class="sidebar-divider my-0">

    @auth
        @foreach ($menus[auth()->user()->role_id] as $menu)
            @if ($menu->title === 'Log Aktivitas')
                {{-- LOG AKTIVITAS (collapse ruangan) --}}
                <li class="nav-item">
                    <a class="nav-link collapsed"
                       href="#"
                       data-bs-toggle="collapse"
                       data-bs-target="#collapseLogAktivitas"
                       aria-expanded="{{ $isLogRuangan ? 'true' : 'false' }}"
                       aria-controls="collapseLogAktivitas">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->title }}</span>
                    </a>

                    <div id="collapseLogAktivitas"
                         class="collapse {{ $isLogRuangan ? 'show' : '' }}"
                         aria-labelledby="headingLogAktivitas"
                         data-bs-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Pilih Ruangan:</h6>

                            <a class="collapse-item {{ request()->routeIs('log.ruangan1') ? 'active' : '' }}"
                               href="{{ route('log.ruangan1') }}">
                                SmartClass 1
                            </a>

                            <a class="collapse-item {{ request()->routeIs('log.ruangan2') ? 'active' : '' }}"
                               href="{{ route('log.ruangan2') }}">
                                SmartClass 2
                            </a>
                        </div>
                    </div>
                </li>

                <hr class="sidebar-divider">

            @elseif ($menu->title === 'Rekapan Aktivitas')
                {{-- REKAP --}}
                <li class="nav-item {{ isMenuActive($menu->path) }}">
                    <a class="nav-link" href="/{{ $menu->path }}">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->title }}</span>
                    </a>
                </li>

                <hr class="sidebar-divider">

            @elseif ($menu->title === 'Mahasiswa')
                {{-- MAHASISWA → collapse Data Mahasiswa & Data Dosen --}}
                <li class="nav-item">
                    <a class="nav-link collapsed"
                       href="#"
                       data-bs-toggle="collapse"
                       data-bs-target="#collapsePeople"
                       aria-expanded="{{ $isPeople ? 'true' : 'false' }}"
                       aria-controls="collapsePeople">
                        <i class="{{ $menu->icon }}"></i>
                        <span>Data Pengguna</span>
                    </a>

                    <div id="collapsePeople"
                         class="collapse {{ $isPeople ? 'show' : '' }}"
                         data-bs-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Pilih Data:</h6>

                            <a class="collapse-item {{ request()->is('mahasiswa*') ? 'active' : '' }}"
                               href="{{ route('mahasiswa.index') }}">
                                Data Mahasiswa
                            </a>

                            <a class="collapse-item {{ request()->is('dosen*') ? 'active' : '' }}"
                               href="{{ route('dosen.index') }}">
                                Data Dosen
                            </a>
                        </div>
                    </div>
                </li>

                <hr class="sidebar-divider">

            @elseif ($menu->title === 'Force Open')
                {{-- FORCE OPEN: modal --}}
                <li class="nav-item">
                    <a class="nav-link" href="#"
                       data-bs-toggle="modal"
                       data-bs-target="#forceOpenModal">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->title }}</span>
                    </a>
                </li>

            @else
                {{-- MENU UMUM --}}
                <li class="nav-item {{ isMenuActive($menu->path) }}">
                    <a class="nav-link" href="/{{ $menu->path }}">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->title }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    @endauth

    <hr class="sidebar-divider d-none d-md-block">

    {{-- Sidebar Toggler --}}
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

{{-- Modal Force Open (dinamis dari esp_devices) --}}
<div class="modal fade" id="forceOpenModal" tabindex="-1" aria-labelledby="forceOpenLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-body text-center p-4">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center force-box">
          <i class="fas fa-door-open fa-lg"></i>
        </div>
        <h5 id="forceOpenLabel" class="fw-bold mb-2">Konfirmasi Paksa Buka Pintu</h5>
        <p class="text-muted mb-4">
          Anda yakin ingin membuka pintu secara paksa?<br/>
          Tindakan ini akan dicatat dalam log sistem.
        </p>

        <form method="POST" action="{{ route('force-open.execute') }}" id="forceOpenForm" class="mb-2">
          @csrf

          {{-- Pilih device (kirimkan "code" ke controller) --}}
          <div class="mb-3">
            <label class="form-label fw-semibold">Pilih SmartClass</label>
            <div class="d-flex flex-wrap gap-4 justify-content-center">
              @forelse($devices as $i => $dev)
                <div class="form-check">
                  <input class="form-check-input"
                         type="radio"
                         name="ruangan"
                         id="dev-{{ $dev->code }}"
                         value="{{ $dev->code }}"
                         {{ $i === 0 ? 'checked' : '' }}>
                  <label class="form-check-label" for="dev-{{ $dev->code }}">
                    {{ $dev->nama_kelas }}
                  </label>
                </div>
              @empty
                <div class="text-danger small">
                  Tidak ada device terdaftar. Tambahkan di menu admin terlebih dahulu.
                </div>
              @endforelse
            </div>

            @error('ruangan')
              <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger px-4" id="forceOpenSubmit">Ya, Buka Pintu</button>
          </div>
        </form>

        @if (session('success'))
          <div class="alert alert-success mt-3 mb-0 py-2 px-3">
            {{ session('success') }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<style>
  .force-box{ width:56px;height:56px;border:2px solid #ff6b6b;border-radius:14px; }
  .force-box i{ color:#ff6b6b; }
</style>

{{-- optional: cegah double-submit --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('forceOpenForm');
  const btn  = document.getElementById('forceOpenSubmit');
  if (form && btn) {
    form.addEventListener('submit', function () {
      btn.disabled = true;
      btn.innerText = 'Mengirim...';
    });
  }
});
</script>
