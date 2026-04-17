<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Antrian - {{ $antrian->kode_antrian }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; }
        .tiket {
            width: 280px; margin: 20px auto; border: 2px solid #003580;
            border-radius: 8px; padding: 16px; text-align: center;
        }
        .header { background: #003580; color: white; padding: 10px; margin: -16px -16px 16px; border-radius: 6px 6px 0 0; }
        .nomor { font-size: 56px; font-weight: bold; color: #003580; margin: 10px 0; }
        .info  { font-size: 13px; color: #555; }
        .footer { margin-top: 12px; font-size: 11px; color: #888; }
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
<div class="tiket">
    <div class="header">
        <strong>PST BPS Kota Jambi</strong>
    </div>
    <div class="info">{{ $antrian->jenisLayanan->nama_layanan }}</div>
    <div class="nomor">{{ $antrian->kode_antrian }}</div>
    <div class="info">{{ $antrian->nama_pengunjung }}</div>
    <div class="info">{{ $antrian->tanggal->format('d M Y') }}</div>
    @if($menunggu > 0)
    <div style="margin-top:10px;padding:8px;background:#fff3cd;border-radius:6px;font-size:12px;">
        ⏳ <strong>{{ $menunggu }}</strong> antrian di depan Anda
    </div>
    @endif
    <div class="footer">Terima kasih atas kunjungan Anda</div>
</div>

<div class="no-print" style="text-align:center; margin-top:16px;">
    <button onclick="window.print()"
        style="background:#003580;color:white;border:none;padding:10px 24px;border-radius:6px;cursor:pointer;font-size:14px;">
        🖨️ Cetak Tiket
    </button>
    <a href="{{ route('antrian.display') }}"
        style="display:block;margin-top:8px;color:#003580;font-size:13px;">
        📺 Lihat Display Antrian
    </a>
</div>
</body>
</html>