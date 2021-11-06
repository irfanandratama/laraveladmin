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
                                        <th>Nama Pegawai</th>
                                        <th>Periode SKP</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($skps as $index => $skp)
                                    <tr>

                                        <td>{{ $index + 1 }}</td>

                                        <td>{{ $skp->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($skp->periode_mulai)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($skp->periode_selesai)->format('d M Y') }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('penilaian.show', $skp->id) }}" class="btn btn-info" style="margin-right: 3px; align-self: center;">Penilaian Perilaku</a>
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