<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>SK Penyetaraan - {{ $conversion->user->name }}</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 18mm 20mm 18mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            color: #000;
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }

        .page {
            width: 100%;
            min-height: 100%;
        }

        .page-break {
            page-break-after: always;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
        }

        .italic {
            font-style: italic;
        }

        .justify {
            text-align: justify;
        }

        .middle {
            text-align: center;
            vertical-align: middle !important;
        }

        /* ========= LETTERHEAD ========= */
        .letterhead-table {
            width: 100%;
            border-collapse: collapse;
        }

        .letterhead-table td {
            vertical-align: middle;
            padding: 0;
        }

        .letterhead-logo {
            width: 80px;
            text-align: left;
        }

        .letterhead-logo img {
            width: 160px;
            height: auto;
            margin-left: 20px;
        }

        .letterhead-address {
            text-align: center;
            font-size: 9pt;
            line-height: 1.2;
            margin-top: 2px;
        }

        .hr-line {
            border: none;
            border-top: 1px solid #000;
            margin: 8px 0 28px 0;
        }

        /* ========= SK TITLE ========= */
        .sk-title {
            text-align: center;
            font-size: 11pt;
            line-height: 1.2;
            margin-bottom: 18px;
        }

        /* ========= SK TABLE ========= */
        .sk-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
            line-height: 1.2;
        }

        .sk-table td {
            vertical-align: top;
            padding: 1px 4px;
        }

        .sk-label {
            width: 90px;
            font-weight: bold;
        }

        .sk-colon {
            width: 12px;
        }

        .sk-number {
            width: 18px;
        }

        .decision-title {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
        }

        /* ========= SIGNATURE ========= */
        .sk-signature {
            width: 260px;
            margin-left: auto;
            margin-top: 20px;
            font-size: 11pt;
            line-height: 1.2;
        }

        .signature-space {
            height: 60px;
        }

        .cc-list {
            margin-top: 20px;
            font-size: 11pt;
            line-height: 1.2;
        }

        /* ========= LAMPIRAN HEADER ========= */
        .lampiran-header {
            font-size: 11pt;
            line-height: 1.2;
            margin-bottom: 18px;
        }

        .lampiran-header table {
            width: 100%;
            border-collapse: collapse;
        }

        .lampiran-header td {
            padding: 0;
            vertical-align: top;
        }

        .lampiran-label {
            width: 72px;
        }

        .lampiran-colon {
            width: 10px;
        }

        .conversion-title {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 18px;
        }

        .student-info {
            line-height: 1.2;
            font-size: 11pt;
            margin-bottom: 16px;
        }

        .student-info table {
            border-collapse: collapse;
        }

        .student-info td {
            padding: 0 4px 2px 0;
            vertical-align: top;
        }

        .student-label {
            width: 140px;
        }

        .student-colon {
            width: 10px;
        }

        /* ========= MAIN TABLE ========= */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
            font-size: 10pt;
        }

        .no-col {
            width: 4%;
            text-align: center;
        }

        .code-col {
            width: 10%;
            text-align: center;
        }

        .name-col {
            width: 40%;
        }

        .sks-col {
            width: 6%;
            text-align: center;
        }

        .grade-col {
            width: 6%;
            text-align: center;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: top;
        }

        .main-table th {
            text-align: center;
            font-weight: bold;
            line-height: 1.1;
        }

        .group-header {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            padding: 2px 0;
        }

        .course-name {
            line-height: 1.1;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .total-row td {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        .no-conversion-row {
            background-color: #ff0000;
        }

        /* ========= SUMMARY TABLE ========= */
        .summary-table {
            width: 340px;
            margin: 16px auto 0 auto;
            border-collapse: collapse;
            font-size: 11pt;
            font-weight: bold;
        }

        .summary-table td {
            border: 1px solid #000;
            padding: 2px 8px;
        }

        .summary-label {
            width: 260px;
        }

        .summary-value {
            width: 80px;
            text-align: center;
        }

        .final-signature {
            width: 300px;
            margin-left: auto;
            margin-top: 50px;
            text-align: center;
            font-size: 11pt;
            line-height: 1.2;
        }

        /* Repeating headers for DomPDF */
        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    @php
    $decreeNumber = $conversion->decree_number ?? '019/SK/Rektor-ITEBA/III/2024';
    $decreeDate = $conversion->decree_date ? \Carbon\Carbon::parse($conversion->decree_date)->translatedFormat('j F Y') : '5 Maret 2024';
    $academicYear = $conversion->academic_year ?? '2024/2025';
    $rectorName = $conversion->rector_name ?? 'Prof. Dr. Ing. Ir. H. Hairul Abral';
    $rectorNidn = $conversion->rector_nidn ?? '0017086612';
    $graduationTotalSks = $conversion->graduation_total_sks ?? 144;

    $logoPath = public_path('images/logo-iteba.png');
    $logoBase64 = '';
    if (file_exists($logoPath)) {
    $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }

    // Grouping: 1 source subject may map to multiple target subjects
    $groups = collect($conversion->results)
    ->groupBy(fn($item) => $item->source_subject_id ?? optional($item->source_subject)->id ?? spl_object_id($item))
    ->values();

    $totalSourceSks = $conversion->total_source_sks ?? $groups->sum(fn($items) => (int) ($items->first()->source_subject->sks ?? 0));
    $totalTargetSks = $conversion->total_target_sks ?? collect($conversion->results)->sum(fn($item) => (int) ($item->target_subject->sks ?? 0));
    $recognizedSks = $conversion->recognized_sks ?? $totalTargetSks;
    $remainingSks = $conversion->remaining_sks ?? ($graduationTotalSks - $recognizedSks);

    $noAsal = 1;
    $noKonversi = 1;

    $type = request('type');
    $showSk = ($type === 'sk') && ($conversion->status === 'approved');
    @endphp

    @if($showSk)
    {{-- ==================== PAGE 1: SURAT KEPUTUSAN ==================== --}}
    <div class="page page-break">
        {{-- ... ( Decree content remains unchanged ) ... --}}
        <table class="letterhead-table">
            <tr>
                <td class="letterhead-logo">
                    @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo ITEBA">
                    @endif
                </td>
            </tr>
        </table>
        <div class="letterhead-address">
            Jl Gajah Mada, Kompleks Vitka City, Tiban Ayu - Sekupang, Batam 29425, Kepulauan Riau - Indonesia<br>
            Telp: (0778) 3540555 / 3540666 WA: 0822 1000 3267 Web : Iteba.ac.id Email: info@iteba.ac.id
        </div>
        <hr class="hr-line">

        <div class="sk-title">
            SURAT KEPUTUSAN REKTOR INSTITUT TEKNOLOGI BATAM<br>
            <b>
                NOMOR : {{ $decreeNumber }}<br>
                TENTANG<br>
                PENETAPAN MAHASISWA BARU JALUR ALIH JENJANG PROGRAM STUDI SISTEM<br>
                INFORMASI DI INSTITUT TEKNOLOGI BATAM<br>
                <br>
                REKTOR<br>
                INSTITUT TEKNOLOGI BATAM
            </b>
        </div>

        <table class="sk-table">
            <tr>
                <td class="sk-label">Menimbang</td>
                <td class="sk-colon">:</td>
                <td class="sk-number">1.</td>
                <td class="justify">
                    Bahwa mahasiswa baru jalur alih jenjang adalah migrasi mahasiswa lulusan D2/D3
                    dari Perguruan Tinggi lain melalui proses administrasi dan konversi nilai mata
                    kuliah yang sudah ditempuh di Perguruan Tinggi asal, ke Institut Teknologi Batam
                    yang mata kuliahnya diakui atau disetarakan di Institut Teknologi Batam.
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2.</td>
                <td class="justify">
                    Bahwa mahasiswa yang bersangkutan telah lolos seleksi Penerimaan Mahasiswa
                    Baru Tahun Akademik {{ $academicYear }}.
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>3.</td>
                <td class="justify">
                    Bahwa untuk itu perlu dikeluarkan Surat Keputusan Rektor Institut Teknologi
                    Batam sebagai penetapan dan pengesahannya.
                </td>
            </tr>
            <tr>
                <td class="sk-label" style="padding-top:10px;">Mengingat</td>
                <td style="padding-top:10px;">:</td>
                <td style="padding-top:10px;">1.</td>
                <td style="padding-top:10px;" class="justify">
                    Undang - Undang No. 20 Tahun 2003 tentang Pendidikan Nasional.
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>2.</td>
                <td class="justify">
                    Peraturan Pemerintah No. 66 Tahun 2010 tentang Pengelolaan dan Penyelenggaraan Pendidikan.
                </td>
            </tr>
        </table>

        <div class="decision-title">MEMUTUSKAN</div>

        <table class="sk-table">
            <tr>
                <td class="sk-label">Menetapkan</td>
                <td class="sk-colon"></td>
                <td></td>
            </tr>
            <tr>
                <td class="bold">Pertama</td>
                <td>:</td>
                <td class="justify">
                    Keputusan Rektor Institut Teknologi Batam, tentang Penetapan Mahasiswa Baru Jalur
                    Alih Jenjang Program Studi Sistem Informasi di Institut Teknologi Batam atas nama
                    <strong>{{ $conversion->user->name }}.</strong>
                </td>
            </tr>
            <tr>
                <td class="bold">Kedua</td>
                <td>:</td>
                <td class="justify">
                    Menetapkan penyetaraan {{ $recognizedSks }} SKS dari {{ $graduationTotalSks }} SKS
                    yang terlampir dalam Surat Keputusan ini.
                </td>
            </tr>
            <tr>
                <td class="bold">Ketiga</td>
                <td>:</td>
                <td class="justify">
                    Surat Keputusan ini berlaku sejak ditetapkan apabila dikemudian hari ternyata terdapat
                    kekeliruan dalam keputusan ini, akan diadakan perbaikan sebagaimana mestinya.
                </td>
            </tr>
        </table>

        <div class="sk-signature">
            Ditetapkan di &nbsp;&nbsp;: Batam<br>
            <span class="underline">Pada tanggal &nbsp;&nbsp;: {{ \Carbon\Carbon::parse($decreeDate)->locale('id')->translatedFormat('d F Y') }}</span><br><br>
            REKTOR
            <div class="signature-space"></div>
            <div class="bold">{{ $rectorName }}</div>
            <div>NIDN. {{ $rectorNidn }}</div>
        </div>

        <div class="cc-list">
            <strong><u>Tembusan disampaikan kepada Yth :</u></strong><br>
            &nbsp;&nbsp;&nbsp;1. &nbsp;Ketua Yayasan Vitka<br>
            &nbsp;&nbsp;&nbsp;2. &nbsp;Direktur <em>Business Development &amp; Marketing</em><br>
            &nbsp;&nbsp;&nbsp;3. &nbsp;Direktur Keuangan Yayasan Vitka<br>
            &nbsp;&nbsp;&nbsp;4. &nbsp;Dekan dan Kaprodi yang bersangkutan<br>
            &nbsp;&nbsp;&nbsp;5. &nbsp;Mahasiswa yang bersangkutan<br>
            &nbsp;&nbsp;&nbsp;6. &nbsp;Arsip
        </div>
    </div>
    @endif

    {{-- ==================== PAGE 2: LAMPIRAN + TABLE START ==================== --}}
    <div class="page">
        @if($showSk)
        <div class="lampiran-header">
            Lampiran Keputusan Rektor Institut Teknologi Batam<br>
            <table>
                <tr>
                    <td class="lampiran-label">Nomor</td>
                    <td class="lampiran-colon">:</td>
                    <td>{{ $decreeNumber }}</td>
                </tr>
                <tr>
                    <td class="lampiran-label">Tanggal</td>
                    <td class="lampiran-colon">:</td>
                    <td>{{ \Carbon\Carbon::parse($decreeDate)->locale('id')->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td class="lampiran-label">Tentang</td>
                    <td class="lampiran-colon">:</td>
                    <td>Penetapan Mahasiswa Baru Jalur Alih Jenjang Program Studi Sistem Informasi di Institut Teknologi Batam.</td>
                </tr>
            </table>
        </div>
        @endif

        <div class="conversion-title">
            Penyetaraan Mata Kuliah Program Studi Sistem Informasi
        </div>

        <div class="student-info">
            <table>
                <tr>
                    <td class="student-label">Nama Mahasiswa</td>
                    <td class="student-colon">:</td>
                    <td>{{ $conversion->user->name }}</td>
                </tr>
                <tr>
                    <td class="student-label">Program Studi</td>
                    <td class="student-colon">:</td>
                    <td>{{ $conversion->user->prodi ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="student-label">Asal Kampus</td>
                    <td class="student-colon">:</td>
                    <td>{{ $conversion->user->studentDetail->university->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="student-label">Tanggal Kelulusan</td>
                    <td class="student-colon">:</td>
                    <td>{{ $conversion->user->studentDetail->graduation_date ? $conversion->user->studentDetail->graduation_date->locale('id')->translatedFormat('d F Y') : '-' }}</td>
                </tr>
            </table>
        </div>

        @include('pdf.partials.conversion-table-header')
        <tbody>
            @foreach($groups as $items)
            @php $first = $items->first(); $rowspan = max($items->count(), 1); @endphp
            @foreach($items as $index => $result)
            @php $hasNoTarget = empty($result->target_subject); @endphp
            <tr class="{{ $hasNoTarget ? 'no-conversion-row' : '' }}">
                @if($index === 0)
                <td class="middle no-col" rowspan="{{ $rowspan }}">{{ $noAsal++ }}</td>
                <td class="middle code-col" rowspan="{{ $rowspan }}">{{ $first->source_subject->code ?? '-' }}</td>
                <td class="course-name name-col" rowspan="{{ $rowspan }}">{{ $first->source_subject->name ?? '-' }}</td>
                <td class="middle sks-col" rowspan="{{ $rowspan }}">{{ $first->source_subject->sks ?? '-' }}</td>
                <td class="middle grade-col" rowspan="{{ $rowspan }}">{{ $first->origin_grade ?? '-' }}</td>
                @endif

                <td class="middle no-col">{{ $hasNoTarget ? '' : $noKonversi++ }}</td>
                <td class="middle code-col">{{ $result->target_subject->code ?? '' }}</td>
                <td class="course-name name-col">{{ $result->target_subject->name ?? '' }}</td>
                <td class="middle sks-col">{{ $result->target_subject->sks ?? '' }}</td>
                <td class="middle grade-col">{{ $result->grade ?? '' }}</td>
            </tr>
            @endforeach
            @endforeach

            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ $totalSourceSks }}</td>
                <td></td>
                <td colspan="3">Total</td>
                <td>{{ $totalTargetSks }}</td>
                <td></td>
            </tr>
        </tbody>
        </table>

        {{-- SUMMARY TABLE --}}
        <table class="summary-table">
            <tr>
                <td class="summary-label">Total Sks yang diakui</td>
                <td class="summary-value">{{ $recognizedSks }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Sks yang harus ditempuh</td>
                <td class="summary-value">{{ $remainingSks }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total sks Lulus</td>
                <td class="summary-value">{{ $graduationTotalSks }}</td>
            </tr>
        </table>

        {{-- FINAL SIGNATURE --}}
        <div class="final-signature">
            REKTOR
            <div class="signature-space"></div>
            <div class="bold">{{ $rectorName }}</div>
            <div>NIDN. {{ $rectorNidn }}</div>
        </div>
    </div>

</body>

</html>