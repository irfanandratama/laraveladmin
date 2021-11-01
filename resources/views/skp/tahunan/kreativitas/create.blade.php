@extends('layouts.app')

@section('title', '| Add Tugas Tambahan')

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
                          <strong class="card-title"> <h1><i class='fa fa-user-plus'></i> Add Kreativitas </h1></strong>
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

                        {{ Form::open(array('route' => 'kreativitas.store')) }}

                        <div class="form-group">
                            {{ Form::label('tahun', 'Tanggal') }}
                            {{ Form::date('tanggal_kreativitas', null, array('class' => 'form-control', 'min' => \Carbon\Carbon::now()->year.'-01-01', 'max' => \Carbon\Carbon::now()->add(1, 'year')->year.'-12-31')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('nama_tugas', 'Kreativitas') }}
                            {{ Form::textarea('kegiatan_kreativitas', null, array('class' => 'form-control', 'rows', 'name'=>'kegiatan_kreativitas', 'id'=>'kegiatan_kreativitas')) }}
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('no_sk', 'Kuantitas') }}
                                    {{ Form::text('kuantitas', null, array('class' => 'form-control')) }}
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    {{ Form::label('no_sk', 'Satuan Kuantitas') }}
                                    {{ Form::select('satuan_kegiatan_id', $satuankegiatan->prepend('-- Silahkan Pilih Satuan Kegiatan --', ''), null, array('class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>

                        {{ Form::hidden('skp_tahunan_header_id', $skpheader->id)}}

                        {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

                        {{ Form::close() }}
                    </div>
                  </div>
              </div>
          </div>
       </div>
  </div>

@endsection