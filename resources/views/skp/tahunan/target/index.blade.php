{{-- \resources\views\roles\index.blade.php --}}
@extends('layouts.app')

@section('title', '| SKP Tahunan')

@section('content')

<div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-1 col-lg-10">
                   @include('skp.tahunan.detail')
                  <div class="card">
                      <div class="card-header">
                          <h1><i class="fa fa-key"></i> Target SKP Tahunan
                            <a href="{{ URL::to('skp/tahunan/target/'.$id.'/create') }}" class="btn btn-default pull-right">Add Target SKP Tahunan</a>
                         </h1>
                      </div>
                      <div class="card-body">  
                        @if ($message = Session::get('flash_message'))
                            <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>    
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
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
