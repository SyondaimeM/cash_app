@extends('layouts.app')

@section('content')

    <div class='row'>
        <div class='col-md-12'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('global.app_csvImport')
                </div>

                <div class="panel-body table-responsive">
                    <form class="form-horizontal" method="POST" action="{{ route('admin.csv_process') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="filename" value="{{ $filename }}" />
                        <input type="hidden" name="hasHeader" value="{{ $hasHeader }}" />
                        <input type="hidden" name="modelName" value="{{ $modelName }}" />
                        <input type="hidden" name="redirect" value="{{ $redirect }}" />
                        {{-- <input type="hidden" name="bank_id" value="1" /> --}}

                        <table class="table">
                            <tr>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="Bank">Select Bank</label>
                                        <select name="bank_id" class="form-control" required>
                                            @foreach ($banks as $k => $bank)
                                                <option value="{{ $bank->id }}" f>
                                                    {{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </tr>
                            @if (isset($headers))
                                <tr>
                                    @foreach ($headers as $field)
                                        <th>{{ $field }}</th>
                                    @endforeach
                                </tr>
                            @endif
                            @if ($lines)
                                @foreach ($lines as $line)
                                    <tr>
                                        @foreach ($line as $field)
                                            <td>{{ $field }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                @foreach ($headers as $key => $header)
                                    <td>
                                        <select name="fields[{{ $key }}]">
                                            <option value=''>Please select</option>
                                            @foreach ($fillables as $k => $fillable)
                                                <option value="{{ $fillables_original[$k] }}" @if (strtolower($header) === strtolower($fillable)) selected @endif>
                                                    {{ $fillables[$k] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endforeach
                            </tr>
                        </table>

                        <button type="submit" class="btn btn-primary">
                            @lang('global.app_import_data')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
