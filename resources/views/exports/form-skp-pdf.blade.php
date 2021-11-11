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
                height: 18;
                width: 10%;
            }
        </style>
        <center>
            <p style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 12">FORMULIR SASARAN KINERJA <br/> PEGAWAI NEGERI SIPIL</p>
        </center>
        <div class="table-responsive">
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center">NO</th>
                        <th colspan="3" style="text-align: left">I. PEJABAT PENILAI</th>
                        <th style="text-align: center">NO</th>
                        <th colspan="4" style="text-align: left">II. PEGAWAI NEGERI SIPIL YANG DINILAI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th style="text-align: center">1</th>
                        <th style="text-align: left">Nama</th>
                        <th style="text-align: left" colspan="2">{{$user_atasan->name}}</th>
                        <th style="text-align: center">1</th>
                        <th style="text-align: left">Nama</th>
                        <th style="text-align: left" colspan="4">{{$user->name}}</th>
                    </tr>
                    <tr style="text-align: center">
                        <th style="text-align: center">2</th>
                        <th style="text-align: left">NIP</th>
                        <th style="text-align: left" colspan="2">{{$user_atasan->nip}}</th>
                        <th style="text-align: center">2</th>
                        <th style="text-align: left">NIP</th>
                        <th style="text-align: left"colspan="4">{{$user->nip}}</th>
                    </tr>
                    <tr style="text-align: center">
                        <th style="text-align: center">3</th>
                        <th style="text-align: left">Pangkat/Gol. Ruang</th>
                        <th style="text-align: left" colspan="2">{{$user_atasan->pangkat->pangkat_golongan}}</th>
                        <th style="text-align: center">3</th>
                        <th style="text-align: left">Pangkat/Gol. Ruang</th>
                        <th style="text-align: left" colspan="4">{{$user->pangkat->pangkat_golongan}}</th>
                    </tr>
                    <tr style="text-align: center">
                        <th style="text-align: center">4</th>
                        <th style="text-align: left">Jabatan</th>
                        <th style="text-align: left" colspan="2">{{$user_atasan->jabatan}}</th>
                        <th style="text-align: center">4</th>
                        <th style="text-align: left">Jabatan</th>
                        <th style="text-align: left" colspan="4">{{$user->jabatan}}</th>
                    </tr>
                    <tr style="text-align: center">
                        <th style="text-align: center">5</th>
                        <th style="text-align: left">Unit Kerja</th>
                        <th style="text-align: left" colspan="2">{{$user_atasan->satuan_kerja->satuan_kerja}}</th>
                        <th style="text-align: center">5</th>
                        <th style="text-align: left">Unit Kerja</th>
                        <th style="text-align: left" colspan="4">{{$user->satuan_kerja->satuan_kerja}}</th>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table-bordered">
                <thead>
                    <tr>
                        <col>
                        <colgroup span="5"></colgroup>
                        <th style="text-align: center" rowspan="2">NO</th>
                        <th colspan="3" rowspan="2" style="text-align: left">III. KEGIATAN TUGAS JABATAN</th>
                        <th style="text-align: center" rowspan="2">AK</th>
                        <th colspan="4" style="text-align: center" scope="colgroup">TARGET</th>
                    </tr>
                    <tr style="text-align: center">
                        <th scope="col">KUANT/ OUTPUT</th>
                        <th scope="col">KUAL/ MUTU</th>
                        <th scope="col">WAKTU</th>
                        <th scope="col">BIAYA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($skplines as $index => $skpline)
                    <tr>
                        <th>{{ $index + 1 }}</th>
                        <th colspan="3">{{ $skpline->kegiatan }}</th>
                        {{-- <th></th> --}}
                        <th>{{ $skpline->angka_kredit_target }}</th>
                        @foreach ($satuan as $satu)
                            @if ($satu->id === $skpline->satuan_kegiatan_id)
                                <th>{{ $skpline->kuantitas_target . ' ' . $satu->satuan_kegiatan }}</th>                                                
                            @endif
                        @endforeach
                        <th>{{ $skpline->kualitas_target }}</th>
                        <th>{{ $skpline->waktu_target . ' Bulan' }}</th>
                        <th>{{ $skpline->biaya_target  }}</th>
                    </tr>
                    @empty
                    <tr>
                        <th colSpan="10" style="text-align: center">Silahkan tambah data target SKP terlebih dahulua</th>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th colspan="10">TUGAS TAMBAHAN dan KREATIVITAS/UNSUR PENUNJANG</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tugass as $index => $tugas)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <th colspan="4">{{ $tugas->nama_tugas }}</th>
                            <th>0</th>
                            <th>1 Kegiatan</th>
                            <th>100</th>
                            <th>{{ $skplines[0]->waktu_target . ' Bulan' }}</th>
                            <th></th>
                        </tr>
                    @empty
                        
                    @endforelse
                    @forelse ($kreativitas as $index => $kreatif)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <th colspan="4">{{ $kreatif->kegiatan_kreativitas }}</th>
                            <th>0</th>
                            @foreach ($satuan as $satu)
                                @if ($satu->id === $kreatif->satuan_kegiatan_id)
                                    <th>{{ $kreatif->kuantitas . ' ' . $satu->satuan_kegiatan }}</th>                                                
                                @endif
                            @endforeach
                            <th>100</th>
                            <th>{{ $skplines[0]->waktu_target . ' Bulan' }}</th>
                            <th></th>
                        </tr>
                    @empty
                        
                    @endforelse
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
                        <th style="text-align: center" colspan="2">Pejabat Penilai,</th>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th style="text-align: center" colspan="4">Pegawai Negeri Sipil Yang Dinilai,</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td></td></tr>
                    <tr><td></td></tr>
                    <tr><td></td></tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="text-align: center" colspan="2">{{ $user_atasan->name }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: center" colspan="4">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="text-align: center" colspan="2">{{ substr($user_atasan->nip, 0, 8) . '.' . substr($user_atasan->nip, 8, 6) . '.' . substr($user_atasan->nip, 14, 1) . '.' . substr($user_atasan->nip, 15, 3) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: center" colspan="4">{{ substr($user->nip, 0, 8) . '.' . substr($user->nip, 8, 6) . '.' . substr($user->nip, 14, 1) . '.' . substr($user->nip, 15, 3) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
