@extends('layouts.app')

@section('title', '| Validasi Tugas Tambahan')

@section('content')

 <div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-3 col-lg-6">
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
                          <strong class="card-title"> <h1><i class='fa fa-user-plus'></i> Validasi Tugas Tambahan </h1></strong>
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
                            {{ Form::model($kreatif, array('route' => array('kreativitas.validation', $kreatif->id), 'method' => 'PUT')) }}

                            <div class="row">
                                <div class="col-md-6 col-lg-6">Data Lama</div>
                                <div class="col-md-6 col-lg-6">Data Baru</div>
                            </div>

                            <div class="row form-group">
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('tanggal', 'Tanggal') }}
                                    {{ Form::date('tanggal_kreativitas', null, array('class' => 'form-control', 'min' => \Carbon\Carbon::now()->year.'-01-01', 'max' => \Carbon\Carbon::now()->add(1, 'year')->year.'-12-31', 'disabled')) }}
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('tanggal', 'Tanggal') }}
                                    {{ Form::date('tanggal_kreativitas', $validation_temp->tanggal_kreativitas, array('class' => 'form-control', 'min' => \Carbon\Carbon::now()->year.'-01-01', 'max' => \Carbon\Carbon::now()->add(1, 'year')->year.'-12-31', 'disabled')) }}
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('nama_tugas', 'Kreativitas') }}
                                    {{ Form::textarea('kegiatan_kreativitas', null, array('class' => 'form-control', 'rows', 'name'=>'kegiatan_kreativitas', 'id'=>'kegiatan_kreativitas', 'disabled')) }}
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('nama_tugas', 'Kreativitas') }}
                                    {{ Form::textarea('kegiatan_kreativitas', $validation_temp->kegiatan_kreativitas, array('class' => 'form-control', 'rows', 'name'=>'kegiatan_kreativitas', 'id'=>'kegiatan_kreativitas', 'disabled')) }}
                                </div>
                                
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('no_sk', 'Kuantitas') }}
                                        {{ Form::text('kuantitas', null, array('class' => 'form-control', 'disabled')) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('no_sk', 'Kuantitas') }}
                                        {{ Form::text('kuantitas', $validation_temp->kegiatan, array('class' => 'form-control', 'disabled')) }}
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('no_sk', 'Satuan Kuantitas') }}
                                    {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), null, array('class' => 'form-control', 'disabled')) }}
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('no_sk', 'Satuan Kuantitas') }}
                                    {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), $validation_temp->satuan_kegiatan_id, array('class' => 'form-control', 'disabled')) }}
                                </div>
                            </div>

                            {{ Form::hidden('skp_tahunan_header_id', $kreatif->skp_tahunan_header_id)}}

                            <div class="row form-group">
                                @if ($kreatif->status == '04')
                                    {{ Form::submit('Confirm Update', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                    {{ Form::submit('Decline Update', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                @endif
                                
                                @if ($kreatif->status == '07')
                                    {{ Form::submit('Confirm Delete', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                    {{ Form::submit('Decline Delete', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                @endif
                                <a href="{{ route('kreativitas.show', $skpheader->id) }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
                            </div>

                            {{ Form::close() }}
                        @else
                            {{ Form::model($kreatif, array('route' => array('kreativitas.validation', $kreatif->id), 'method' => 'PUT')) }}

                            <div class="form-group">
                                {{ Form::label('tanggal', 'Tanggal') }}
                                {{ Form::date('tanggal_kreativitas', null, array('class' => 'form-control', 'min' => \Carbon\Carbon::now()->year.'-01-01', 'max' => \Carbon\Carbon::now()->add(1, 'year')->year.'-12-31', 'disabled')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('nama_tugas', 'Kreativitas') }}
                                {{ Form::textarea('kegiatan_kreativitas', null, array('class' => 'form-control', 'rows', 'name'=>'kegiatan_kreativitas', 'id'=>'kegiatan_kreativitas', 'disabled')) }}
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('no_sk', 'Kuantitas') }}
                                        {{ Form::text('kuantitas', null, array('class' => 'form-control', 'disabled')) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('no_sk', 'Satuan Kuantitas') }}
                                        {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), null, array('class' => 'form-control', 'disabled')) }}
                                    </div>
                                </div>
                            </div>

                            {{ Form::hidden('skp_tahunan_header_id', $kreatif->skp_tahunan_header_id)}}

                            <div class="row form-group">
                                {{ Form::submit('Confirm', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                {{ Form::submit('Decline', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                <a href="{{ route('kreativitas.show', $skpheader->id) }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
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