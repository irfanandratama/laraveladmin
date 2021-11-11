{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.app')

@section('title', '| Validasi Target SKP Tahunan')

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
                          <strong class="card-title"> <h1><i class='fa fa-user-plus'></i> Validasi Target SKP Tahunan </h1></strong>
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
                            {{ Form::model($skpline, array('route' => array('target.validation', $skpline->id), 'method' => 'PUT')) }}

                            <div class="row">
                                <div class="col-md-6 col-lg-6">Data Lama</div>
                                <div class="col-md-6 col-lg-6">Data Baru</div>
                            </div>
                            
                            <div class="row form-group">
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('kegiatan', 'Kegiatan Tahunan') }}
                                    {{ Form::textarea('kegiatan', null, array('class' => 'form-control', 'rows', 'name'=>'kegiatan', 'id'=>'kegiatan', 'readonly')) }}
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('kegiatan', 'Kegiatan Tahunan') }}
                                    {{ Form::textarea('kegiatan', $validation_temp->kegiatan, array('class' => 'form-control', 'rows', 'name'=>'kegiatan', 'id'=>'kegiatan', 'readonly')) }}
                                </div>
                                
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('kuantitas_target', 'Target Kuantitas') }}
                                    {{ Form::number('kuantitas_target', null, array('class' => 'form-control', 'readonly')) }}
                                    {{ Form::hidden('kualitas_target', $skpline->kualitas_target) }}
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('kuantitas_target', 'Target Kuantitas') }}
                                    {{ Form::number('kuantitas_target', $validation_temp->kuantitas_target, array('class' => 'form-control', 'readonly')) }}
                                    {{ Form::hidden('kualitas_target', $validation_temp->kualitas_target) }}
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('satuan_kegiatan', 'Satuan Kegiatan') }}
                                    {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), null, array('class' => 'form-control', 'readonly')) }}
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('satuan_kegiatan', 'Satuan Kegiatan') }}
                                    {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), $validation_temp->satuan_kegiatan_id, array('class' => 'form-control', 'readonly')) }}
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('angka_kredit', 'Angka Kredit') }}
                                    {{ Form::number('angka_kredit_target', null, array('class' => 'form-control', 'readonly')) }}
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('angka_kredit', 'Angka Kredit') }}
                                    {{ Form::number('angka_kredit_target', $validation_temp->angka_kredit_target, array('class' => 'form-control', 'readonly')) }}
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('waktu_target', 'Target Waktu (Dalam hitungan bulan)') }}<br>
                                    {{ Form::number('waktu_target', null, array('class' => 'form-control', 'readonly')) }}
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('waktu_target', 'Target Waktu (Dalam hitungan bulan)') }}<br>
                                    {{ Form::number('waktu_target', $validation_temp->waktu_target, array('class' => 'form-control', 'readonly')) }}
                                </div>
                                
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('biaya_target', 'Biaya') }}<br>
                                    {{ Form::number('biaya_target', null, array('class' => 'form-control', 'readonly')) }}
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    {{ Form::label('biaya_target', 'Biaya') }}<br>
                                    {{ Form::number('biaya_target', $validation_temp->biaya_target, array('class' => 'form-control', 'readonly')) }}
                                </div>
                                
                            </div>

                            {{ Form::hidden('skp_tahunan_header_id', $skpline->skp_tahunan_header_id)}}

                            <div class="row form-group">
                                @if ($skpline->status == '04')
                                    {{ Form::submit('Confirm Update', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                    {{ Form::submit('Decline Update', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                @endif
                                
                                @if ($skpline->status == '07')
                                    {{ Form::submit('Confirm Delete', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                    {{ Form::submit('Decline Delete', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                @endif
                                <a href="{{ route('target.show', $skpheader->id) }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
                            </div>

                            {{ Form::close() }}
                        @else
                            {{ Form::model($skpline, array('route' => array('target.validation', $skpline->id), 'method' => 'PUT')) }}

                            <div class="row">
                                <div class="col-md-6 col-lg-6">Data Lama</div>
                                <div class="col-md-6 col-lg-6">Data Baru</div>
                            </div>

                            <div class="form-group">
                                {{ Form::label('kegiatan', 'Kegiatan Tahunan') }}
                                {{ Form::textarea('kegiatan', null, array('class' => 'form-control', 'rows', 'name'=>'kegiatan', 'id'=>'kegiatan', 'readonly')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('kuantitas_target', 'Target Kuantitas') }}
                                {{ Form::number('kuantitas_target', null, array('class' => 'form-control', 'readonly')) }}
                                {{ Form::hidden('kualitas_target', $skpline->kualitas_target) }}
                            </div>

                            <div>
                                {{ Form::label('satuan_kegiatan', 'Satuan Kegiatan') }}
                                {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), null, array('class' => 'form-control', 'readonly')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('angka_kredit', 'Angka Kredit') }}
                                {{ Form::number('angka_kredit_target', null, array('class' => 'form-control', 'readonly')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('waktu_target', 'Target Waktu (Dalam hitungan bulan)') }}<br>
                                {{ Form::number('waktu_target', null, array('class' => 'form-control', 'readonly')) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('biaya_target', 'Biaya') }}<br>
                                {{ Form::number('biaya_target', null, array('class' => 'form-control', 'readonly')) }}
                            </div>

                            {{ Form::hidden('skp_tahunan_header_id', $skpline->skp_tahunan_header_id)}}

                            <div class="row form-group">
                                {{ Form::submit('Confirm', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                {{ Form::submit('Decline', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                <a href="{{ route('target.show', $skpheader->id) }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
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