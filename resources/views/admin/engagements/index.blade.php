@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.engagements.title')</h3>
    @can('engagement_create')
        <p>
            {{-- <a href="{{ route('admin.engagements.create') }}" class="btn btn-success">@lang('global.app_add_new')</a> --}}
            <a href="#" class="btn btn-warning" style="margin-left:5px;" data-toggle="modal"
                data-target="#myModal">@lang('global.app_csvImport')</a>
            @include('csvImport.modal', ['model' => 'Engagement'])

        </p>
    @endcan

    {{-- <p>
    <ul class="list-inline">
        <li><a href="{{ route('admin.engagements.index') }}"
                style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">@lang('global.app_all')</a></li> |
        <li><a href="{{ route('admin.engagements.index') }}?show_deleted=1"
                style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">@lang('global.app_trash')</a></li>
    </ul>
    </p> --}}


    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('global.app_list')
        </div>

        <div class="panel-body table-responsive">
            <table
                class="table table-bordered table-striped {{ count($engagements) > 0 ? 'datatable' : '' }} @can('engagement_delete') @if (request('show_deleted') != 1) dt-select @endif @endcan">
                <thead>
                    <tr>
                        @can('engagement_delete')
                            @if (request('show_deleted') != 1)<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>@endif
                        @endcan

                        <th>@lang('global.engagements.fields.date')</th>
                        <th>@lang('global.engagements.fields.transaction_id')</th>
                        <th>@lang('global.engagements.fields.amount')</th>
                        <th>@lang('global.engagements.fields.status')</th>
                        <th>@lang('global.engagements.fields.name_of_sender')</th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($engagements) > 0)
                        @foreach ($engagements as $engagement)
                            <tr data-entry-id="{{ $engagement->id }}">
                                @can('engagement_delete')
                                    @if (request('show_deleted') != 1)<td></td>@endif
                                @endcan

                                <td field-key='stats_date'>{{ $engagement->date }}</td>
                                <td field-key='transaction_id'>{{ $engagement->transaction_id }}</td>
                                <td field-key='fans'>{{ $engagement->amount }}</td>
                                <td field-key='engagements'>{{ $engagement->status }}</td>
                                <td field-key='reactions'>{{ $engagement->name_of_sender }}</td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        @can('engagement_delete')
            @if (request('show_deleted') != 1) window.route_mass_crud_entries_destroy = '{{ route('admin.engagements.mass_destroy') }}'; @endif
        @endcan
    </script>
@endsection
