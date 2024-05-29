<?php

namespace App\Http\Services\Admin;

use App\Models\Menu;
use App\Models\MenuDropdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuService
{
    public function index(Request $request)
    {
        $menu = Menu::with('menuDropDown');

        if ($request->has('sortBy') && $request->has('sortDesc')) {
            $sortBy = $request->query('sortBy');
            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';
            $menu = $menu->orderBy($sortBy, $sortDesc);
        } else {
            $menu = $menu->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = 10;

        if ($searchValue)
        {
            $menu->where(function ($query) use ($searchValue) {
                $query->where('title', 'like', '%' . $searchValue . '%');
            });


            if($request->has('itemsPerPage')) {

                $itemsPerPage = $request->get('itemsPerPage');

                return $menu->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($request->has('itemsPerPage'))
        {
            $itemsPerPage = $request->get('itemsPerPage');
        }

        return $menu->paginate($itemsPerPage);
    }

    public function getAllMenus()
    {
        $menus = Menu::with('menuDropDown')->get();
        return $menus;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $menu = new Menu();

            $menu->permission_id = $request->permission_id;
            $menu->title = $request->title;
            $menu->icon = $request->icon;
            $menu->route = $request->route;
            $menu->header_menu = $request->header_menu;
            $menu->sidebar_menu = $request->sidebar_menu;
            $menu->dropdown = $request->dropdown;

            $menu->save();

            DB::commit();

            return $menu;

        }catch (\Throwable $th){
            DB::rollBack();
            throw $th;
        }
    }

    public function show($id)
    {
        $menu = Menu::with('menuDropDown')->where('id', $id)->first();

        return $menu;
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $menu = Menu::findOrFail($id);

            $menu->permission_id = $request->permission_id;
            $menu->title = $request->title;
            $menu->icon = $request->icon;
            $menu->route = $request->route;
            $menu->header_menu = $request->header_menu;
            $menu->sidebar_menu = $request->sidebar_menu;
            $menu->dropdown = $request->dropdown;

            $menu->save();

            DB::commit();

            return $menu;

        }catch (\Throwable $th){
            DB::rollBack();
            throw $th;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {

            $menu = Menu::findOrFail($id);

            if ($menu->dropdown)
            {
                $menuDropDown = MenuDropdown::where('menu_id', $menu->id)->delete();
            }

            $menu->delete();

            DB::commit();

            return $menu;

        }catch (\Throwable $th){
            DB::rollBack();
            throw $th;
        }
    }
}
