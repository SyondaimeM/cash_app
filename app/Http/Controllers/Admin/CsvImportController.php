<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bank;
use SpreadsheetReader;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;


class CsvImportController extends Controller
{

    public function parse(Request $request)
    {

        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines = [];
        $lines[] = $reader->next();
        $lines[] = $reader->next();
        $lines[] = $reader->next();


        $filename = str_random(10) . '.csv';
        $file->storeAs('csv_import', $filename);

        $modelName = $request->input('model', false);
        $fullModelName = "App\\" . $modelName;

        $model = new $fullModelName();
        $fillables = $model->getFillable();

        // $fillables = $fillables->map(function ($data) {
        //     dd($data);
        //     return str_replace(' ', '_', $data);
        // });
        $fillables_original = $fillables;
        foreach ($fillables as $key => $value) {
            $data[$key] = str_replace('_', ' ', $value);
            if ($value == 'name_of_sender') {
                $data[$key] = 'Name of sender/receiver';
            }
        }


        // dd($data);
        $fillables = $data;
        $redirect = url()->previous();

        // dd($fillables, $headers, $filename);

        $banks = Bank::all();
        return view('csvImport.parse_import', compact('banks', 'headers', 'filename', 'fillables', 'fillables_original', 'hasHeader', 'modelName', 'lines', 'redirect'));
    }

    public function process(Request $request)
    {

        $filename = $request->input('filename', false);
        $path = storage_path('app/csv_import/' . $filename);

        $hasHeader = $request->input('hasHeader', false);

        $fields = $request->input('fields', false);
        $bank_id = $request->input('bank_id');
        // dd($bank_id);
        $fields = array_flip(array_filter($fields));

        $modelName = $request->input('modelName', false);
        $model = "App\\" . $modelName;

        $reader = new SpreadsheetReader($path);
        $insert = [];

        foreach ($reader as $key => $row) {
            if ($hasHeader && $key == 0) {
                continue;
            }

            $tmp = [];
            foreach ($fields as $header => $k) {
                $tmp[$header] = $row[$k];
            }
            $insert[] = $tmp;
        }
        // dd($insert);
        $for_insert = array_chunk($insert, 100);
        foreach ($for_insert as $insert_item) {

            foreach ($insert_item as $key => $item) {
                $item['date'] = Carbon::parse($item['date'])->tz('CST');

                $item['fee'] = preg_replace('/[^-?0-9.]|\.(?=.*\.)/', '', $item['fee']);
                $item['amount'] = preg_replace('/[^-?0-9.]|\.(?=.*\.)/', '', $item['amount']);
                $item['net_amount'] = preg_replace('/[^-?0-9.]|\.(?=.*\.)/', '', $item['net_amount']);
                $item['asset_price'] = ($item['asset_price'] == "" ? 0 : (preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $item['asset_price'])));
                $item['asset_amount'] = ($item['asset_amount'] == "" ? 0 : (preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $item['asset_amount'])));
                $data[$key] = $item;
                $data[$key]['bank_id'] = $bank_id;
            }
            // dd($data);
            $model::insert($data);
        }

        $rows = count($insert);
        $table = str_plural($modelName);

        File::delete($path);

        $redirect = $request->input('redirect', false);
        return redirect()->to($redirect)->with('message', trans('global.app_imported_rows_to_table', ['rows' => $rows, 'table' => $table]));
    }
}