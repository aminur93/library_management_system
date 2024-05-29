<?php

namespace App\Http\Services\Admin;

use App\Http\Requests\Admin\PermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function index(Request $request)
    {
        $permissions = new Permission;

        if ($request->has('sortBy') && $request->has('sortDesc')) {
            $sortBy = $request->query('sortBy');
            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';
            $permissions = $permissions->orderBy($sortBy, $sortDesc);
        } else {
            $permissions = $permissions->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = 10;

        if ($searchValue)
        {
            $permissions->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
            });


            if($request->has('itemsPerPage')) {

                $itemsPerPage = $request->get('itemsPerPage');

                return $permissions->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($request->has('itemsPerPage'))
        {
            $itemsPerPage = $request->get('itemsPerPage');
        }

        return $permissions->paginate($itemsPerPage);
    }

    public function getAllPermissions()
    {
        $permissions = Permission::latest()->get();

        return $permissions;
    }


    /**
     * @throws \Throwable
     */
    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            //storing permission
            $permission = new Permission();

            $permission->name = $request->name;

            $permission->save();

            DB::commit();

            return $permission;

        }catch (\Throwable $th){

            DB::rollBack();

            throw $th;
        }

    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        return $permission;
    }

    /**
     * @throws \Throwable
     */
    public function update(Request $request, $id)
    {

        DB::beginTransaction();

        try {

            $permission = Permission::findOrFail($id);

            $permission->name = $request->name;

            $permission->save();

            DB::commit();

            return $permission;

        }catch (\Throwable $th){

            DB::rollBack();

            throw $th;
        }

    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return $permission;
    }

}
