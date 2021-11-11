{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.app')

@section('title', '| Validasi Realisasi SKP Tahunan')

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
                          <strong class="card-title"> <h1><i class='fa fa-user-plus'></i> Validasi Realisasi SKP Tahunan </h1></strong>
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
                            {{ Form::model($skpline, array('route' => array('realisasi.validation', $skpline->id), 'method' => 'PUT')) }}

                            <div class="row">
                                <div class="col-md-6 col-lg-6">Data Lama</div>
                                <div class="col-md-6 col-lg-6">Data Baru</div>
                            </div>

                            <div class="row form-group">
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('kegiatan', 'Kegiatan Tahunan') }}
                                    {{ Form::textarea('kegiatan', null, array('class' => 'form-control', 'rows', 'name'=>'kegiatan', 'id'=>'kegiatan', 'disabled')) }}
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('kegiatan', 'Kegiatan Tahunan') }}
                                    {{ Form::textarea('kegiatan', $validation_temp->kegiatan, array('class' => 'form-control', 'rows', 'name'=>'kegiatan', 'id'=>'kegiatan', 'disabled')) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('kuantitas_realisasi', 'Realisasi Kuantitas') }}
                                        {{ Form::number('kuantitas_realisasi', null, array('class' => 'form-control', 'readonly')) }}
                                        {{ Form::hidden('kualitas_realisasi', 100) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('kuantitas_realisasi', 'Realisasi Kuantitas') }}
                                        {{ Form::number('kuantitas_realisasi', $validation_temp->kuantitas_realisasi, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('kualitas_realisasi', 'Realisasi Kualitas') }}
                                        {{ Form::number('kualitas_realisasi', null, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('kualitas_realisasi', 'Realisasi Kualitas') }}
                                        {{ Form::number('kualitas_realisasi', $validation_temp->kualitas_realisasi, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('satuan_kegiatan', 'Satuan Kegiatan') }}
                                    {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), null, array('class' => 'form-control', 'readonly')) }}
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('satuan_kegiatan', 'Satuan Kegiatan') }}
                                    {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), $validation_temp->satuan_kegiatan_id, array('class' => 'form-control', 'readonly')) }}
                                </div>
                                
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('angka_kredit', 'Angka Kredit Realisasi') }}
                                        {{ Form::number('angka_kredit_realisasi', null, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                    <div class="col=lg-6 col-md-6">
                                        {{ Form::label('angka_kredit', 'Angka Kredit Realisasi') }}
                                        {{ Form::number('angka_kredit_realisasi', $validation_temp->angka_kredit_realisasi, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('waktu_realisasi', 'Realisasi Waktu (Dalam hitungan bulan)') }}<br>
                                        {{ Form::number('waktu_realisasi', null, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('waktu_realisasi', 'Realisasi Waktu (Dalam hitungan bulan)') }}<br>
                                        {{ Form::number('waktu_realisasi', $validation_temp->waktu_realisasi, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                </div>
                                
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('biaya_realisasi', 'Realisasi Biaya') }}<br>
                                        {{ Form::number('biaya_realisasi', null, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('biaya_realisasi', 'Realisasi Biaya') }}<br>
                                        {{ Form::number('biaya_realisasi', $validation_temp->biaya_realisasi, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                </div>
                                
                            </div>

                            {{ Form::hidden('skp_tahunan_header_id', $skpheader->id)}}

                            <div class="row form-group">
                                @if ($skpline->status == '01')
                                    {{ Form::submit('Confirm', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                    {{ Form::submit('Decline', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                @endif

                                @if ($skpline->status == '04')
                                    {{ Form::submit('Confirm Update', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                    {{ Form::submit('Decline Update', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                @endif
                                
                                @if ($skpline->status == '07')
                                    {{ Form::submit('Confirm Delete', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                    {{ Form::submit('Decline Delete', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                @endif
                                <a href="{{ url()->previous() }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
                            </div>

                            {{ Form::close() }}
                        @else
                            {{ Form::model($skpline, array('route' => array('realisasi.validation', $skpline->id), 'method' => 'PUT')) }}


                            <div class="form-group">
                                {{ Form::label('kegiatan', 'Kegiatan Tahunan') }}
                                {{ Form::textarea('kegiatan', null, array('class' => 'form-control', 'rows', 'name'=>'kegiatan', 'id'=>'kegiatan', 'disabled')) }}
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col=lg-6 col-md-6">
                                        {{ Form::label('kuantitas_target', 'Target Kuantitas') }}
                                        {{ Form::number('kuantitas_target', null, array('class' => 'form-control', 'disabled')) }}
                                        {{ Form::hidden('kualitas_target', 100) }}
                                    </div>
                                    <div class="col=lg-6 col-md-6">
                                        {{ Form::label('kuantitas_realisasi', 'Realisasi Kuantitas') }}
                                        {{ Form::number('kuantitas_realisasi', null, array('class' => 'form-control', 'readonly')) }}
                                        {{ Form::hidden('kualitas_realisasi', 100) }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('kualitas_target', 'Target Kualitas') }}
                                        {{ Form::number('kualitas_target', null, array('class' => 'form-control', 'disabled')) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('kualitas_realisasi', 'Realisasi Kualitas') }}
                                        {{ Form::number('kualitas_realisasi', null, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                {{ Form::label('satuan_kegiatan', 'Satuan Kegiatan') }}
                                {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), null, array('class' => 'form-control', 'readonly')) }}
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('angka_kredit', 'Angka Kredit Target') }}
                                        {{ Form::number('angka_kredit_target', null, array('class' => 'form-control', 'disabled')) }}
                                    </div>
                                    <div class="col=lg-6 col-md-6">
                                        {{ Form::label('angka_kredit', 'Angka Kredit Realisasi') }}
                                        {{ Form::number('angka_kredit_realisasi', null, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('waktu_target', 'Target Waktu (Dalam hitungan bulan)') }}<br>
                                        {{ Form::number('waktu_target', null, array('class' => 'form-control' , 'disabled')) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('waktu_realisasi', 'Realisasi Waktu (Dalam hitungan bulan)') }}<br>
                                        {{ Form::number('waktu_realisasi', null, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                </div>
                                
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('biaya_target', 'Target Biaya') }}<br>
                                        {{ Form::number('biaya_target', null, array('class' => 'form-control', 'disabled')) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        {{ Form::label('biaya_realisasi', 'Realisasi Biaya') }}<br>
                                        {{ Form::number('biaya_realisasi', null, array('class' => 'form-control', 'readonly')) }}
                                    </div>
                                </div>
                                
                            </div>

                            {{ Form::hidden('skp_tahunan_header_id', $skpheader->id)}}

                            <div class="row form-group">
                                {{ Form::submit('Confirm', array('class' => 'btn btn-primary', 'name' => 'action', 'value' => 'confirm')) }}
                                {{ Form::submit('Decline', array('class' => 'btn btn-danger', 'name' => 'action', 'value' => 'decline')) }}
                                <a href="{{ url()->previous() }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
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