<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModal">{{ __('Login') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::open(array('route' => 'tahunan.store')) }}

                    <div class="form-group">
                        {{ Form::label('periode_mulai', 'Periode Mulai') }}
                        {{ Form::date('periode_mulai', null, array('class' => 'form-control')) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('periode_selesai', 'Periode Selesai') }}
                        {{ Form::date('periode_selesai', null, array('class' => 'form-control')) }}
                    </div>

                    @hasanyrole('Administrator|Super-Admin')
                        <div class="form-group">
                            {{ Form::label('user_id', 'Nama Pegawai') }}<br>
                            {{ Form::select('user_id', $users, null, array('class' => 'form-control')) }}
                        </div>
                    @else
                        <div class="form-group">
                            {{ Form::label('name', 'Nama') }}
                            {{ Form::text('name', Auth::user()->name, array('class' => 'form-control', 'disabled')) }}
                            {{ Form::hidden('user_id', Auth::id()) }}
                        </div>
                    @endhasanyrole
                    

                    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>