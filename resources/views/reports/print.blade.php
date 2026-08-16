<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Persuratan - {{ $startDate }} s.d {{ $endDate }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h3 { margin: 0; font-size: 12pt; text-transform: uppercase; }
        .header h2 { margin: 2px 0; font-size: 14pt; text-transform: uppercase; font-weight: bold; }
        .header p { margin: 0; font-size: 8.5pt; font-style: italic; }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        table.grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.grid td, table.grid th {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: top;
        }
        table.grid th {
            background-color: #e6e6e6;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9pt;
            text-align: center;
        }
        .signatures {
            margin-top: 25px;
            width: 100%;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .no-print { margin-bottom: 15px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" style="padding: 8px 16px; font-size: 13px; font-weight: bold; cursor: pointer; background: #0f172a; color: white; border: none; border-radius: 6px;">
            Cetak Laporan Rekapitulasi
        </button>
    </div>

    <div class="header">
        <h3>PEMERINTAH KABUPATEN SUKOHARJO</h3>
        <h2>KECAMATAN SUKOHARJO - KELURAHAN DUKUH</h2>
        <p>Jl. Raya Sukoharjo No. 01 Dukuh, Sukoharjo, Jawa Tengah, Kode Pos 57512</p>
    </div>

    <div class="title">REKAPITULASI AGENDA PERSURATAN & DISPOSISI KELURAHAN</div>
    <p style="text-align:center; margin-top:-10px; margin-bottom:15px; font-size:9.5pt;">
        Periode Tanggal: <strong>{{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }}</strong> s.d. <strong>{{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</strong>
    </p>

    <table class="grid">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">No. Agenda</th>
                <th width="15%">No. Surat / Tgl</th>
                <th width="18%">Pengirim</th>
                <th width="22%">Perihal Surat</th>
                <th width="10%">Kategori</th>
                <th width="12%">Status Disposisi</th>
                <th width="8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($letters as $index => $l)
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td style="font-weight:bold;">{{ $l->agenda_number }}</td>
                    <td>{{ $l->reference_number }}<br><small>Tgl: {{ $l->letter_date->format('d/m/Y') }}</small></td>
                    <td>{{ $l->sender }}</td>
                    <td>{{ $l->subject }}</td>
                    <td>{{ $l->category->name ?? '-' }}</td>
                    <td>
                        @foreach($l->dispositions as $d)
                            <div style="font-size:8.5pt;">• {{ $d->recipient->name }} ({{ $d->status }})</div>
                        @endforeach
                    </td>
                    <td style="text-align:center; font-weight:bold;">{{ $l->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding: 20px;">Tidak ada data persuratan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>LURAH DUKUH</strong><br><br><br><br><br>
                <u>{{ $lurahUser->name ?? 'H. BAMBANG SUTARJO, S.STP, M.Si' }}</u><br>
                NIP. {{ $lurahUser->nip ?? '19780512 199803 1 002' }}
            </td>
            <td>
                Sukoharjo, {{ date('d F Y') }}<br>
                <strong>Petugas Agendaris TU</strong><br><br><br><br><br>
                <u>Siti Aminah, A.Md</u><br>
                NIP. 19900315 201402 2 003
            </td>
        </tr>
    </table>

</body>
</html>
