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
                height: 17;
                width: 10%;
            }
            .column {
                float: left;
                width: 50%;
                padding: 10px;
            }

            /* Clear floats after the columns */
            .row:after {
                content: "";
                display: table;
                clear: both;
            }
        </style>
        <div class="container">
            <div class="col-lg-6 col-md-6">
                <center><img src={{$base64}} alt="garuda" width="80px" height="80px"></center>
                <center>
                    <p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 12">PENILAIAN PRESTASI KERJA  <br/> PEGAWAI NEGERI SIPIL</p>
                </center>
                <div class="row">
                    <div class="column"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">{{$user->satuan_kerja->satuan_kerja}}</p></div>
                    <div class="column"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">JANGKA WAKTU PENILAIAN <br> BULAN: {{ \Carbon\Carbon::parse($skpheader->periode_mulai)->format('d M Y') . ' sd. ' . \Carbon\Carbon::parse($skpheader->periode_selesai)->format('d M Y') }}</p></div>
                </div>
                <div>
                    <table class="table-bordered">
                        <tbody>
                            <tr>
                                <td style="text-align: center" rowspan="6">1.</td>
                                <td style="font-weight: bold" colspan="9">YANG DINILAI</td>
                            </tr>
                            <tr>
                                <td  colspan="4">a. Nama</td>
                                <td colspan="5">{{$user->name}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">b. NIP</td>
                                <td colspan="5">{{$user->nip}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">c. Pangkat, Golongan ruang, TMT</td>
                                <td colspan="5">{{$user->pangkat->pangkat_golongan}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">d. Jabatan/Pekerjaan</td>
                                <td colspan="5">{{$user->jabatan}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">e.Unit Organisasi</td>
                                <td colspan="5">{{$user->satuan_kerja->satuan_kerja}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table-bordered">
                        <tbody>
                            <tr>
                                <td style="text-align: center" rowspan="6">2.</td>
                                <td style="font-weight: bold" colspan="9">PEJABAT PENILAI</td>
                            </tr>
                            <tr>
                                <td colspan="4">a. Nama</td>
                                <td colspan="5">{{$user_atasan->name}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">b. NIP</td>
                                <td colspan="5">{{$user_atasan->nip}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">c. Pangkat, Golongan ruang, TMT</td>
                                <td colspan="5">{{$user_atasan->pangkat->pangkat_golongan}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">d. Jabatan/Pekerjaan</td>
                                <td colspan="5">{{$user_atasan->jabatan}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">e.Unit Organisasi</td>
                                <td colspan="5">{{$user_atasan->satuan_kerja->satuan_kerja}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table-bordered">
                        <tbody>
                            <tr>
                                <td style="text-align: center" rowspan="6">3.</td>
                                <td style="font-weight: bold" colspan="9">ATASAN PEJABAT PENILAI</td>
                            </tr>
                            <tr>
                                <td colspan="4">a. Nama</td>
                                <td colspan="5">{{$user_atasan_atasan->name}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">b. NIP</td>
                                <td colspan="5">{{$user_atasan_atasan->nip}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">c. Pangkat, Golongan ruang, TMT</td>
                                <td colspan="5">{{$user_atasan_atasan->pangkat->pangkat_golongan}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">d. Jabatan/Pekerjaan</td>
                                <td colspan="5">{{$user_atasan_atasan->jabatan}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">e.Unit Organisasi</td>
                                <td colspan="5">{{$user_atasan_atasan->satuan_kerja->satuan_kerja}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table-bordered">
                        <tbody>
                            <tr>
                                <td style="text-align: center" rowspan="11">4.</td>
                                <td style="font-weight: bold" colspan="8">UNSUR YANG DINILAI</td>
                                <td style="font-weight: bold; text-align: center" colspan="2">Jumlah</td>
                            </tr>
                            <tr>
                                <td colspan="5" style="text-align: center; font-weight: bold">a. Sasaran Kerja Pegawai (SKP)</td>
                                <td style="text-align: center">{{ $total_nilai }}</td>
                                <td style="text-align: center">x</td>
                                <td style="text-align: center">60%</td>
                                <td style="text-align: center" colspan="2">{{ $nilai_skp }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" rowspan="9" style="text-align: center; font-weight: bold">b. Perilaku Kerja</td>
                                <td colspan="3">1. Orientasi Pelayanan</td>
                                <td>{{ $penilaian->orientasi_pelayanan }}</td>
                                <td colspan="2">({{ $penilaian->capaian_orientasi_pelayanan }})</td>
                                <td colspan="2"> </td>
                            </tr>
                            <tr>
                                <td colspan="3">2. Integritas</td>
                                <td>{{ $penilaian->integritas }}</td>
                                <td colspan="2">({{ $penilaian->capaian_integritas }})</td>
                                <td colspan="2"> </td>
                            </tr>
                            <tr>
                                <td colspan="3">3. Komitmen</td>
                                <td>{{ $penilaian->komitmen }}</td>
                                <td colspan="2">({{ $penilaian->capaian_komitmen }})</td>
                                <td colspan="2"> </td>
                            </tr>
                            <tr>
                                <td colspan="3">4. Disiplin</td>
                                <td>{{ $penilaian->disiplin }}</td>
                                <td colspan="2">({{ $penilaian->capaian_disiplin }})</td>
                                <td colspan="2"> </td>
                            </tr>
                            <tr>
                                <td colspan="3">5. Kerjasama</td>
                                <td>{{ $penilaian->kerjasama }}</td>
                                <td colspan="2">({{ $penilaian->capaian_kerjasama }})</td>
                                <td colspan="2"> </td>
                            </tr>
                            <tr>
                                <td colspan="3">6. Kepemimpinan</td>
                                <td>{{ $penilaian->kepemimpinan }}</td>
                                <td colspan="2">({{ $penilaian->capaian_kepemimpinan }})</td>
                                <td colspan="2"> </td>
                            </tr>
                            <tr>
                                <td colspan="3">7. Jumlah</td>
                                <td>{{ $penilaian->jumlah }}</td>
                                <td colspan="2"></td>
                                <td colspan="2"> </td>
                            </tr>
                            <tr>
                                <td colspan="3">8. Nilai Rata-rata</td>
                                <td>{{ $penilaian->rata_rata }}</td>
                                <td colspan="2">({{ $penilaian->capaian_rerata }})</td>
                                <td colspan="2"> </td>
                                
                            </tr>
                            <tr>
                                <td colspan="3">9. Nilai Perilaku Kerja</td>
                                <td>{{ $penilaian->rata_rata }}</td>
                                <td>x</td>
                                <td>40%</td>
                                <td colspan="2">{{ $nilai_perilaku }}</td>
                            </tr>
                            <tr>
                                <td colspan="9" rowspan="2">NILAI PRESTASI KERJA</td>
                                <td colspan="2">{{ $jumlah_nilai }}</td>
                            </tr>
                            <tr><td colspan="2">{{ $capaian_final }}</td></tr>
                        </tbody>
                    </table>
                </div>
                

            </div>
                
            <div class="col-lg-6 col-md-6">
                <div style="border: 1px solid black">
                    <div class="col-12"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">5. KEBERATAN DARI PEGAWAI NEGERI <br> SIPIL YANG DINILAI (APABILA ADA)</p></div>
                    <br><br>
                    <div class="col-12" style="text-align: center"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">Tanggal ......</p></div>
                </div>
                <div style="border: 1px solid black">
                    <div class="col-12"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">6. TANGGAPAN PEJABAT PENILAI <br> ATAS KEBERATAN</p></div>
                    <br><br>
                    <div class="col-12" style="text-align: center"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">Tanggal ......</p></div>
                </div>
                <div style="border: 1px solid black">
                    <div class="col-12"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">7. KEPUTUSAN ATASAN PEJABAT <br> PENILAI ATAS KEBERATAN</p></div>
                    <br><br>
                    <div class="col-12" style="text-align: center"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">Tanggal ......</p></div>
                </div>
                <div style="border: 1px solid black">
                    <div class="col-12"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">8. REKOMENDASI</p></div>
                    <br><br>
                    <div class="col-12" style="text-align: center"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">Tanggal ......</p></div>
                </div>
                <div style="border: 1px solid black">
                    <div class="col-12">
                        <div class="row">
                            <div class="column"></div>
                            <div class="column"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">9. DIBUAT TANGGAL, <br> PEJABAT PENILAI <br><br><br><u>{{$user_atasan->name}}</u><br>{{$user_atasan->nip}}</p></div>
                        </div>
                        <div class="row">
                            <div class="column"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">10. DITERIMA TANGGAL, <br>PEGAWAI NEGERI SIPIL YANG DINILAI <br><br><br><u>{{$user->name}}</u> <br> {{$user->nip}}</p></div>
                            <div class="column"></div>
                        </div>
                        <div class="row">
                            <div class="column"><p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 8">11. DITERIMA TANGGAL,    <br><br><br><u>{{$user_atasan->name}}</u><br>{{$user_atasan->nip}}</p></div>
                            <div class="column"></div>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </body>
</html>
