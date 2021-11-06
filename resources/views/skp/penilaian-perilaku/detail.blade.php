{{-- \resources\views\users\create.blade.php --}}
@extends('layouts.app')

@section('title', '| Penilaian Perilaku')

@section('content')

 <div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-3 col-lg-6">
                @include('skp.tahunan.detail')
                  <div class="card">
                      <div class="card-header">
                          <strong class="card-title"> <h1><i class='fa fa-user-plus'></i> Penilaian Perilaku</h1></strong>
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

                            {{ Form::model($penilaian, $penilaian ? array('route' => array('penilaian.update', $penilaian->id), 'method' => 'PUT') : array('route' => array('penilaian.store'), 'method' => 'POST')) }}

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
                                @if ($penilaian)
                                {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                                @else
                                    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}
                                @endif

                                {{ Form::close() }}
                                <a href="{{ route('penilaian.index') }}" class="btn btn-default pull-left" style="margin-right: 3px;">Cancel</a>
                            </div>
                            
                        </div>
                        {{-- <button></button> --}}
                  </div>
              </div>
          </div>
       </div>
  </div>

@endsection

{{-- @section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        function calculation() {
                var orientasi = document.getElementById('orientasi_pelayanan').value ? document.getElementById('orientasi_pelayanan').value || '0'
                var integritas = document.getElementById('integritas').value ? document.getElementById('integritas').value || '0'
                var komitmen = document.getElementById('komitmen') ? document.getElementById('komitmen').value || '0'
                var disiplin = document.getElementById('disiplin') ? document.getElementById('disiplin').value || '0'
                var kerjasama = document.getElementById('kerjasama') ? document.getElementById('kerjasama').value || '0'
                var kepemimpinan = document.getElementById('kepemimpinan') ? document.getElementById('kepemimpinan').value || '0'

                var total = document.getElementById('jumlah');
                var rerata = document.getElementById('rata-rata');

                total.value = parseInt(orientasi)  + parseInt(integritas) + parseInt(komitmen) + parseInt(disiplin) + parseInt(kerjasama) + parseInt(kepemimpinan);
                rerata.value = total.value / 6;

            }
        $(document).ready(function(){
            calculation();
        });
    </script>
@endsection --}}
