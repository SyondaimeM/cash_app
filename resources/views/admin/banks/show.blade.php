@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.banks.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('global.banks.fields.name')</th>
                            <td field-key='name'>{{ $bank->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('global.banks.fields.status')</th>
                            <td field-key='status'>{{ $bank->status }}</td>
                        </tr>

                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.banks.index') }}" class="btn btn-default">@lang('global.app_back_to_list')</a>
        </div>
    </div>
@stop
