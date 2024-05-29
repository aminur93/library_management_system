<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helper\GlobalResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\MenuDropDownService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class MenuDropDownController extends Controller
{
    private $menuDropDownService;

    public function __construct(MenuDropDownService $menuDropDownService)
    {
        $this->menuDropDownService = $menuDropDownService;
    }

    public function index(Request $request)
    {
        $pagination = $request->get('pagination', true);

        if ($pagination === true) {

            $menu_dropdown = $this->menuDropDownService->index($request);

            return GlobalResponse::success($menu_dropdown, "All menu dropdown fetch successful with pagination", \Illuminate\Http\Response::HTTP_OK);

        }

        if ($request->get('pagination') === "false")
        {
            $menu_dropdown = $this->menuDropDownService->getAllMenuDropDown();

            return GlobalResponse::success($menu_dropdown, "All menu dropdown fetch successful", \Illuminate\Http\Response::HTTP_OK);
        }
    }

    public function store(Request $request)
    {
        try {

            $menu_dropdown = $this->menuDropDownService->store($request);

            return GlobalResponse::success($menu_dropdown, "Store successful", Response::HTTP_CREATED);

        }catch (ValidationException $exception){

            return GlobalResponse::success($exception->errors(), $exception->getMessage(), $exception->status);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $menu_dropdown = $this->menuDropDownService->show($id);

            return GlobalResponse::success($menu_dropdown, "Menu dropdown fetch successful", Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $menu_dropdown = $this->menuDropDownService->update($request, $id);

            return GlobalResponse::success($menu_dropdown, "Update successful", Response::HTTP_OK);

        }catch (ValidationException $exception){

            return GlobalResponse::error($exception->errors(), $exception->getMessage(), $exception->status);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), $exception->status);
        }
    }

    public function destroy($id)
    {
        try {
            $menu_dropdown = $this->menuDropDownService->destroy($id);

            return GlobalResponse::success($menu_dropdown, "Delete successful", Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
