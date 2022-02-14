@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ action('HomeController@index') }}" class="form-inline">
                <div class="form-group">
                    <label for="Bank">Select Bank</label>
                    <select name="bank_id" class="form-control">
                        <option value=''>All Bank</option>
                        @foreach ($banks as $k => $bank)
                            <option value="{{ $bank->id }}"
                                {{ (empty($query_data) ? '' : $query_data['bank_id'] == $bank->id) ? 'selected' : '' }}>
                                {{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" name="from" id="from_date"
                        value="{{ empty($query_data) ? Carbon\Carbon::now()->format('mm-dd-YYYY') : $query_data['from'] }}">
                </div>
                <div class="form-group">
                    <label for="start_date">End Date</label>
                    <input type="date" class="form-control" name="to" id="to_date"
                        value="{{ empty($query_data) ? Carbon\Carbon::now()->format('mm-dd-YYYY') : $query_data['to'] }}">
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
            <button onclick="location.href='{{ url('/') }}'">Refresh</button>
        </div>
    </div>
    <hr>
    <div class="row">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
        @foreach ($reports as $report)
            <div class="col-md-6">
                <h2 style="margin-top: 0;">{{ $report['reportTitle'] }}</h2>

                <canvas id="myChart{{ $report['reportTitle'] }}"></canvas>

                <script>
                    var ctx = document.getElementById("myChart{{ $report['reportTitle'] }}");
                    var myChart = new Chart(ctx, {
                        type: '{{ $report['chartType'] }}',
                        data: {
                            labels: [
                                @foreach ($report['results'] as $group => $result)
                                    "{{ $group }}",
                                @endforeach
                            ],

                            datasets: [{
                                label: '{{ $report['reportLabel'] }}',
                                data: [
                                    @foreach ($report['results'] as $group => $result)
                                        {!! $result !!},
                                    @endforeach
                                ],
                                backgroundColor: '{{ $report['colorName'] }}',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                            }
                        }
                    });
                </script>
            </div>
        @endforeach
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
