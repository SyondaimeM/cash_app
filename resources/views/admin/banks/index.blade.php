@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.banks.title')</h3>
    @can('role_create')
        <p>
            <a href="{{ route('admin.banks.create') }}" class="btn btn-success">@lang('global.app_add_new')</a>
            {{-- <a href="#" class="btn btn-warning" style="margin-left:5px;" data-toggle="modal"
                data-target="#myModal">@lang('global.app_csvImport')</a> --}}
            @include('csvImport.modal', ['model' => 'Role'])

        </p>
    @endcan



    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table
                class="table table-bordered table-striped {{ count($banks) > 0 ? 'datatable' : '' }} @can('role_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('role_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('global.banks.fields.name')</th>
                        <th>@lang('global.banks.fields.status')</th>
                        <th>&nbsp;</th>

                    </tr>
                </thead>

                <tbody>
                    @if (count($banks) > 0)
                        @foreach ($banks as $role)
                            <tr data-entry-id="{{ $role->id }}">
                                @can('role_delete')
                                    <td></td>
                                @endcan

                                <td field-key='name'>{{ $role->name }}</td>
                                <td field-key='name'>{{ $role->status }}</td>
                                <td>
                                    @can('role_view')
                                        <a href="{{ route('admin.banks.show', [$role->id]) }}"
                                            class="btn btn-xs btn-primary">@lang('global.app_view')</a>
                                    @endcan
                                    @can('role_edit')
                                        <a href="{{ route('admin.banks.edit', [$role->id]) }}"
                                            class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                    @endcan
                                    @can('role_delete')
                                        {!! Form::open([
    'style' => 'display: inline-block;',
    'method' => 'DELETE',
    'onsubmit' => "return confirm('" . trans('global.app_are_you_sure') . "');",
    'route' => ['admin.banks.destroy', $role->id],
]) !!}
                                        {!! Form::submit(trans('global.app_delete'), ['class' => 'btn btn-xs btn-danger']) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        @can('role_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.banks.mass_destroy') }}';
        @endcan
    </script>
@endsection
