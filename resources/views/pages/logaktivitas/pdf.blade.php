<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Laporan Log Aktivitas</title>
    <link rel="stylesheet" href="{{ public_path('template/css/pdf.css') }}">
  </head>
<body>

<div class="pdf-header">
  <table class="header-table">
    <tr class="header-top">
      <td class="hdr-logo">
        @if(!empty($logoPath) && file_exists($logoPath))
          <img src="{{ $logoPath }}" alt="Logo" style="height:48px;">
        @endif
      </td>
      <td class="hdr-title" colspan="4">POLITEKNIK NEGERI MANADO</td>
      <td class="hdr-q">Q</td>
    </tr>
    <tr class="header-meta">
      <td>FORMULIR</td>
      <td>{{ $meta['kode'] ?? 'FM-198 sd.A rev:0' }}</td>
      <td>ISSUE: {{ $meta['issue'] ?? 'A' }}</td>
      <td>Issued: {{ $meta['tgl_efektif'] ?? '—' }}</td>
      <td>UPDATE: {{ $meta['update'] ?? '0' }}</td>
      <td>Updated: {{ $meta['updated_at'] ?? '00-00-0000' }}</td>
    </tr>
  </table>
</div>

<div class="pdf-footer">
  <table class="footer-table">
    <tr>
      <td class="footer-left">Dicetak: {{ now()->format('d/m/Y H:i') }}</td>
      <td class="footer-right">Halaman <span class="pagenum"></span> / <span class="pagetotal"></span></td>
    </tr>
  </table>
</div>

<main>
  <h3>Laporan Log Aktivitas — {{ $judulPeriode }}</h3>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Role</th>
        <th>Nama</th>
        <th>NIM/NIP</th>
        <th>Tanggal</th>
        <th>Ruangan</th>
        <th>Waktu Masuk</th>
        <th>Waktu Keluar</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($logs as $i => $log)
        @php
          $isMhs = !is_null($log->mahasiswa_id);
          $role  = $isMhs ? 'Mahasiswa' : 'Dosen';
          $nama  = $isMhs ? ($log->mahasiswa->nama ?? '-') : ($log->dosen->nama ?? '-');
          $idno  = $isMhs ? ($log->mahasiswa->nim  ?? '-') : ($log->dosen->nip   ?? '-');
        @endphp
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $role }}</td>
          <td>{{ $nama }}</td>
          <td>{{ $idno }}</td>
          <td>{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}</td>
          <td>{{ $log->ruangan }}</td>
          <td>{{ $log->waktu_masuk  ?? '-' }}</td>
          <td>{{ $log->waktu_keluar ?? '-' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</main>

</body>
</html>
