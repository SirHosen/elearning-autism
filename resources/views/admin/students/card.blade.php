<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kartu Masuk Siswa - {{ $student->name }}</title>
  <style>
    @page { size: A6 landscape; margin: 10mm; }
    body { font-family: sans-serif; margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 90vh; background: #fafafa; }
    .card {
      width: 130mm; height: 80mm; background: #fff; border: 2px dashed #3275c5; border-radius: 16px;
      padding: 16px; box-sizing: border-box; display: flex; flex-direction: column; justify-content: space-between;
      text-align: center;
    }
    .header { font-size: 14px; font-weight: bold; color: #3275c5; }
    .school { font-size: 11px; color: #666; margin-top: 2px; }
    .name { font-size: 20px; font-weight: bold; margin: 8px 0 2px; color: #222; }
    .username-box { font-size: 14px; color: #444; background: #edf5ff; padding: 6px; border-radius: 8px; display: inline-block; margin: 0 auto; }
    .pin-label { font-size: 11px; color: #888; margin-top: 8px; }
    .pin-code { font-size: 28px; font-weight: 900; letter-spacing: 6px; color: #2e7d5b; margin: 2px 0; }
    .footer { font-size: 10px; color: #999; }
    @media print {
      body { background: #fff; padding: 0; }
      .no-print { display: none; }
      .card { border-style: solid; }
    }
  </style>
</head>
<body>
  <div style="position: absolute; top: 16px; right: 16px;" class="no-print">
    <button onclick="window.print()" style="padding: 10px 16px; font-weight: bold; cursor: pointer; border-radius: 8px; border: 1px solid #ccc; background: #fff;">
      🖨 Cetak Kartu Ini
    </button>
  </div>

  <div class="card">
    <div>
      <div class="header">KARTU MASUK RUANG BELAJAR EDUAUTIS</div>
      <div class="school">SLB B-C Wijayakusumah</div>
    </div>

    <div>
      <div class="name">{{ $student->name }}</div>
      <div class="username-box">Nama Pengguna: <b>{{ $student->username }}</b></div>
      <div class="pin-label">PIN Masuk:</div>
      <div class="pin-code">{{ session('generated_pin') ?? '••••' }}</div>
    </div>

    <div class="footer">
      Simpan kartu ini untuk masuk ke halaman belajar di komputer atau tablet sekolah.
    </div>
  </div>
</body>
</html>
