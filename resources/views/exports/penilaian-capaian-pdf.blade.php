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
                font-size: 6;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                height: 10;
                width: 7%;
            }
            .wide {
                width: 30%;
            }
        </style>
        <center>
            <p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 10">PENILAIAN CAPAIAN SASARAN KERJA<br/>PEGAWAI NEGERI SIPIL</p>
        </center>

        <div class="container">
            <p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 6">Jangka Waktu Penilaian <br> {{ \Carbon\Carbon::parse($skpheader->periode_mulai)->format('d M Y') . ' sd. ' . \Carbon\Carbon::parse($skpheader->periode_selesai)->format('d M Y') }}</p>
        </div>

        <div class="table-responsive">
            <table class="table-bordered">
                <thead>
                    <col span="2" class="wide">
                    <tr>
                        <col>
                        <colgroup span="4"></colgroup>
                        <th style="text-align: center" rowspan="2">NO</th>
                        <th style="text-align: center" rowspan="2">I. KEGIATAN TUGAS JABATAN</th>
                        <th style="text-align: center" rowspan="2">AK</th>
                        <th colspan="4" style="text-align: center" scope="colgroup">TARGET</th>
                        <th style="text-align: center" rowspan="2">AK</th>
                        <th colspan="4" style="text-align: center" scope="colgroup">REALISASI</th>
                        <th style="text-align: center" rowspan="2">PENG<br>HITUNGAN</th>
                        <th style="text-align: center" rowspan="2">NILAI CAPAIAN SKP</th>
                    </tr>
                    <tr style="text-align: center">
                        <th scope="col">KUANT/ OUTPUT</th>
                        <th scope="col">KUAL/ MUTU</th>
                        <th scope="col">WAKTU</th>
                        <th scope="col">BIAYA</th>
                        <th scope="col">KUANT/ OUTPUT</th>
                        <th scope="col">KUAL/ MUTU</th>
                        <th scope="col">WAKTU</th>
                        <th scope="col">BIAYA</th>
                    </tr>
                    <tr bgcolor="RGB(192,192,192)" style="text-align: center" height="5">
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                    </tr>
                </thead>
                <tbody>
                    <col span="2" class="wide">
                   @forelse ($skplines as $index => $skpline)
                   <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $skpline->kegiatan }}</td>
                    <td>{{ $skpline->angka_kredit_target }}</td>
                    <td>{{ $skpline->kuantitas_target }}</td>
                    <td>{{ $skpline->kualitas_target }}</td>
                    <td>{{ $skpline->waktu_target }}</td>
                    <td>{{ $skpline->biaya_target }}</td>
                    <td>{{ $skpline->angka_kredit_realisasi}}</td>
                    <td>{{ $skpline->kuantitas_realisasi ? $skpline->kuantitas_realisasi : '' }}</td>
                    <td>{{ $skpline->kualitas_realisasi ? $skpline->kualitas_realisasi : ''}}</td>
                    <td>{{ $skpline->waktu_realisasi ? $skpline->waktu_realisasi : ''}}</td>
                    <td>{{ $skpline->biaya_realisasi !== null ? $skpline->biaya_realisasi : ''}}</td>
                    <td>{{ $skpline->perhitungan ? $skpline->perhitungan : ''}}</td>
                    <td>{{ $skpline->nilai_capaian ? $skpline->nilai_capaian : ''}}</td>
                   </tr>
                   @empty
                       
                   @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center"></th>
                        <th style="text-align: left" colspan="13">II. TUGAS TAMBAHAN DAN KREATIVITAS:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center">A</td>
                        <td style="text-align: left; font-weight: bold;" colspan="12">Tugas Tambahan :</td>
                        <td rowspan="{{ count($tugass) + 2 }}" style="text-align: center">{{ count($tugass) }}</td>
                    </tr>
                   @forelse ($tugass as $index => $tugas)
                   <tr>
                    <td>{{ $index + 1 }}</td>
                    <td colspan="12">{{ $tugas->nama_tugas }}</td>
                   </tr>
                   @empty
                       
                   @endforelse
                   <tr>
                       <td>{{ count($tugass) + 1 }}</td>
                       <td colspan="12"></td>
                   </tr>
                   <tr>
                        <td style="text-align: center">B</td>
                        <td style="text-align: left; font-weight: bold;" colspan="12">Kreativitas</td>
                        <td rowspan="{{ count($kreativitas) + 2 }}" style="text-align: center">{{ count($kreativitas) > 0 ? count($kreativitas) : '' }}</td>
                   </tr>
                   @forelse ($kreativitas as $index => $kreatif)
                   <tr>
                    <td>{{ $index + 1 }}</td>
                    <td colspan="12">{{ $kreatif->kegiatan_kreativitas }}</td>
                   </tr>
                   @empty
                       
                   @endforelse
                   <tr>
                    <td>{{ count($kreativitas) + 1 }}</td>
                    <td colspan="12"></td>
                   </tr>
                   <tr>
                       <td colspan="13" rowspan="2" style="font-weight: bold; text-align: center;">
                           Nilai Capaian SKP
                       </td>
                       <td style="text-align: center">{{ $total_nilai }}</td>
                   </tr>
                   <tr><td style="text-align: center">{{ $capaian }}</td></tr>
                </tbody>
            </table>
        </div>

        <br><br><br>
        <div class="table-responsive">
            <table border="0">
                <thead>
                    <tr>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th style="text-align: center" colspan="6">{{ $lokasi . ', ' . \Carbon\Carbon::now()->format('d M Y') }}</th>
                    </tr>
                    <tr>
                        <th style="width: 10%">*) Keterangan</th>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th style="text-align: center" colspan="6">Pejabat Penilai,</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1.</td>
                        <td colspan="4">Nilai Capaian Sasaran Kerja dari {{$user->satuan_kerja->satuan_kerja}}</td>
                        <td style="text-align: left">:</td>
                        <td style="text-align: left">{{ $total_nilai }}</td>
                    </tr>
                    <tr><td></td></tr>
                    <tr><td></td></tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: center" colspan="6">{{ $user_atasan->name }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: center" colspan="6">{{ substr($user_atasan->nip, 0, 8) . '.' . substr($user_atasan->nip, 8, 6) . '.' . substr($user_atasan->nip, 14, 1) . '.' . substr($user_atasan->nip, 15, 3) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
