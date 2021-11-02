{{-- \resources\views\roles\index.blade.php --}}
@extends('layouts.app')

@section('title', '| Kreativitas')

@section('content')

<div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-1 col-lg-10">
                @include('skp.tahunan.detail')
                  <div class="card">
                      <div class="card-header">
                          <h1><i class="fa fa-key"></i> Kreativitas
                            <a href="{{ URL::to('skp/tahunan/kreativitas/'.$id.'/create') }}" class="btn btn-default pull-right">Add Kreativitas</a>
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
                                        <th>Tanggal</th>
                                        <th>Kegiatan Kreativitas</th>
                                        <th>Kuantitas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($kreatifs as $index => $kreatif)
                                    <tr>

                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $kreatif->tanggal_kreativitas }}</td>
                                        <td>{{ $kreatif->kegiatan_kreativitas }}</td>
                                        @foreach ($satuan as $satu)
                                            @if ($satu->id === $kreatif->satuan_kegiatan_id)
                                                <td>{{ $kreatif->kuantitas .' '. $satu->satuan_kegiatan }}</td>                                                
                                            @endif
                                        @endforeach
                                        <td>
                                            <a href="{{ route('kreativitas.edit', $kreatif->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['kreativitas.destroy', $kreatif->id], 'onsubmit' => 'return confirm("Yakin menghapus data ini?")' ]) !!}
                                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                    @empty
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
