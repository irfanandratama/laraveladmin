{{-- \resources\views\roles\index.blade.php --}}
@extends('layouts.app')

@section('title', '| SKP Tahunan')

@section('content')

<div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-1 col-lg-10">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Detail Data SKP
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-parent="#accordionExample" aria-expanded="false">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                      <div class="form-group">
                                          {{ Form::label('', 'Periode') }}
                                          {{ Form::text('periode', \Carbon\Carbon::parse($skpheader->periode_mulai)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($skpheader->periode_selesai)->format('d M Y'), array('class' => 'form-control', 'disabled')) }}
                                      </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                      <div class="form-group">
                                          {{ Form::label('', 'Nama') }}
                                          {{ Form::text('nama', $user->name, array('class' => 'form-control', 'disabled')) }}
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
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
