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
                                        <th>Aksi</th>
                                        <th>Detil</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($skps as $index => $skp)
                                    <tr>

                                        <td>{{ $index + 1 }}</td>

                                        <td>{{ $skp->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($skp->periode_mulai)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($skp->periode_selesai)->format('d M Y') }}</td>{{-- Retrieve array of permissions associated to a role and convert to string --}}
                                        <td>
                                            <a href="{{ route('tahunan.edit', $skp->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                                            {{-- <a href="#" data-id="{{$skp->id}}" data-toggle="modal"  class="btn btn-info pull-left editModalBtn" style="margin-right: 3px;">Edit</a> --}}

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['tahunan.destroy', $skp->id], 'onsubmit' => 'return confirm("Yakin menghapus data ini ('. $skp->name . ' periode '.\Carbon\Carbon::parse($skp->periode_mulai)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($skp->periode_selesai)->format('d M Y').')? Hal ini juga akan menghapus seluruh target dan realisasi dari SKP yang berkaitan.")' ]) !!}
                                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!}
                                        </td>
                                        <td>
                                            <a href="{{ route('target.show', $skp->id) }}" class="btn btn-success pull-left" style="margin-right: 3px;">Target</a>
                                            <a href="{{ URL::to('skp/tahunan/realisasi/'.$skp->id.'') }}" class="btn btn-success pull-left" style="margin-right: 3px;">Realisasi</a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tbody>
                                            <tr>
                                                <td colSpan="5" style="text-align: center">Data Not Found</td>
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

@include('skp.tahunan.create')
