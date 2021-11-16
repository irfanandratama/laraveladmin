{{-- \resources\views\roles\index.blade.php --}}
@extends('layouts.app')

@section('title', '| SKP Tahunan')

@section('content')

<div class="content-area">
          <div class="container-fluid">
            <div class="row">
               <div class="offset-md-1 col-lg-10">
                  <div class="card">
                      <div class="card-header">
                          <h1><i class="fa fa-key"></i> SKP Tahunan
                           <a href="" data-toggle="modal" data-target='#createModal' class="btn btn-default pull-right">Add SKP Tahunan</a>
                         </h1>
                      </div>
                      <div class="card-body">
                        @if ($message = Session::get('flash_message'))
                            <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>    
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block">
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
                                        <th>Periode SKP</th>
                                        <th>Nama Pegawai</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                        <th>Target & Realisasi</th>
                                        <th>Tugas Tambahan & Kreativitas</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($skps as $index => $skp)
                                    <tr>

                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $skp->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($skp->periode_mulai)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($skp->periode_selesai)->format('d M Y') }}</td>
                                        <td>{{ $skp->keterangan }}</td>
                                        <td style="text-align: center">
                                            @if (strpos(strtolower($skp->keterangan), 'diterima') !== false || strpos(strtolower($skp->keterangan), 'disetujui') !== false || strpos(strtolower($skp->keterangan), 'ditolak') !== false && $skp->status !== '03')
                                                <a href="{{ route('tahunan.edit', $skp->id) }}" class="btn btn-primary pull-left" style="margin-right: 3px;">Edit</a>

                                                {!! Form::open(['method' => 'DELETE', 'route' => ['tahunan.destroy', $skp->id], 'onsubmit' => 'return confirm("Yakin menghapus data ini ('. $skp->name . ' periode '.\Carbon\Carbon::parse($skp->periode_mulai)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($skp->periode_selesai)->format('d M Y').')? Hal ini juga akan menghapus seluruh target dan realisasi dari SKP yang berkaitan.")' ]) !!}
                                                {!! Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-danger']) !!}
                                                {!! Form::close() !!}
                                            @endif
                                            @if ($skp->printable)
                                                <a href="{{ route('tahunan.export', $skp->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Cetak</a>
                                            @endif

                                            
                                            @if (strpos(strtolower($skp->keterangan), 'pengajuan') !== false)
                                                @hasanyrole('Kepegawaian')
                                                    <a href="{{ route('tahunan.validate_data', $skp->id) }}" class="btn btn-primary pull-left" style="margin-right: 3px;">Validasi</a>
                                                @endhasanyrole
                                            @endif
                                            
                                        </td>
                                        <td>
                                            @if (strpos(strtolower($skp->keterangan), 'diterima') !== false || strpos(strtolower($skp->keterangan), 'disetujui') !== false || strpos(strtolower($skp->keterangan), 'ditolak') !== false && $skp->status !== '03')
                                                @hasanyrole('Kepegawaian|Pegawai')
                                                <a href="{{ route('target.show', $skp->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Target</a>
                                                @endhasanyrole
                                                @hasanyrole('Kepegawaian')
                                                <a href="{{ route('realisasi.show', $skp->id) }}" class="btn btn-primary pull-left" style="margin-right: 3px;">Realisasi</a>
                                                @endhasanyrole
                                            @endif
                                            
                                        </td>
                                        <td>
                                            @if (strpos(strtolower($skp->keterangan), 'diterima') !== false || strpos(strtolower($skp->keterangan), 'disetujui') !== false || strpos(strtolower($skp->keterangan), 'ditolak') !== false && $skp->status !== '03')
                                                @hasanyrole('Kepegawaian|Pegawai')
                                                <a href="{{ route('tugas.show', $skp->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Tugas Tambahan</a>
                                                <a href="{{ route('kreativitas.show', $skp->id) }}" class="btn btn-primary pull-left" style="margin-right: 3px;">Kreativitas</a>
                                                @endhasanyrole
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tbody>
                                            <tr>
                                                <td colSpan="7" style="text-align: center">Data Not Found</td>
                                            </tr>
                                        </tbody>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>   
                     </div>
                  </div>
              </div>
          </div>
       </div>
  </div>

@endsection('content')

@include('skp.tahunan.create')