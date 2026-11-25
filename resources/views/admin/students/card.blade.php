<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kartu Masuk Siswa - {{ $student->name }}</title>
  <link rel="stylesheet" href="/css/tokens.css">
  <style>
    @page { size: A6 landscape; margin: 10mm; }
    * { box-sizing: border-box; }
    body {
      font-family: 'Lexend', system-ui, -apple-system, sans-serif;
      margin: 0;
      padding: 24px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 92vh;
      background: #FBF8F3;
      color: #1F2933;
    }
    .login-card-print {
      width: 132mm;
      height: 84mm;
      background: #FFFFFF;
      border: 2.5px dashed #2F6F9F;
      border-radius: 18px;
      padding: 18px 22px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      text-align: center;
      box-shadow: 0 10px 28px rgba(31, 41, 51, 0.08);
    }
    .header-band {
      padding-bottom: 10px;
      border-bottom: 1.5px solid #D9D2C5;
    }
    .header { font-size: 13px; font-weight: 700; color: #2F6F9F; letter-spacing: 0.8px; }
    .school { font-size: 11px; color: #4B5563; margin-top: 2px; }
    .name { font-size: 20px; font-weight: 700; margin: 4px 0; color: #1F2933; }
    .class-tag { font-size: 11px; color: #4B5563; margin-bottom: 8px; }
    .username-box {
      font-size: 13px;
      color: #1A4B70;
      background: #DCEBF7;
      padding: 6px 14px;
      border-radius: 999px;
      display: inline-block;
    }
    .pin-label { font-size: 11px; color: #4B5563; margin-top: 10px; }
    .pin-code {
      font-size: 28px;
      font-weight: 700;
      letter-spacing: 6px;
      color: #1B4332;
      margin: 2px 0;
    }
    .footer { font-size: 10px; color: #4B5563; border-top: 1px solid #F3EEE5; padding-top: 8px; }
    @media print {
      body { background: #FFFFFF; padding: 0; }
      .no-print { display: none; }
      .login-card-print { border-style: solid; box-shadow: none; }
    }
  </style>
</head>
<body>
  <div style="position: fixed; top: 18px; right: 18px;" class="no-print">
    <button onclick="window.print()" style="padding: 10px 18px; font-weight: 700; cursor: pointer; border-radius: 10px; border: 1.5px solid #1B4332; background: #143326; color: #FFFFFF;">
      Cetak Kartu Ini
    </button>
  </div>

  <div class="login-card-print">
    <div class="header-band">
      <div class="header">KARTU MASUK SAHABAT BELAJAR</div>
      <div class="school">SLB B-C Wijayakusumah &bull; Sumedang</div>
    </div>

    <div>
      <div class="name">{{ $student->name }}</div>
      <div class="class-tag">{{ $student->class_name ?? 'Ruang Belajar Mandiri' }}</div>
      <div class="username-box">Nama Pengguna: <b>{{ $student->username }}</b></div>
      <div class="pin-label">PIN Angka Masuk:</div>
      <div class="pin-code">{{ session('generated_pin') ?? '••••' }}</div>
    </div>

    <div class="footer">
      Simpan kartu ini untuk masuk ke ruang belajar pada perangkat sekolah maupun di rumah.
    </div>
  </div>
</body>
</html>
