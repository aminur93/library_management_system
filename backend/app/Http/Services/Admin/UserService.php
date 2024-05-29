<?php

namespace App\Http\Services\Admin;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function index(Request $request)
    {
        $users = User::with('roles', 'roles.permissions');

        if ($request->has('sortBy') && $request->has('sortDesc')) {

            $sortBy = $request->query('sortBy');

            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';

            $users = $users->orderBy($sortBy, $sortDesc);

        } else {

            $users = $users->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = $request->input('itemsPerPage');
        $defaultItemsPerPage = 10;

        if ($searchValue)
        {
            $users->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%');
            });

            $itemsPerPage = 10;

            if($request->has('itemsPerPage')) {
                $itemsPerPage = $request->get('itemsPerPage');

                return $users->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($itemsPerPage)
        {
            return $users->paginate($itemsPerPage);
        }

        return $users->paginate($defaultItemsPerPage);
    }

    public function getAllUser()
    {
        $users = User::with('roles', 'permissions')->latest()->get();

        return $users;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            //storing users
            $user = new  User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);

            $role = $request->input('role');

            $user->assignRole($role);

            $user->save();

            DB::commit();

            return $user;

        }catch (\Throwable $throwable){
            DB::rollBack();

            throw $throwable;
        }
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->first();

        $role_id = DB::table('model_has_roles')->select('role_id')->where('model_id', $id)->first();

        $user->role_id = $role_id->role_id;

        if ($id != $user->id)
        {
            throw new \Exception("Model not found");
        }

        return $user;
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            // update user

            $user = User::findOrFail($id);

            $user->name = $request->name ?? $user->name;
            $user->email = $request->email ?? $user->email;
            $user->phone = $request->phone ?? $user->phone;

            // Update password if provided
            if ($request->has('password')) {
                $user->password = bcrypt($request->password);
            }

            if ($request->has('role'))
            {
                $role = $request->input('role');

                $user->assignRole($role);
            }


            $user->save();

            DB::commit();

            return $user;

        }catch (\Throwable $throwable){

            DB::rollBack();

            throw $throwable;
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->roles()->detach();

        $user->delete();

        return $user;
    }
}
