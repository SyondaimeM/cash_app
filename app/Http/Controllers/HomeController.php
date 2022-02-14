<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Engagement;
use App\Bank;
use DateTime;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Engagement::get();
        $query_data = $request->all();
        if (!empty($query_data)) {
            $bank_id = $query_data['bank_id'];
            $from = $query_data['from'];
            $to = $query_data['to'];
            if ($bank_id == '') {
                $data = Engagement::whereBetween('date', [$from, $to])->get();
            } else {
                $data = Engagement::Bank($bank_id)->whereBetween('date', [$from, $to])->get();
            }
        }
        $banks = Bank::all();
        $reports = [
            [
                'reportTitle' => 'IN',
                'reportLabel' => 'Cash IN',
                'colorName' => '#5cb85c',
                'chartType' => 'line',
                'results' => $data->sortBy('date')->groupBy(function ($entry) {
                    return \Carbon\Carbon::parse($entry->date)->format('Y-m-d');
                })->map(function ($entries, $group) {
                    return $entries->where('status', "PAYMENT DEPOSITED")->sum('net_amount');
                })
            ],
            [
                'reportTitle' => 'OUT',
                'reportLabel' => 'Cash OUT',
                'colorName' => '#d9534f',
                'chartType' => 'line',
                'results' => $data->sortBy('date')->groupBy(function ($entry) {

                    return \Carbon\Carbon::parse($entry->date)->format('Y-m-d');
                })->map(function ($entries, $group) {
                    return $entries->where('status', "PAYMENT SENT")->sum('net_amount');
                })
            ],
            [
                'reportTitle' => 'REFUNDED',
                'reportLabel' => 'Cash Refunded',
                'colorName' => '#f0ad4e',
                'chartType' => 'line',
                'results' => $data->sortBy('date')->groupBy(function ($entry) {

                    return \Carbon\Carbon::parse($entry->date)->format('Y-m-d');
                })->map(function ($entries, $group) {
                    return $entries->where('status', "PAYMENT REFUNDED")->sum('net_amount');
                })
            ],
            [
                'reportTitle' => 'Profit/Loss',
                'reportLabel' => 'Total Transaction',
                'colorName' => '#5bc0de',
                'chartType' => 'line',
                'results' => $data->sortBy('date')->groupBy(function ($entry) {

                    return \Carbon\Carbon::parse($entry->date)->format('Y-m-d');
                })->mapWithKeys(function ($entries, $key) {
                    $in = $entries->where('status', 'PAYMENT DEPOSITED')->sum('net_amount');
                    $refunded = $entries->where('status', 'PAYMENT REFUNDED')->sum('net_amount');
                    $out = $entries->where('status', 'PAYMENT SENT')->sum('net_amount');
                    $data[$key] = $in + $refunded + $out;
                    return $data;
                })
            ],
        ];

        return view('admin.reports', compact('reports', 'banks', 'query_data'));
    }
}