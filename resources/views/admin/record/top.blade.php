@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ action('Admin\ReportsController@top') }}" class="form-inline">
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
                {{-- {{ dd('hh') }} --}}
                <div class="form-group">
                    <label for="Bank">Select Quantity</label>
                    <select name="quantity" class="form-control">
                        @foreach ($numbers as $k => $number)
                            <option value="{{ $number }}" @if (empty($query_data) || count($query_data) < 2)
                                {{-- {{ dd('test') }} --}}
                            @else
                                {{ $query_data['quantity'] == $number ? 'selected' : '' }}
                        @endif
                        >
                        {{ $number }}</option>
                        @endforeach
                    </select>
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
            <button onclick="location.href='{{ url('admin/reports/top') }}'">Refresh</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-striped table-bordered table-sm">
                {{-- {{ dd($query_data) }} --}}
                <caption>Top @if (empty($query_data))
                        20
                    @else
                        {{ $query_data['quantity'] }}
                    @endif Profitable Players</caption>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_profit as $key => $row)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $row->name_of_sender }}</td>
                            <td>${{ $row->sumtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <table class="table table-striped table-dark">
                <caption>Top @if (empty($query_data))
                        20
                    @else
                        {{ $query_data['quantity'] }}
                    @endif Non-Profitable Players</caption>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_loss as $key => $row)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $row->name_of_sender }}</td>
                            <td>${{ $row->sumtotal }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript')
    @parent

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
    </script>
@stop
