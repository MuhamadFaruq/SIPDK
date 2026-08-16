<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Disposisi - {{ $letter->agenda_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
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
        .header h3 {
            margin: 0;
            font-size: 13pt;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 2px 0;
            font-size: 15pt;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 9pt;
            font-style: italic;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            text-decoration: underline;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        table.border-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.border-table td, table.border-table th {
            border: 1px solid #000;
            padding: 6px 10px;
            vertical-align: top;
        }
        .box-title {
            font-weight: bold;
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 10pt;
        }
        .checkbox-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .box {
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            display: inline-block;
            text-align: center;
            line-height: 12px;
            font-size: 10px;
            font-weight: bold;
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
        .no-print {
            margin-bottom: 15px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" style="padding: 8px 16px; font-size: 13px; font-weight: bold; cursor: pointer; background: #0f172a; color: white; border: none; border-radius: 6px;">
            Cetak Lembar Disposisi
        </button>
    </div>

    <!-- Kop Surat -->
    <div class="header">
        <h3>PEMERINTAH KABUPATEN SUKOHARJO</h3>
        <h2>KECAMATAN SUKOHARJO - KELURAHAN DUKUH</h2>
        <p>Jl. Raya Sukoharjo No. 01 Dukuh, Sukoharjo, Jawa Tengah, Kode Pos 57512</p>
    </div>

    <div class="title">LEMBAR DISPOSISI LURAH</div>

    <table class="border-table">
        <tr>
            <td width="50%">
                <strong>Surat Dari:</strong> {{ $letter->sender }}<br>
                <strong>No. Surat:</strong> {{ $letter->reference_number }}<br>
                <strong>Tgl. Surat:</strong> {{ $letter->letter_date->isoFormat('D MMMM Y') }}
            </td>
            <td width="50%">
                <strong>Diterima Tgl:</strong> {{ $letter->received_date->isoFormat('D MMMM Y') }}<br>
                <strong>No. Agenda:</strong> <u>{{ $letter->agenda_number }}</u><br>
                <strong>Sifat:</strong> [ {{ $letter->degree == 'Biasa' ? 'X' : ' ' }} ] Biasa &nbsp; [ {{ $letter->degree == 'Penting' ? 'X' : ' ' }} ] Penting &nbsp; [ {{ $letter->degree == 'Sangat Segera' ? 'X' : ' ' }} ] Segera
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Perihal / Ringkasan Isi Surat:</strong><br>
                <strong>{{ $letter->subject }}</strong>
                <p style="margin: 4px 0 0 0; font-size:10pt; font-style:italic;">{{ $letter->summary ?? '-' }}</p>
            </td>
        </tr>
    </table>

    <table class="border-table">
        <tr>
            <th class="box-title" width="45%">DITERUSKAN KEPADA KASI / KAUR:</th>
            <th class="box-title" width="55%">PETUNJUK / DISPOSISI LURAH:</th>
        </tr>
        <tr>
            <td>
                <div class="checkbox-grid">
                    @foreach($departments as $dept)
                        @php
                            $isTarget = $letter->dispositions->pluck('recipient_department_id')->contains($dept->id);
                        @endphp
                        <div class="checkbox-item">
                            <span class="box">{{ $isTarget ? '✓' : '' }}</span>
                            <span>{{ $dept->head_title ?? $dept->name }}</span>
                        </div>
                    @endforeach
                </div>
            </td>
            <td>
                @php
                    $latestDisp = $letter->dispositions->last();
                @endphp
                @if($latestDisp)
                    <p style="margin:0; font-size:10.5pt; font-weight:bold;">"{{ $latestDisp->instruction }}"</p>
                    @if($latestDisp->due_date)
                        <small style="color:red; display:block; margin-top:6px;">Batas Waktu: {{ $latestDisp->due_date->isoFormat('D MMMM Y') }}</small>
                    @endif
                @else
                    <p style="margin:0; color:#888; font-style:italic;">(Tuliskan catatan disposisi tangan jika dicetak kosong)</p>
                @endif
            </td>
        </tr>
    </table>

    <!-- History Log Table -->
    @if($letter->dispositions->count() > 0)
        <table class="border-table">
            <tr class="box-title">
                <th width="25%">Tanggal & Pengirim</th>
                <th width="35%">Penerima Disposisi</th>
                <th width="40%">Status & Catatan Hasil</th>
            </tr>
            @foreach($letter->dispositions as $disp)
                <tr>
                    <td>{{ $disp->created_at->format('d/m/Y H:i') }}<br><small>{{ $disp->sender->name }}</small></td>
                    <td><strong>{{ $disp->recipient->name }}</strong><br><small>{{ $disp->recipientDepartment->name ?? '-' }}</small></td>
                    <td>
                        Status: <strong>{{ $disp->status }}</strong><br>
                        <small>{{ $disp->follow_up_notes ?? 'Belum ada laporan.' }}</small>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    <table class="signatures">
        <tr>
            <td></td>
            <td>
                Sukoharjo, {{ date('d F Y') }}<br>
                <strong>LURAH DUKUH</strong><br><br><br><br><br>
                <u>{{ $lurahUser->name ?? 'H. BAMBANG SUTARJO, S.STP, M.Si' }}</u><br>
                NIP. {{ $lurahUser->nip ?? '19780512 199803 1 002' }}
            </td>
        </tr>
    </table>

</body>
</html>
