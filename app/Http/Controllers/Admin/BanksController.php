<?php

namespace App\Http\Controllers\Admin;

use App\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\StoreBanksRequest;
use App\Http\Requests\Admin\UpdateBanksRequest;

class BanksController extends Controller
{
    public function index()
    {
        // if (!Gate::allows('role_access')) {
        //     return abort(401);
        // }


        $banks = Bank::all();

        return view('admin.banks.index', compact('banks'));
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!Gate::allows('role_create')) {
        //     return abort(401);
        // }

        // $bank = \App\Permission::get()->pluck('title', 'id');


        return view('admin.banks.create');
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param  \App\Http\Requests\StoreBanksRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBanksRequest $request)
    {
        if (!Gate::allows('role_create')) {
            return abort(401);
        }
        $bank = Bank::create($request->all());
        // $role->permission()->sync(array_filter((array)$request->input('permission')));



        return redirect()->route('admin.banks.index');
    }


    /**
     * Show the form for editing Role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!Gate::allows('role_edit')) {
        //     return abort(401);
        // }

        // $permissions = \App\Permission::get()->pluck('title', 'id');


        $bank = Bank::findOrFail($id);

        return view('admin.banks.edit', compact('bank'));
    }

    /**
     * Update Role in storage.
     *
     * @param  \App\Http\Requests\UpdateBanksRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBanksRequest $request, $id)
    {
        // if (!Gate::allows('role_edit')) {
        //     return abort(401);
        // }
        $bank = Bank::findOrFail($id);

        $bank->update($request->all());
        // $role->permission()->sync(array_filter((array)$request->input('permission')));



        return redirect()->route('admin.banks.index');
    }


    /**
     * Display Role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // if (!Gate::allows('role_view')) {
        //     return abort(401);
        // }

        // $permissions = \App\Permission::get()->pluck('title', 'id');
        // $users = \App\User::whereHas(
        //     'role',
        //     function ($query) use ($id) {
        //         $query->where('id', $id);
        //     }
        // )->get();

        $bank = Bank::findOrFail($id);

        return view('admin.banks.show', compact('bank'));
    }


    /**
     * Remove Role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!Gate::allows('role_delete')) {
        //     return abort(401);
        // }
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return redirect()->route('admin.banks.index');
    }

    /**
     * Delete all selected Role at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        // if (!Gate::allows('role_delete')) {
        //     return abort(401);
        // }
        if ($request->input('ids')) {
            $entries = Bank::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }
}