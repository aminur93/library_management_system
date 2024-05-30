<?php

namespace App\Http\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function index(Request $request)
    {
        $roles = Role::with('permissions');

        if ($request->has('sortBy') && $request->has('sortDesc')) {

            $sortBy = $request->query('sortBy');

            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';

            $roles = $roles->orderBy($sortBy, $sortDesc);

        } else {

            $roles = $roles->orderBy('name', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = $request->input('itemsPerPage');
        $defaultItemsPerPage = 10;

        if ($searchValue)
        {
            $roles->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
            });

            $itemsPerPage = 10;

            if($request->has('itemsPerPage')) {
                $itemsPerPage = $request->get('itemsPerPage');

                return $roles->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($itemsPerPage)
        {
            return $roles->paginate($itemsPerPage);
        }

        return $roles->paginate($defaultItemsPerPage);
    }

    public function getAllRole()
    {
        $roles = Role::with('permissions')->latest()->get();

        return $roles;
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            // storing roles

            $role = new Role();

            $role->name = $request->name;

            $permission = $request->input('permission');

            $stringWithCommas = $permission[0];

            $stringArray = explode(',', $stringWithCommas);

            $integerArray = array_map('intval', $stringArray);

            $role->syncPermissions($integerArray);

            $role->save();

            DB::commit();

            return $role;

        }catch (\Throwable $th){
            DB::rollBack();

            throw $th;
        }
    }

    public function edit($id)
    {
        $role = Role::where('id', $id)->first();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        $rp_array = [];

        foreach ($rolePermissions as $rp) {
            $rp_array[] = $rp;
        }

        if ($id != $role->id)
        {
            throw new \Exception("Model not found");
        }

        $data = [
                "role" => $role,
                "role_permission" => $rp_array
            ];

        return $data;
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            // update role

            $role = Role::findOrFail($id);

            $role->name = $request->name;

            $permission = $request->input('permission');

            $stringWithCommas = $permission[0];

            $stringArray = explode(',', $stringWithCommas);

            $integerArray = array_map('intval', $stringArray);

            $role->syncPermissions($integerArray);

            $role->save();

            DB::commit();

            return $role;

        }catch (\Throwable $th){
            DB::rollBack();

            throw $th;
        }
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        $role->permissions()->detach();

        $role->delete();

        return $role;
    }
}
