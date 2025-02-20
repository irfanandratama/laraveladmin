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
                          <strong class="card-title"> <h1><i class='fa fa-user-plus'></i> Add Target SKP Tahunan </h1></strong>
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

                        {{ Form::open(array('route' => 'target.store')) }}

                        <div class="form-group">
                            {{ Form::label('kegiatan', 'Kegiatan Tahunan') }}
                            {{ Form::textarea('kegiatan', null, array('class' => 'form-control', 'rows', 'name'=>'kegiatan', 'id'=>'kegiatan')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('kuantitas_target', 'Target Kuantitas') }}
                            {{ Form::number('kuantitas_target', '', array('class' => 'form-control')) }}
                            {{ Form::hidden('kualitas_target', 100) }}
                        </div>

                        <div>
                            {{ Form::label('satuan_kegiatan', 'Satuan Kegiatan') }}
                            {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), null, array('class' => 'form-control')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('angka_kredit', 'Angka Kredit') }}
                            {{ Form::number('angka_kredit_target', '', array('class' => 'form-control')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('waktu_target', 'Target Waktu (Dalam hitungan bulan)') }}<br>
                            {{ Form::number('waktu_target', '', array('class' => 'form-control')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('biaya_target', 'Biaya') }}<br>
                            {{ Form::number('biaya_target', '', array('class' => 'form-control')) }}
                        </div>

                        {{ Form::hidden('skp_tahunan_header_id', $skpheader->id)}}

                        <div class="row">
                            {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
                            <a href="{{ route('target.show', $skpheader->id) }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
                        </div>
                        
                        {{ Form::close() }}
                    </div>
                  </div>
              </div>
          </div>
       </div>
  </div>

@endsection