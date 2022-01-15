@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <table class="table table-striped table-bordered table-sm">
                <caption>Top 20 Profitable Players</caption>
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
                <caption>Top 20 Non-Profitable Players</caption>
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
