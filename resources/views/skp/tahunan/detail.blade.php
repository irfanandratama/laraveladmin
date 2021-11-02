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
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                          @foreach ($users as $id => $userd)
                              @if ($id === $user->atasan_1_id)
                                {{ Form::label('', 'Atasan Langsung (Pejabat Penilai)') }}
                                {{ Form::text('nama', $userd, array('class' => 'form-control', 'disabled')) }}
                              @endif
                          @endforeach
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                          @foreach ($users as $id => $userd)
                              @if ($id === $user->atasan_2_id)
                                {{ Form::label('', 'Atasan Pejabat Penilai') }}
                                {{ Form::text('nama', $userd, array('class' => 'form-control', 'disabled')) }}
                              @endif
                          @endforeach
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>