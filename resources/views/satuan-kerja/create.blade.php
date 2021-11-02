@extends('layouts.app')

@section('title', '| Add Satuan Kerja')

@section('content')

   <div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-3 col-lg-6">
                  <div class="card">
                      <div class="card-header">
                          <strong class="card-title">Add Satuan Kerja</strong>
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
                           {{ Form::open(array('url' => 'satker')) }}

                            <div class="form-group">
                                {{ Form::label('satuan_kerja', 'Satuan Kerja') }}
                                {{ Form::text('satuan_kerja', null, array('class' => 'form-control','required')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('alamat', 'Alamat' ) }}
                                {{ Form::textarea('alamat', null, array('class' => 'form-control', 'rows')) }}
                            </div>

                            {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

                        {{ Form::close() }}
                      </div>
                  </div>
              </div>
          </div>
       </div>
  </div>
@endsection




