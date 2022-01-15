<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Engagement;
use DB;

class ReportsController extends Controller
{
    public function fans()
    {

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

        return view('admin.record.top', compact('data_profit', 'data_loss'));
    }

    public function engagements()
    {
        $reports = [
            [
                'reportTitle' => 'Engagements',
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
                    return $entries->sum('engagements');
                }),
            ],
        ];

        return view('admin.reports', compact('reports'));
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