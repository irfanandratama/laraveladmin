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
                          <strong class="card-title"> <h1><i class='fa fa-user-plus'></i> Add Tugas Tambahan </h1></strong>
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

                        {{ Form::open(array('route' => 'tugas.store')) }}

                        <div class="form-group">
                            {{ Form::label('tahun', 'Tahun') }}
                            {{ Form::text('tahun', $tahun, array('class' => 'form-control', 'readonly')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('nama_tugas', 'Nama Tugas Tambahan') }}
                            {{ Form::textarea('nama_tugas', null, array('class' => 'form-control', 'rows', 'name'=>'nama_tugas', 'id'=>'nama_tugas')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('no_sk', 'Nomor SK') }}
                            {{ Form::text('no_sk', null, array('class' => 'form-control')) }}
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