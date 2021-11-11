{{-- \resources\views\users\edit.blade.php --}}

@extends('layouts.app')

@section('title', '| Validasi SKP Tahunan')

@section('content')

<div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-3 col-lg-6">
                  <div class="card">
                      <div class="card-header">
                          <strong class="card-title"><h1><i class='fa fa-user-plus'></i> Validasi {{($skp->status == '04') ? 'Perubahan' : (($skp->status == '07') ? 'Penghapusan' : '')}} SKP Tahunan {{$skp->name}}</h1></strong>
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
                            
                            @if ($validation_temp)
                                {{ Form::model($skp, array('route' => array('tahunan.validation', $skp->id), 'method' => 'PUT')) }}

                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">Data Lama</div>
                                        <div class="col-md-6 col-lg-6">Data Baru</div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6 col-lg-6">
                                            {{ Form::label('periode_mulai', 'Periode Mulai') }}
                                            {{ Form::date('periode_mulai', null, array('class' => 'form-control ', 'disabled')) }}
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            {{ Form::label('periode_mulai', 'Periode Mulai') }}
                                            {{ Form::date('periode_mulai', $validation_temp->periode_mulai, array('class' => 'form-control ', 'disabled')) }}
                                        </div>
                                        
                                    </div>
                
                                    <div class="row form-group">
                                        <div class="col-md-6 col-lg-6">
                                            {{ Form::label('periode_selesai', 'Periode Selesai') }}
                                            {{ Form::date('periode_selesai', null, array('class' => 'form-control ', 'disabled')) }}
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            {{ Form::label('periode_selesai', 'Periode Selesai') }}
                                            {{ Form::date('periode_selesai', $validation_temp->periode_selesai, array('class' => 'form-control ', 'disabled')) }}
                                        </div>
                                        
                                    </div>
                
                                    @hasanyrole('Administrator|Super-Admin')
                                        <div class="row form-group">
                                            <div class="col-md-6 col-lg-6">
                                                {{ Form::label('user_id', 'Nama Pegawai') }}<br>
                                                {{ Form::select('user_id', $users, null, array('class' => 'form-control ', 'disabled')) }}
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                {{ Form::label('user_id', 'Nama Pegawai') }}<br>
                                                {{ Form::select('user_id', $users, $validation_temp->user_id, array('class' => 'form-control ', 'disabled')) }}
                                            </div>
                                            
                                        </div>
                                    @else
                                        <div class="row form-group">
                                            <div class="col-md-6 col-lg-6">
                                                {{ Form::label('name', 'Nama') }}
                                                {{ Form::text('name', null, array('class' => 'form-control ', 'disabled')) }}
                                                {{ Form::hidden('user_id', null) }}
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                {{ Form::label('name', 'Nama') }}
                                                {{ Form::text('name', null, array('class' => 'form-control ', 'disabled')) }}
                                                {{ Form::hidden('user_id', null) }}
                                            </div>
                                        </div>
                                    @endhasanyrole
                                    
                                    <div class="row form-group">
                                        @if ($skp->status == '04')
                                            {{ Form::submit('Confirm Update', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                            {{ Form::submit('Decline Update', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                        @endif
                                        
                                        @if ($skp->status == '07')
                                            {{ Form::submit('Confirm Delete', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                            {{ Form::submit('Decline Delete', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                        @endif
                                        <a href="{{ route('tahunan.index') }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
                                    </div>

                                {{ Form::close() }}
                            @else
                                {{ Form::model($skp, array('route' => array('tahunan.validation', $skp->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with user data --}}

                                    <div class="form-group">
                                        {{ Form::label('periode_mulai', 'Periode Mulai') }}
                                        {{ Form::date('periode_mulai', null, array('class' => 'form-control ', 'readonly')) }}
                                    </div>
                
                                    <div class="form-group">
                                        {{ Form::label('periode_selesai', 'Periode Selesai') }}
                                        {{ Form::date('periode_selesai', null, array('class' => 'form-control ', 'readonly')) }}
                                    </div>
                
                                    @hasanyrole('Administrator|Super-Admin')
                                        <div class="form-group">
                                            {{ Form::label('user_id', 'Nama Pegawai') }}<br>
                                            {{ Form::select('user_id', $users, null, array('class' => 'form-control ', 'readonly')) }}
                                        </div>
                                    @else
                                        <div class="form-group">
                                            {{ Form::label('name', 'Nama') }}
                                            {{ Form::text('name', null, array('class' => 'form-control ', 'disabled', 'readonly')) }}
                                            {{ Form::hidden('user_id', null) }}
                                        </div>
                                    @endhasanyrole
                                    
                                    <div class="row form-group">
                                        {{ Form::submit('Confirm', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                        {{ Form::submit('Decline', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                        <a href="{{ route('tahunan.index') }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
                                    </div>

                                {{ Form::close() }}
                            @endif
                            

                      </div>
                  </div>
              </div>
          </div>
       </div>
  </div>       

@endsection