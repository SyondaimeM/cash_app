@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('global.engagements.title')</h3>
    <p>
    <div class="row">
        <div class="col-md-12">
            <form action="{{ action('Admin\EngagementsController@index') }}" class="form-inline">
                <div class="form-group">
                    <label for="Bank">Select Bank</label>
                    <select name="bank_id" class="form-control">
                        <option value=''>All Bank</option>
                        @foreach ($banks as $k => $bank)
                            <option value="{{ $bank->id }}" @if (empty($query_data) || count($query_data) < 2)
                                {{-- {{ dd('test') }} --}}
                            @else
                                {{ $query_data['bank_id'] == $bank->id ? 'selected' : '' }}
                        @endif
                        >
                        {{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" name="from" id="from_date"
                        value="{{ array_key_exists('from', $query_data) ? $query_data['from'] : Carbon\Carbon::now()->format('mm-dd-YYYY') }}">
                </div>
                <div class="form-group">
                    <label for="start_date">End Date</label>
                    <input type="date" class="form-control" name="to" id="to_date"
                        value="{{ array_key_exists('to', $query_data) ? $query_data['to'] : Carbon\Carbon::now()->format('mm-dd-YYYY') }}">
                </div>
                <div class="form-group">
                    <button type="submit">Search</button>
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <button onclick="daysfunction(30)">30 days</button>
            <button onclick="daysfunction(7)">7 days</button>
            <button onclick="daysfunction(1)">1 day</button>
            <button onclick="location.href='{{ url('/admin/engagements') }}'">Refresh</button>
        </div>
    </div>
    </p>
    <hr>
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
            <table id="engagement_table"
                class="table table-bordered table-striped {{ count($engagements) > 0 ? '' : '' }} @can('engagement_delete') @if (request('show_deleted') != 1) dt-select @endif @endcan">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('global.engagements.fields.name_of_sender')</th>
                        <th>@lang('global.engagements.fields.transaction_id')</th>
                        <th>@lang('global.engagements.fields.date')</th>
                        <th>@lang('global.engagements.fields.amount')</th>
                        <th>@lang('global.engagements.fields.status')</th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($engagements) > 0)
                        @foreach ($engagements as $engagement)
                            <tr data-entry-id="{{ $engagement->id }}">
                                @can('engagement_delete')
                                    @if (request('show_deleted') != 1)<td></td>@endif
                                @endcan

                                <td field-key='reactions'>{{ $engagement->name_of_sender }}</td>
                                <td field-key='transaction_id'>{{ $engagement->transaction_id }}</td>
                                <td field-key='stats_date'>{{ $engagement->date }}</td>
                                <td field-key='fans'>${{ $engagement->net_amount }}</td>
                                <td field-key='engagements'>{{ $engagement->status }}</td>

                            </tr>
                        @endforeach

                    @else
                        <tr>
                            <td colspan="11">@lang('global.app_no_entries_in_table')</td>
                        </tr>
                    @endif
                    {{ $engagements->appends($_GET)->links() }}
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

    {{-- @section('javascript')
@parent --}}

    <script>
        function daysfunction(x) {
            var today = new Date();
            var today_date = formatDate(today);

            var priorDate = new Date(new Date().setDate(today.getDate() - x));
            var x_date = formatDate(priorDate);

            $('#from_date').val(x_date);
            $('#to_date').val(today_date);
        }

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }

        $(document).ready(function() {
            $('#engagement_table').DataTable({
                "paging": false,
                "ordering": false,
                "info": false
            });
        });
    </script>
    {{-- @stop --}}

@endsection
