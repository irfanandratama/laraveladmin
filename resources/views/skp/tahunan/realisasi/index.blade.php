{{-- \resources\views\roles\index.blade.php --}}
@extends('layouts.app')

@section('title', '| SKP Tahunan')

@section('content')

<div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-1 col-lg-10">
                <div class="form-group"><a href="{{ route('tahunan.index') }}" class="btn btn-default pull-left" style="margin-right: 3px;">Back</a></div><br><br>
                @include('skp.tahunan.detail')
                  <div class="card">
                      <div class="card-header">
                          <h1><i class="fa fa-key"></i> Realisasi SKP Tahunan
                            @if (count($skplines) > 0)
                                <a href="{{ URL::to('skp/tahunan/realisasi/'.$id.'/create') }}" class="btn btn-default pull-right">Add Realisasi SKP Tahunan</a>
                            @endif
                         </h1>
                      </div>
                      <div class="card-body">  
                        @if ($message = Session::get('flash_message'))
                        <div class="alert alert-success alert-block">
                          <button type="button" class="close" data-dismiss="alert">×</button>    
                            <strong>{{ $message }}</strong>
                        </div>
                      @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <col>
                                    <colgroup span="4"></colgroup>
                                    <tr style="text-align: center">
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Kegiatan Tahunan</th>
                                        <th colspan="4" scope="colgroup">Target</th>
                                        <th colspan="4" scope="colgroup">Realisasi</th>
                                        <th colspan="2" scope="colgroup">Penilaian</th>
                                        <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr style="text-align: center">
                                        <th scope="col">Kuantitas</th>
                                        <th scope="col">Kualitas</th>
                                        <th scope="col">Waktu</th>
                                        <th scope="col">Biaya</th>
                                        <th scope="col">Kuantitas</th>
                                        <th scope="col">Kualitas</th>
                                        <th scope="col">Waktu</th>
                                        <th scope="col">Biaya</th>
                                        <th scope="col">Perhitungan</th>
                                        <th scope="col">Nilai</th>
                                    </tr>
                                </thead>

                                <tbody style="text-align: center">
                                    @forelse ($skplines as $index => $skpline)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $skpline->kegiatan }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                                        <td>{{ $skpline->kuantitas_target }}</td>
                                        <td>{{ $skpline->kualitas_target }}</td>
                                        <td>{{ $skpline->waktu_target }}</td>
                                        <td>{{ $skpline->biaya_target }}</td>
                                        <td>{{ $skpline->kuantitas_realisasi ? $skpline->kuantitas_realisasi : 'Data Realisasi Belum Dimasukkan' }}</td>
                                        <td>{{ $skpline->kualitas_realisasi ? $skpline->kualitas_realisasi : 'Data Realisasi Belum Dimasukkan'}}</td>
                                        <td>{{ $skpline->waktu_realisasi ? $skpline->waktu_realisasi : 'Data Realisasi Belum Dimasukkan'}}</td>
                                        <td>{{ $skpline->biaya_realisasi !== null ? $skpline->biaya_realisasi : 'Data Realisasi Belum Dimasukkan'}}</td>
                                        <td>{{ $skpline->perhitungan ? $skpline->perhitungan : 'Data Realisasi Belum Dimasukkan'}}</td>
                                        <td>{{ $skpline->nilai_capaian ? $skpline->nilai_capaian : 'Data Realisasi Belum Dimasukkan'}}</td>
                                        <td>
                                            @if ($skpline->perhitungan && $skpline->nilai_capaian)
                                                <a href="{{ route('realisasi.edit', $skpline->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit Realisasi</a>
                                            @else
                                                <a href="{{ URL::to('skp/tahunan/realisasi/'.$skpline->id.'/create') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Add Realisasi</a>
                                            @endif

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['realisasi.destroy', $skpline->id], 'onsubmit' => 'return confirm("Yakin menghapus data ini? Ini hanya akan menghapus data realisasi.")' ]) !!}
                                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    @empty
                                        <tbody>
                                            <tr>
                                                <td colSpan="13" style="text-align: center">Silahkan tambah data target SKP terlebih dahulua</td>
                                            </tr>
                                        </tbody>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $skplines->links() }}
                        </div>   
                     </div>
                  </div>
              </div>
          </div>
       </div>
  </div>

@endsection
