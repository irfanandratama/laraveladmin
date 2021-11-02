@extends('layouts.app')

@section('title', '| Edit Satuan Kerja')

@section('content')

<div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-3 col-lg-6">
                  <div class="card">
                      <div class="card-header">
                          <strong class="card-title"> <h1><i class='fa fa-key'></i> Edit Role: {{$satker->satuan_kerja}}</h1></strong>
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
                        {{ Form::model($satker, array('route' => array('satker.update', $satker->id), 'method' => 'PUT')) }}

                        <div class="form-group">
                            {{ Form::label('satuan_kerja', 'Satuan Kerja') }}
                            {{ Form::text('satuan_kerja', null, array('class' => 'form-control','required')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('alamat', 'Alamat' ) }}
                            {{ Form::textarea('alamat', null, array('class' => 'form-control', 'rows')) }}
                        </div>

                        {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

                        {{ Form::close() }}    
                     </div>
                  </div>
              </div>
          </div>
       </div>
  </div>    


@endsection