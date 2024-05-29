<?php

namespace App\Http\Services\Admin;


use App\Models\MenuDropdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuDropDownService
{
    public function index(Request $request)
    {
        $menu_dropdown = MenuDropdown::with('menu');

        if ($request->has('sortBy') && $request->has('sortDesc')) {
            $sortBy = $request->query('sortBy');
            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';
            $menu_dropdown = $menu_dropdown->orderBy($sortBy, $sortDesc);
        } else {
            $menu_dropdown = $menu_dropdown->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = 10;

        if ($searchValue)
        {
            $menu_dropdown->where(function ($query) use ($searchValue) {
                $query->where('title', 'like', '%' . $searchValue . '%');
            });


            if($request->has('itemsPerPage')) {

                $itemsPerPage = $request->get('itemsPerPage');

                return $menu_dropdown->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($request->has('itemsPerPage'))
        {
            $itemsPerPage = $request->get('itemsPerPage');
        }

        return $menu_dropdown->paginate($itemsPerPage);
    }

    public function getAllMenuDropDown()
    {
        $menu_dropdown = MenuDropdown::latest()->get();

        return $menu_dropdown;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $menu_dropdown = new MenuDropdown();

            $menu_dropdown->menu_id = $request->menu_id;
            $menu_dropdown->permission_id = $request->permission_id;
            $menu_dropdown->title = $request->title;
            $menu_dropdown->icon = $request->icon;
            $menu_dropdown->route = $request->route;

            $menu_dropdown->save();

            DB::commit();

            return $menu_dropdown;

        }catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function show($id)
    {
        $menu_dropdown = MenuDropdown::with('menu')->findOrFail($id);

        return $menu_dropdown;
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $menu_dropdown = MenuDropdown::findOrFail($id);

            $menu_dropdown->menu_id = $request->menu_id ?? $menu_dropdown->menu_id;
            $menu_dropdown->permission_id = $request->permission_id ?? $menu_dropdown->permission_id;
            $menu_dropdown->title = $request->title ?? $menu_dropdown->title;
            $menu_dropdown->icon = $request->icon ?? $menu_dropdown->icon;
            $menu_dropdown->route = $request->route ?? $menu_dropdown->route;

            $menu_dropdown->save();

            DB::commit();

            return $menu_dropdown;

        }catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function destroy($id)
    {
        $menu_dropdown = MenuDropdown::findOrFail($id);
        $menu_dropdown->delete();

        return $menu_dropdown;
    }
}
