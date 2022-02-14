<?php

namespace App\Http\Controllers\Admin;

use App\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Engagement;
use DB;

class ReportsController extends Controller
{
    public function top(Request $request)
    {

        $numbers = [20, 10, 5];
        $banks = Bank::all();
        $query_data = $request->all();
        if (!empty($query_data)) {
            $bank_id = $query_data['bank_id'];
            $from = $query_data['from'];
            $to = $query_data['to'];
            $quantity = $query_data['quantity'];

            //Analysis of profit 
            $data_profit = Engagement::select((DB::raw("SUM( CASE
                                                    WHEN asset_type = 'BTC' THEN name_of_sender='Bit Coin'
                                                    WHEN status='TRANSFER REVERSED' THEN net_amount*0
                                                    WHEN status='PAYMENT FAILED' THEN net_amount*0
                                                    ELSE net_amount
                                                END
                                                ) as sumtotal,
                                                name_of_sender")))
                ->Bank($bank_id)
                ->whereBetween('date', [$from, $to])
                ->orderBy("sumtotal", "DESC")
                ->groupBy("name_of_sender")
                ->get()
                ->take($quantity);

            //Analysis of Loss 
            $data_loss = Engagement::select((DB::raw("SUM( CASE
                                                    WHEN asset_type = 'BTC' THEN name_of_sender='Bit Coin'
                                                    WHEN status='TRANSFER REVERSED' THEN net_amount*0
                                                    WHEN status='PAYMENT FAILED' THEN net_amount*0
                                                    ELSE net_amount
                                                END
                                                ) as sumtotal,
                                                name_of_sender")))
                ->Bank($bank_id)
                ->whereBetween('date', [$from, $to])
                ->orderBy("sumtotal", "asc")
                ->groupBy("name_of_sender")
                ->get()
                ->take($quantity);

            //Adding Bit Coin to nameless transaction of bit coin
            $data_loss = $data_loss->map(function ($query) {
                $query->name_of_sender = ($query->name_of_sender == '') ? 'Bit Coin' : $query->name_of_sender;
                return $query;
            });

            return view('admin.record.top', compact('data_profit', 'data_loss', 'banks', 'query_data', 'numbers'));
        }

        //Analysis of profit 
        $data_profit = Engagement::select((DB::raw("SUM( CASE
                                                        WHEN asset_type = 'BTC' THEN name_of_sender='Bit Coin'
                                                        WHEN status='TRANSFER REVERSED' THEN net_amount*0
                                                        WHEN status='PAYMENT FAILED' THEN net_amount*0
                                                        ELSE net_amount
                                                    END
                                                    ) as sumtotal,
                                                    name_of_sender")))
            ->orderBy("sumtotal", "DESC")
            ->groupBy("name_of_sender")
            ->get()
            ->take(20);

        //Analysis of Loss 
        $data_loss = Engagement::select((DB::raw("SUM( CASE
                                                    WHEN asset_type = 'BTC' THEN name_of_sender='Bit Coin'
                                                    WHEN status='TRANSFER REVERSED' THEN net_amount*0
                                                    WHEN status='PAYMENT FAILED' THEN net_amount*0
                                                    ELSE net_amount
                                                END
                                                ) as sumtotal,
                                                name_of_sender")))
            ->orderBy("sumtotal", "asc")
            ->groupBy("name_of_sender")
            ->get()
            ->take(20);

        //Adding Bit Coin to nameless transaction of bit coin
        $data_loss = $data_loss->map(function ($query) {
            $query->name_of_sender = ($query->name_of_sender == '') ? 'Bit Coin' : $query->name_of_sender;
            return $query;
        });

        return view('admin.record.top', compact('data_profit', 'data_loss', 'banks', 'query_data', 'numbers'));
    }

    public function bitcoin(Request $request)
    {

        $banks = Bank::all();
        $data_buy = Engagement::select('*')
            ->where('asset_type', 'BTC')
            ->where('transaction_type', 'Bitcoin Buy');
        // ->get();
        $data_sale = Engagement::select('*')
            ->where('asset_type', 'BTC')
            ->where('transaction_type', 'Bitcoin Sale');
        // ->get();
        $data_withdrawal = Engagement::select('*')
            ->where('asset_type', 'BTC')
            ->where('transaction_type', 'Bitcoin Withdrawal');
        // ->get();
        $query_data = $request->all();
        if (!empty($query_data)) {
            $bank_id = $query_data['bank_id'];
            $from = $query_data['from'];
            $to = $query_data['to'];

            $data_buy = $data_buy->Bank($bank_id)->whereBetween('date', [$from, $to]);
            $data_sale = $data_sale->Bank($bank_id)->whereBetween('date', [$from, $to]);
            $data_withdrawal = $data_withdrawal->Bank($bank_id)->whereBetween('date', [$from, $to]);
            // $quantity = $query_data['quantity'];
        }
        $data_buy = $data_buy->get();
        $data_sale = $data_sale->get();
        $data_withdrawal = $data_withdrawal->get();

        return view('admin.record.bitcoin', compact('data_buy', 'data_sale', 'data_withdrawal', 'banks', 'query_data'));
    }

    public function reactions()
    {
        $reports = [
            [
                'reportTitle' => 'Reactions',
                'reportLabel' => 'SUM',
                'chartType'   => 'line',
                'results'     => Engagement::get()->sortBy('stats_date')->groupBy(function ($entry) {
                    if ($entry->stats_date instanceof \Carbon\Carbon) {
                        return \Carbon\Carbon::parse($entry->stats_date)->format('Y-m-d');
                    }

                    return \Carbon\Carbon::createFromFormat(
                        config('app.date_format'),
                        $entry->stats_date
                    )->format('Y-m-d');
                })->map(function ($entries, $group) {
                    return $entries->sum('reactions');
                }),
            ],
        ];

        return view('admin.reports', compact('reports'));
    }

    public function comments()
    {
        $reports = [
            [
                'reportTitle' => 'Comments',
                'reportLabel' => 'SUM',
                'chartType'   => 'line',
                'results'     => Engagement::get()->sortBy('date')->groupBy(function ($entry) {
                    if ($entry->stats_date instanceof \Carbon\Carbon) {
                        return \Carbon\Carbon::parse($entry->stats_date)->format('Y-m-d');
                    }

                    return \Carbon\Carbon::createFromFormat(
                        config('app.date_format'),
                        $entry->stats_date
                    )->format('Y-m-d');
                })->map(function ($entries, $group) {
                    return $entries->sum('comments');
                }),
            ],
        ];

        return view('admin.reports', compact('reports'));
    }

    public function shares()
    {
        $reports = [
            [
                'reportTitle' => 'Shares',
                'reportLabel' => 'SUM',
                'chartType'   => 'line',
                'results'     => Engagement::get()->sortBy('stats_date')->groupBy(function ($entry) {
                    if ($entry->stats_date instanceof \Carbon\Carbon) {
                        return \Carbon\Carbon::parse($entry->stats_date)->format('Y-m-d');
                    }

                    return \Carbon\Carbon::createFromFormat(
                        config('app.date_format'),
                        $entry->stats_date
                    )->format('Y-m-d');
                })->map(function ($entries, $group) {
                    return $entries->sum('shares');
                }),
            ],
        ];

        return view('admin.reports', compact('reports'));
    }
}