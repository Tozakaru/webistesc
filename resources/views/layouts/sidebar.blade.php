@php
    $menus = [
        1 => [
            (object) [
                'title' => 'Dashboard',
                'path' => 'dashboard',
                'icon' => 'fas fa-fw fa-tachometer-alt',
            ],
            (object) [
                'title' => 'Log Aktivitas',
                'path' => 'logaktivitas',
                'icon' => 'fas fa-list fa-sm',
            ],
            (object) [
                'title' => 'Aktivitas Invalid',
                'path' => 'aktivitas-invalid',
                'icon' => 'fas fa-exclamation-triangle',
            ],
            (object) [
                'title' => 'Mahasiswa',
                'path' => 'mahasiswa',
                'icon' => 'fas fa-solid fa-table',
            ],
            (object) [
                'title' => 'Rekapan Aktivitas',
                'path' => 'rekapanaktivitas',
                'icon' => 'fas fa-solid fa-folder',
            ],
            (object) [
                'title' => 'Daftar Akun',
                'path' => 'account-list',
                'icon' => 'fas fa-solid fa-user',
            ],
        ],
        2 => [
                (object) [
                    'title' => 'Dashboard',
                    'path' => 'dashboard',
                    'icon' => 'fas fa-fw fa-tachometer-alt',
                ],
                (object) [
                    'title' => 'Log Aktivitas',
                    'path' => 'logaktivitas',
                    'icon' => 'fas fa-list fa-sm',
                ],
            ],
    ];
  // Helper untuk active menu umum
    function isMenuActive($path) {
        return request()->is($path . '*') ? 'active' : '';
    }

    // State untuk menu Log Aktivitas (ruangan)
    $isLogRuangan = request()->routeIs('log.ruangan');
    $ruanganParam = $isLogRuangan ? request()->route('ruangan') : null;
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
                       aria-expanded="{{ ($isLogRuangan || request()->is('logaktivitas*')) ? 'true' : 'false' }}"
                       aria-controls="collapseLogAktivitas">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->title }}</span>
                    </a>

                    <div id="collapseLogAktivitas"
                         class="collapse {{ ($isLogRuangan || request()->is('logaktivitas*')) ? 'show' : '' }}"
                         aria-labelledby="headingLogAktivitas"
                         data-bs-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Pilih Ruangan:</h6>

                            <a class="collapse-item
                                      {{ ($isLogRuangan && $ruanganParam === 'ruangan1') ? 'active' : '' }}
                                      {{ request()->is('logaktivitas/ruangan1') ? 'active' : '' }}"
                               href="{{ route('log.ruangan', ['ruangan' => 'ruangan1']) }}">
                                SmartClass 1
                            </a>

                            <a class="collapse-item
                                      {{ ($isLogRuangan && $ruanganParam === 'ruangan2') ? 'active' : '' }}
                                      {{ request()->is('logaktivitas/ruangan2') ? 'active' : '' }}"
                               href="{{ route('log.ruangan', ['ruangan' => 'ruangan2']) }}">
                                SmartClass 2
                            </a>
                        </div>
                    </div>
                </li>

                <hr class="sidebar-divider">

            @elseif ($menu->title === 'Rekapan Aktivitas')
                {{-- REKAPAN AKTIVITAS (single link) --}}
                <li class="nav-item {{ isMenuActive($menu->path) }}">
                    <a class="nav-link" href="/{{ $menu->path }}">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->title }}</span>
                    </a>
                </li>

                <hr class="sidebar-divider">

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