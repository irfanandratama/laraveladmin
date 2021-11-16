{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.app')

@section('title', '| Add User')

@section('content')

 <div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-3 col-lg-6">
                  <div class="card">
                      <div class="card-header">
                          <strong class="card-title"> <h1><i class='fa fa-user-plus'></i> Add User</h1></strong>
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

                            {{ Form::open(array('url' => 'users')) }}

                            <div class="form-group">
                                {{ Form::label('nip', 'NIP') }}
                                {{ Form::text('nip', '', array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('name', 'Name') }}
                                {{ Form::text('name', '', array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('email', 'Email') }}
                                {{ Form::email('email', '', array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('jabatan', 'Jabatan') }}
                                {{ Form::text('jabatan', '', array('class' => 'form-control')) }}
                            </div>

                            <h5><b>Give Role</b></h5>
                            <div class='form-group'>
                                @foreach ($roles as $role)
                                    {{ Form::checkbox('roles[]',  $role->id ) }}
                                    {{ Form::label($role->name, ucfirst($role->name)) }}<br>
                                @endforeach
                            </div>

                            <div class="form-group">
                                {{ Form::label('password', 'Password') }}<br>
                                {{ Form::password('password', array('class' => 'form-control')) }}

                            </div>

                            <div class="form-group">
                                {{ Form::label('password', 'Confirm Password') }}<br>
                                {{ Form::password('password_confirmation', array('class' => 'form-control')) }}

                            </div>

                            <div class="form-group">
                                {{ Form::label('atasan_1_id', 'Atasan Langsung') }}<br>
                                {{ Form::select('atasan_1_id', $atasans->prepend('-- Silahkan Pilih Atasan Langsung --', ''), null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('atasan_2_id', 'Atasan Pejabat Langsung') }}<br>
                                {{ Form::select('atasan_2_id', $atasans->prepend('-- Silahkan Pilih Atasan Lain --', ''), null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('atasan_3_id', 'Atasan Lain') }}<br>
                                {{ Form::select('atasan_3_id', $atasans->prepend('-- Silahkan Pilih Atasan Lain --', ''), null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('pangkat_id', 'Pangkat') }}<br>
                                {{ Form::select('pangkat_id', $pangkats->prepend('-- Silahkan Pilih Pangkat --', ''), null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('satuan_kerja_id', 'Satuan Kerja') }}<br>
                                {{ Form::select('satuan_kerja_id', $satkers->prepend('-- Silahkan Pilih Satuan Kerja --', ''), null, array('class' => 'form-control')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('is_atasan', 'Sebagai Atasan?') }}<br>
                                {{ Form::checkbox('is_atasan') }}
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