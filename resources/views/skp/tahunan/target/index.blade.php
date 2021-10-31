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
                          <h1><i class="fa fa-key"></i> Target SKP Tahunan
                            <a href="{{ URL::to('skp/tahunan/target/'.$id.'/create') }}" class="btn btn-default pull-right">Add Target SKP Tahunan</a>
                         </h1>
                      </div>
                      <div class="card-body">  
                        @if ($errors->any())
                        <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Kegiatan Tahunan</th>
                                        <th>Target Kuantitas</th>
                                        <th>Target Kualitas</th>
                                        <th>Target Waktu</th>
                                        <th>Biaya</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($skplines as $index => $skpline)
                                    <tr>

                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $tahun }}</td>
                                        <td>{{ $skpline->kegiatan }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                                        <td>{{ $skpline->kuantitas_target }}</td>
                                        <td>{{ $skpline->kualitas_target }}</td>
                                        <td>{{ $skpline->waktu_target }}</td>
                                        <td>{{ $skpline->biaya_target }}</td>
                                        <td>
                                            <a href="{{ route('target.edit', $skpline->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['target.destroy', $skpline->id], 'onsubmit' => 'return confirm("Yakin menghapus data ini?")' ]) !!}
                                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    @empty
                                        <tbody>
                                            <tr>
                                                <td colSpan="8" style="text-align: center">Data Not Found</td>
                                            </tr>
                                        </tbody>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>   
                     </div>
                  </div>
              </div>
          </div>
       </div>
  </div>

@endsection

@include('skp.tahunan.create')
