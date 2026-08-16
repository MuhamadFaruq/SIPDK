<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Agenda Surat - {{ $letter->agenda_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            margin: 2px 0;
            font-size: 16pt;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 10pt;
            font-style: italic;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        table.grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.grid td, table.grid th {
            border: 1px solid #000;
            padding: 8px 12px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 30%;
            background-color: #f2f2f2;
        }
        .signatures {
            margin-top: 40px;
            width: 100%;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .no-print {
            margin-bottom: 20px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" style="padding: 8px 16px; font-size: 13px; font-weight: bold; cursor: pointer; background: #0f172a; color: white; border: none; border-radius: 6px;">
            Cetak Lembar Agenda
        </button>
    </div>

    <!-- Kop Surat Kelurahan -->
    <div class="header">
        <h3>PEMERINTAH KABUPATEN SUKOHARJO</h3>
        <h2>KECAMATAN SUKOHARJO - KELURAHAN DUKUH</h2>
        <p>Jl. Raya Sukoharjo No. 01 Dukuh, Sukoharjo, Jawa Tengah, Kode Pos 57512</p>
    </div>

    <div class="title">LEMBAR AGENDA SURAT MASUK</div>

    <table class="grid">
        <tr>
            <td class="label">NOMOR AGENDA REGISTER</td>
            <td><strong>{{ $letter->agenda_number }}</strong></td>
        </tr>
        <tr>
            <td class="label">TANGGAL DITERIMA TU</td>
            <td>{{ $letter->received_date->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td class="label">NOMOR SURAT FISIK</td>
            <td>{{ $letter->reference_number }}</td>
        </tr>
        <tr>
            <td class="label">TANGGAL SURAT</td>
            <td>{{ $letter->letter_date->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td class="label">ASAL PENGIRIM</td>
            <td>{{ $letter->sender }}</td>
        </tr>
        <tr>
            <td class="label">PERIHAL SURAT</td>
            <td><strong>{{ $letter->subject }}</strong></td>
        </tr>
        <tr>
            <td class="label">KATEGORI & SIFAT</td>
            <td>{{ $letter->category->name ?? '-' }} / Sifat: {{ $letter->degree }}</td>
        </tr>
        <tr>
            <td class="label">RINGKASAN ISI SURAT</td>
            <td>{{ $letter->summary ?? 'Tidak ada ringkasan tertulis.' }}</td>
        </tr>
        <tr>
            <td class="label">PETUGAS TU AGENDARIS</td>
            <td>{{ $letter->creator->name ?? '-' }} (NIP: {{ $letter->creator->nip ?? '-' }})</td>
        </tr>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Sekretaris Kelurahan</strong><br><br><br><br><br>
                <u>Drs. Rahmat Hidayat</u><br>
                NIP. 19800720 200501 1 005
            </td>
            <td>
                Sukoharjo, {{ date('d F Y') }}<br>
                <strong>Petugas Agenda TU</strong><br><br><br><br><br>
                <u>{{ $letter->creator->name ?? 'Siti Aminah, A.Md' }}</u><br>
                NIP. {{ $letter->creator->nip ?? '-' }}
            </td>
        </tr>
    </table>

</body>
</html>
