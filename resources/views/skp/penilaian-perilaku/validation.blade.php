{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.app')

@section('title', '| Validasi Penilaian Perilaku')

@section('content')

 <div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-3 col-lg-6">
                @include('skp.tahunan.detail')
                  <div class="card">
                      <div class="card-header">
                          <strong class="card-title"> <h1><i class='fa fa-user-plus'></i> Validasi Penilaian Perilaku
                        </h1></strong>
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
                            {{ Form::model($penilaian, $penilaian ? array('route' => array('penilaian.validation', $penilaian->id), 'method' => 'PUT') : array('route' => array('penilaian.store'), 'method' => 'POST')) }}

                            <div class="row form-group">
                                <div class="col col-md-4"> </div>
                                <div class="col col-md-4">Data Lama</div>
                                <div class="col col-md-4">Data Baru</div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-4">{{ Form::label('ori', 'Orientasi Pelayanan') }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('orientasi_pelayanan', null, array('class' => 'form-control', 'disabled')) }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('orientasi_pelayanan', $validation_temp->orientasi_pelayanan, array('class' => 'form-control', 'disabled')) }}</div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-4">{{ Form::label('intg', 'Integritas') }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('integritas', null, array('class' => 'form-control', 'disabled')) }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('integritas', $validation_temp->integritas, array('class' => 'form-control', 'disabled')) }}</div>

                            </div>

                            <div class="row form-group">
                                <div class="col col-md-4">{{ Form::label('kmtmn', 'Komitmen') }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('komitmen', null, array('class' => 'form-control', 'disabled')) }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('komitmen', $validation_temp->komitmen, array('class' => 'form-control', 'disabled')) }}</div>

                            </div>

                            <div class="row form-group">
                                <div class="col col-md-4">{{ Form::label('dspln', 'Disiplin') }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('disiplin', null, array('class' => 'form-control', 'disabled')) }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('disiplin', $validation_temp->disiplin, array('class' => 'form-control', 'disabled')) }}</div>

                            </div>

                            <div class='row form-group'>
                                <div class="col col-md-4">{{ Form::label('kjs', 'Kerjasama') }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('kerjasama', null, array('class' => 'form-control', 'disabled')) }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('kerjasama', $validation_temp->kerjasama, array('class' => 'form-control', 'disabled')) }}</div>

                            </div>

                            <div class="row form-group">
                                <div class="col col-md-4">{{ Form::label('kpmpn', 'Kepemimpinan') }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('kepemimpinan', null, array('class' => 'form-control', 'disabled')) }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('kepemimpinan', $validation_temp->kepemimpinan, array('class' => 'form-control', 'disabled')) }}</div>

                            </div>

                            <div class="row form-group">
                                <div class="col col-md-4">{{ Form::label('jml', 'Jumlah (Akan terisi otomatis bila sudah pernah menyimpan data penilaian perilaku)') }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('jumlah', null, array('class' => 'form-control', 'disabled')) }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('jumlah', $validation_temp->jumlah, array('class' => 'form-control', 'disabled')) }}</div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-4">{{ Form::label('rata', 'Rata-rata (Akan terisi otomatis bila sudah pernah menyimpan data penilaian perilaku)') }}</div>
                                <div class="col-12 col-md-4">{{ Form::number('rata_rata', null, array('class' => 'form-control', 'disabled'))}}</div>
                                <div class="col-12 col-md-4">{{ Form::number('rata_rata', $validation_temp->rata_rata, array('class' => 'form-control', 'disabled'))}}</div>
                            </div>

                            {{ Form::hidden('skp_tahunan_header_id', $skpheader->id)}}

                            <div class="row form-group">
                                {{ Form::submit('Confirm Update', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                {{ Form::submit('Decline Update', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                <a href="{{ route('penilaian.index') }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
                            </div>
                        @else
                            {{ Form::model($penilaian, $penilaian ? array('route' => array('penilaian.validation', $penilaian->id), 'method' => 'PUT') : array('route' => array('penilaian.store'), 'method' => 'POST')) }}

                            <div class="row form-group">
                                <div class="col col-md-3">{{ Form::label('ori', 'Orientasi Pelayanan') }}</div>
                                <div class="col-12 col-md-9">{{ Form::number('orientasi_pelayanan', null, array('class' => 'form-control')) }}</div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">{{ Form::label('intg', 'Integritas') }}</div>
                                <div class="col-12 col-md-9">{{ Form::number('integritas', null, array('class' => 'form-control',)) }}</div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">{{ Form::label('kmtmn', 'Komitmen') }}</div>
                                <div class="col-12 col-md-9">{{ Form::number('komitmen', null, array('class' => 'form-control')) }}</div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">{{ Form::label('dspln', 'Disiplin') }}</div>
                                <div class="col-12 col-md-9">{{ Form::number('disiplin', null, array('class' => 'form-control')) }}</div>
                            </div>

                            <div class='row form-group'>
                                <div class="col col-md-3">{{ Form::label('kjs', 'Kerjasama') }}</div>
                                <div class="col-12 col-md-9">{{ Form::number('kerjasama', null, array('class' => 'form-control')) }}</div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">{{ Form::label('kpmpn', 'Kepemimpinan') }}</div>
                                <div class="col-12 col-md-9">{{ Form::number('kepemimpinan', null, array('class' => 'form-control')) }}</div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">{{ Form::label('jml', 'Jumlah (Akan terisi otomatis bila sudah pernah menyimpan data penilaian perilaku)') }}</div>
                                <div class="col-12 col-md-9">{{ Form::number('jumlah', null, array('class' => 'form-control', 'disabled')) }}</div>
                            </div>

                            <div class="row form-group">
                                <div class="col col-md-3">{{ Form::label('rata', 'Rata-rata (Akan terisi otomatis bila sudah pernah menyimpan data penilaian perilaku)') }}</div>
                                <div class="col-12 col-md-9">{{ Form::number('rata_rata', null, array('class' => 'form-control', 'disabled'))}}</div>
                            </div>

                            {{ Form::hidden('skp_tahunan_header_id', $skpheader->id)}}

                            <div class="row form-group">
                                {{ Form::submit('Confirm', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                {{ Form::submit('Decline', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                {{ Form::close() }}
                                <a href="{{ route('penilaian.index') }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
                            </div>
                        @endif
                        </div>
                        {{-- <button></button> --}}
                  </div>
              </div>
          </div>
       </div>
  </div>

@endsection
