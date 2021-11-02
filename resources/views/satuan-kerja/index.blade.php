{{-- \resources\views\roles\index.blade.php --}}
@extends('layouts.app')

@section('title', '| Satuan Kerja')

@section('content')

<div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-1 col-lg-10">
                  <div class="card">
                      <div class="card-header">
                          <h1><i class="fa fa-key"></i> Satuan Kerja
                           <a href="{{ URL::to('satker/create') }}" class="btn btn-default pull-right">Add Satuan Kerja</a>
                         </h1>
                      </div>
                      <div class="card-body">  
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Satuan Kerja</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($satkers as $index => $satker)
                                    <tr>

                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $satker->satuan_kerja }}</td>
                                        <td>
                                        <a href="{{ URL::to('satker/'.$satker->id.'/edit') }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                                        {!! Form::open(['method' => 'DELETE', 'route' => ['satker.destroy', $satker->id], 'onsubmit' => 'return confirm("Yakin menghapus data ini?")'  ]) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                        {!! Form::close() !!}

                                        </td>
                                    </tr>
                                    @endforeach
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