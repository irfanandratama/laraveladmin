{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.app')

@section('title', '| Add Target SKP Tahunan')

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
                          <strong class="card-title"> <h1><i class='fa fa-user-plus'></i> Add Realisasi SKP Tahunan </h1></strong>
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

                        {{ Form::model($skpline, array('route' => array('realisasi.store'))) }}


                        <div class="form-group">
                            {{ Form::label('kegiatan', 'Kegiatan Tahunan') }}
                            {{ Form::textarea('kegiatan', null, array('class' => 'form-control', 'rows', 'name'=>'kegiatan', 'id'=>'kegiatan')) }}
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
                                    {{ Form::number('kuantitas_realisasi', null, array('class' => 'form-control')) }}
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
                                    {{ Form::number('kualitas_realisasi', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        <div>
                            {{ Form::label('satuan_kegiatan', 'Satuan Kegiatan') }}
                            {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), null, array('class' => 'form-control')) }}
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('angka_kredit', 'Angka Kredit Target') }}
                                    {{ Form::number('angka_kredit_target', null, array('class' => 'form-control', 'disabled')) }}
                                </div>
                                <div class="col=lg-6 col-md-6">
                                    {{ Form::label('angka_kredit', 'Angka Kredit Realisasi') }}
                                    {{ Form::number('angka_kredit_realisasi', null, array('class' => 'form-control')) }}
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
                                    {{ Form::number('waktu_realisasi', null, array('class' => 'form-control')) }}
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
                                    {{ Form::number('biaya_realisasi', null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                            
                        </div>

                        {{ Form::hidden('skp_tahunan_header_id', $skpheader->id)}}
                        {{ Form::hidden('skplineid', $skpline->id)}}

                        {{ Form::submit('Add Realisasi', array('class' => 'btn btn-primary')) }}

                        {{ Form::close() }}
                        <a href="{{ url()->previous() }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
                    </div>
                  </div>
              </div>
          </div>
       </div>
  </div>

@endsection