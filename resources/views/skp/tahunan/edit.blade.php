{{-- \resources\views\users\edit.blade.php --}}

@extends('layouts.app')

@section('title', '| Edit SKP Tahunan')

@section('content')

<div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-3 col-lg-6">
                  <div class="card">
                      <div class="card-header">
                          <strong class="card-title"><h1><i class='fa fa-user-plus'></i> Edit SKP Tahunan {{$skp->name}}</h1></strong>
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

                            {{ Form::model($skp, array('route' => array('tahunan.update', $skp->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with user data --}}

                            <div class="form-group">
                                {{ Form::label('periode_mulai', 'Periode Mulai') }}
                                {{ Form::date('periode_mulai', null, array('class' => 'form-control ')) }}
                            </div>
        
                            <div class="form-group">
                                {{ Form::label('periode_selesai', 'Periode Selesai') }}
                                {{ Form::date('periode_selesai', null, array('class' => 'form-control ')) }}
                            </div>
        
                            @hasanyrole('Administrator|Super-Admin')
                                <div class="form-group">
                                    {{ Form::label('user_id', 'Nama Pegawai') }}<br>
                                    {{ Form::select('user_id', $users, null, array('class' => 'form-control ')) }}
                                </div>
                            @else
                                <div class="form-group">
                                    {{ Form::label('name', 'Nama') }}
                                    {{ Form::text('name', null, array('class' => 'form-control ', 'disabled')) }}
                                    {{ Form::hidden('user_id', null) }}
                                </div>
                            @endhasanyrole
                            
        
                            {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}
        
                        {{ Form::close() }}

                      </div>
                  </div>
              </div>
          </div>
       </div>
  </div>       

@endsection