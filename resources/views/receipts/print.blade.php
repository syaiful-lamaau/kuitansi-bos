@extends('layouts.print')

@section('title', 'Cetak Kuitansi - ' . $receipt->nomor_bukti)

@section('content')
    <style>
        .kuitansi-table td {
            padding: 1px 0;
            vertical-align: top;
        }

        .kuitansi-table .label {
            width: 140px;
            font-weight: bold;
        }

        .kuitansi-table .colon {
            width: 10px;
        }
    </style>

    <div
        style="font-family: 'Times New Roman', Times, serif; font-size: 8pt; color: #000; border: 1px solid #1f2937; padding: 20px;">

        {{-- Header --}}
        <div style="text-align: center; margin-bottom: 12px;">
            <strong style="font-size: 8pt;">KUITANSI / BUKTI PEMBAYARAN</strong>
        </div>

        {{-- Info Kanan Atas --}}
        <table style="margin-left: auto; margin-bottom: 10px;">
            <tr>
                <td style="padding: 1px 0;"><strong>Tahun Anggaran</strong></td>
                <td style="padding: 1px 8px;">:</td>
                <td style="padding: 1px 0;">{{ $setting->tahun_anggaran }}</td>
            </tr>
            <tr>
                <td style="padding: 1px 0;"><strong>Nomor Bukti</strong></td>
                <td style="padding: 1px 8px;">:</td>
                <td style="padding: 1px 0;">{{ $receipt->nomor_bukti }}</td>
            </tr>
        </table>

        {{-- Body --}}
        <table class="kuitansi-table" style="width: 100%; margin-bottom: 8px;">
            <tr>
                <td class="label">Sudah terima dari</td>
                <td class="colon">:</td>
                <td>{{ $receipt->sudah_terima_dari ?: 'Bendahara Madrasah' }}</td>
            </tr>
            <tr>
                <td class="label">Madrasah</td>
                <td class="colon">:</td>
                <td>{{ $setting->nama_madrasah }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td>{{ $setting->alamat }}</td>
            </tr>
            <tr>
                <td class="label">Jumlah Uang</td>
                <td class="colon">:</td>
                <td>Rp {{ number_format($receipt->jumlah, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Terbilang</td>
                <td class="colon">:</td>
                <td><em>{{ $receipt->terbilang }}</em></td>
            </tr>
            <tr>
                <td class="label">Untuk pembayaran</td>
                <td class="colon">:</td>
                <td>{{ $receipt->untuk_pembayaran }}</td>
            </tr>
            <tr>
                <td class="label">Sumber dana</td>
                <td class="colon">:</td>
                <td>{{ $receipt->sumber_dana }}</td>
            </tr>
        </table>

        {{-- Tanda Tangan --}}
        <table style="width: 100%; margin-top: 16px; font-size: 8pt;">
            <tr>
                <td style="width: 33%; vertical-align: top;">
                    <div>Mengetahui</div>
                    <div>Kepala Madrasah</div>
                </td>
                <td style="width: 34%; vertical-align: top;">
                    <div>Lunas dibayar</div>
                    <div>Bendahara Madrasah</div>
                </td>
                <td style="width: 33%; vertical-align: top;">
                    <div>{{ $receipt->tanggal->format('d-m-Y') }}</div>
                    <div>Penerima uang</div>
                </td>
            </tr>
            <tr>
                <td style="padding-top: 60px; vertical-align: bottom;">
                    <div>{{ $setting->nama_kepala }}</div>
                    @if($setting->nip_kepala)
                    <div>NIP.{{ $setting->nip_kepala }}</div>@endif
                </td>
                <td style="padding-top: 60px; vertical-align: bottom;">
                    <div>{{ $setting->nama_bendahara }}</div>
                    @if($setting->nip_bendahara)
                    <div>NIP.{{ $setting->nip_bendahara }}</div>@endif
                </td>
                <td style="padding-top: 60px; vertical-align: baseline;">
                    <div>{{ $receipt->nama_penerima ?: '.........................' }}</div>
                </td>
            </tr>
        </table>

    </div>
@endsection