<table>
    <thead>
        <tr style="text-align: center"><th colspan="11"><h1>FORMULIR SASARAN KINERJA</h1></th></tr>
        <tr style="text-align: center"><th colspan="11"><h1>PEGAWAI NEGERI SIPIL</h1></th></tr>
        <tr><th colspan="11"></th></tr>
        <tr style="text-align: center">
            <th>NO</th>
            <th colspan="3">I. PEJABAT PENILAI</th>
            <th>NO</th>
            <th colspan="6">II. PEGAWAI NEGERI SIPIL YANG DINILAI</th>
        </tr>
        <tr style="text-align: center">
            <th>1</th>
            <th>Nama</th>
            <th colspan="2">{{$user_atasan->name}}</th>
            <th>1</th>
            <th>Nama</th>
            <th colspan="5">{{$user->name}}</th>
        </tr>
        <tr style="text-align: center">
            <th>2</th>
            <th>NIP</th>
            <th colspan="2">{{$user_atasan->nip}}</th>
            <th>2</th>
            <th>NIP</th>
            <th colspan="5">{{$user->nip}}</th>
        </tr>
        <tr style="text-align: center">
            <th>3</th>
            <th>Pangkat/Gol. Ruang</th>
            <th colspan="2">{{$user_atasan->pangkat->pangkat_golongan}}</th>
            <th>3</th>
            <th>Pangkat/Gol. Ruang</th>
            <th colspan="5">{{$user->pangkat->pangkat_golongan}}</th>
        </tr>
        <tr style="text-align: center">
            <th>4</th>
            <th>Jabatan</th>
            <th colspan="2">{{$user_atasan->jabatan}}</th>
            <th>4</th>
            <th>Jabatan</th>
            <th colspan="5">{{$user->jabatan}}</th>
        </tr>
        <tr style="text-align: center">
            <th>5</th>
            <th>Unit Kerja</th>
            <th colspan="2">{{$user_atasan->satuan_kerja->satuan_kerja}}</th>
            <th>5</th>
            <th>Unit Kerja</th>
            <th colspan="5">{{$user->satuan_kerja->satuan_kerja}}</th>
        </tr>
        <col>
        <colgroup span="4"></colgroup>
        <tr style="text-align: center">
            <th rowspan="2">NO</th>
            <th colspan="3" rowspan="2">III. KEGIATAN TUGAS JABATAN</th>
            <th rowspan="2">AK</th>
            <th colspan="6" scope="colgroup">Target</th>
        </tr>
        <tr style="text-align: center">
            <th scope="col">KUANT/OUTPUT</th>
            <th scope="col">KUAL/MUTU</th>
            <th scope="col">WAKTU</th>
            <th scope="col">BIAYA</th>
        </tr>
        <tr></tr>
    </thead>
    <tbody style="text-align: center">
        @forelse ($skplines as $index => $skpline)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td colspan="3">{{ $skpline->kegiatan }}</td>
            {{-- <td></td> --}}
            <td>{{ $skpline->angka_kredit_target }}</td>
            <td>{{ $skpline->kuantitas_target }}</td>
            @foreach ($satuan as $satu)
                @if ($satu->id === $skpline->satuan_kegiatan_id)
                    <td>{{ $satu->satuan_kegiatan }}</td>                                                
                @endif
            @endforeach
            <td>{{ $skpline->kualitas_target }}</td>
            <td>{{ $skpline->waktu_target }}</td>
            <td>bulan</td>
            <td>{{ $skpline->biaya_target  }}</td>
        </tr>
        @empty
        <tr>
            <td colSpan="13" style="text-align: center">Silahkan tambah data target SKP terlebih dahulua</td>
        </tr>
        @endforelse
        <tr></tr>
        <tr>
            <td colspan="4">TUGAS TAMBAHAN dan KREATIVITAS/UNSUR PENUNJANG</td>
        </tr>
        @forelse ($tugass as $index => $tugas)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td colspan="3">{{ $tugas->nama_tugas }}</td>
                <td>0</td>
                <td>1</td>
                <td>kegiatan</td>
                <td>100</td>
                <td>masih dicari</td>
                <td>bulan</td>
                <td></td>
            </tr>
        @empty
            
        @endforelse
        @forelse ($kreativitas as $index => $kreatif)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td colspan="3">{{ $kreatif->kegiatan_kreativitas }}</td>
                <td>0</td>
                <td>{{ $kreatif->kuantitas }}</td>
                @foreach ($satuan as $satu)
                    @if ($satu->id === $kreatif->satuan_kegiatan_id)
                        <td>{{ $satu->satuan_kegiatan }}</td>                                                
                    @endif
                @endforeach
                <td>100</td>
                <td>masih dicari</td>
                <td>bulan</td>
                <td></td>
            </tr>
        @empty
            
        @endforelse
    </tbody>
</table>