{{-- \resources\views\roles\index.blade.php --}}
@extends('layouts.app')

@section('title', '| SKP Tahunan')

@section('content')

<div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-1 col-lg-10">
                   <div class="form-group"><a href="{{ route('tahunan.index') }}" class="btn btn-default pull-left" style="margin-right: 3px;">Back</a></div><br><br>
                   @include('skp.tahunan.detail')
                  <div class="card">
                      <div class="card-header">
                          <h1><i class="fa fa-key"></i> Target SKP Tahunan
                            @if (count($skplines) > 0)
                                <a href="{{ URL::to('skp/tahunan/target/export/'.$id.'') }}" class="btn btn-default pull-right">Cetak Form SKP</a>
                            @endif
                            <a href="{{ URL::to('skp/tahunan/target/'.$id.'/create') }}" class="btn btn-default pull-right">Add Target SKP Tahunan</a>
                         </h1>
                      </div>
                      <div class="card-body">  
                        @if ($message = Session::get('flash_message'))
                            <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>    
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        @if ($errors->any())
                        <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Kegiatan Tahunan</th>
                                        <th>Target Kuantitas</th>
                                        <th>Target Kualitas</th>
                                        <th>Target Waktu</th>
                                        <th>Biaya</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($skplines as $index => $skpline)
                                    <tr>

                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $tahun }}</td>
                                        <td>{{ $skpline->kegiatan }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                                        <td>{{ $skpline->kuantitas_target }}</td>
                                        <td>{{ $skpline->kualitas_target }}</td>
                                        <td>{{ $skpline->waktu_target }}</td>
                                        <td>{{ $skpline->biaya_target }}</td>
                                        <td>{{ $skpline->keterangan }}</td>
                                        <td>
                                            @if (strpos(strtolower($skpline->keterangan), 'diterima') !== false || strpos(strtolower($skpline->keterangan), 'disetujui') !== false || strpos(strtolower($skpline->keterangan), 'ditolak') !== false && $skpline->status !== '03')
                                                <a href="{{ route('target.edit', $skpline->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                                                {!! Form::open(['method' => 'DELETE', 'route' => ['target.destroy', $skpline->id], 'onsubmit' => 'return confirm("Yakin menghapus data ini?")' ]) !!}
                                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                {!! Form::close() !!}
                                            @endif

                                            @if (strpos(strtolower($skpline->keterangan), 'pengajuan') !== false)
                                                @hasanyrole('Kepegawaian')
                                                    <a href="{{ route('target.validate_data', $skpline->id) }}" class="btn btn-primary pull-left" style="margin-right: 3px;">Validasi</a>
                                                @endhasanyrole
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tbody>
                                            <tr>
                                                <td colSpan="8" style="text-align: center">Data Not Found</td>
                                            </tr>
                                        </tbody>
                                    @endforelse
                                </tbody>

                            </table>
                            {{ $skplines->links() }}
                        </div>   
                     </div>
                  </div>
              </div>
          </div>
       </div>
  </div>

@endsection
