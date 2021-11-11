<!DOCTYPE html>
<html>
    <head>
        <title>Membuat Laporan PDF Dengan DOMPDF Laravel</title>
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>

    <body>
        <style type="text/css">
            table {
                table-layout: fixed;
                width: 100%;
            }
            table tr td,
            table tr th{
                font-size: 8;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                height: 10;
                width: 25%;
            }
            .wide {
                width: 10%;
            }
        </style>
        <center>
            <p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 10">BUKU CATATAN PENILAIAN PERILAKU PNS</p>
        </center>
        <p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">Nama : {{ $user->name }} <br> NIP : {{ $user->nip }}</p>
        <div class="table-responsive">
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center" class="wide">No</th>
                        <th style="text-align: center">Tanggal</th>
                        <th style="text-align: center">Uraian</th>
                        <th style="text-align: center">Nama/NIP dan Paraf <br> Pejabat Penilai</th>
                    </tr>
                    <tr bgcolor="RGB(192,192,192)" style="text-align: center" height="5">
                        <th class="wide">1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                    </tr>
                </thead>
                <tbody>
                   <tr>
                    <td style="text-align: center" class="wide">1</td>
                    <td style="text-align: center">{{ \Carbon\Carbon::parse($skpheader->periode_mulai)->format('d M Y') . ' sd. ' . \Carbon\Carbon::parse($skpheader->periode_selesai)->format('d M Y') }}</td>
                    <td>
                        Penilaian SKP sampai dengan akhir {{\Carbon\Carbon::parse($skpheader->periode_selesai)->format('M Y')}} = {{$total_nilai}} sedangkan penilaian perilaku kerjanya adalah sebagai berikut : <br>
                        Orientasi Pelayanan &emsp; = {{$penilaian->orientasi_pelayanan}} <br>
                        Integritas &emsp; = {{$penilaian->integritas}} <br>
                        Komitmen &emsp; = {{$penilaian->komitmen}} <br>
                        Disiplin &emsp; = {{$penilaian->disiplin}} <br>
                        Kerjasama &emsp; = {{$penilaian->kerjasama}} <br>
                        Kepemimpinan &emsp; = {{$penilaian->kepemimpinan}} <br> 
                        <hr>
                        Jumlah &emsp; = {{$penilaian->jumlah}} <br>
                        Nilai Rata-rata &emsp; = {{$penilaian->rata_rata}} <br>
                    </td>
                    <td style="text-align: center"><br>{{ $user_atasan->jabatan }} <br><br><br><br><br> {{$user_atasan->name}}<br>{{substr($user_atasan->nip, 0, 8) . '.' . substr($user_atasan->nip, 8, 6) . '.' . substr($user_atasan->nip, 14, 1) . '.' . substr($user_atasan->nip, 15, 3)}}</td>
                   </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
