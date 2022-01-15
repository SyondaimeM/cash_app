@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.banks.title')</h3>

    {!! Form::model($bank, ['method' => 'PUT', 'route' => ['admin.banks.update', $bank->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('name', trans('global.banks.fields.name') . '*', ['class' => 'control-label']) !!}
                    {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if ($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {!! Form::submit(trans('global.app_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

@section('javascript')
    @parent

    <script>
        $("#selectbtn-permission").click(function() {
            $("#selectall-permission > option").prop("selected", "selected");
            $("#selectall-permission").trigger("change");
        });
        $("#deselectbtn-permission").click(function() {
            $("#selectall-permission > option").prop("selected", "");
            $("#selectall-permission").trigger("change");
        });
    </script>
@stop
