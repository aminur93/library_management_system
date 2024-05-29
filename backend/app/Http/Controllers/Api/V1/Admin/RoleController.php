<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helper\GlobalResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Services\Admin\RoleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        $pagination = $request->get("pagination", true);

        if ($pagination === true)
        {
            $roles = $this->roleService->index($request);

            return GlobalResponse::success($roles, "All roles fetch successfyl", Response::HTTP_OK);
        }

        if ($request->get("pagination") === "false")
        {
            $roles = $this->roleService->getAllRole();

            return GlobalResponse::success($roles, "All roles fetch successfyl", Response::HTTP_OK);
        }
    }

    public function store(RoleRequest $request)
    {
        try {
            $role = $this->roleService->store($request);

            return GlobalResponse::success($role, "store successful", Response::HTTP_CREATED);

        }catch (ValidationException $exception){

            return GlobalResponse::error($exception->errors(), $exception->getMessage(), $exception->status);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit($id)
    {
        try {

            $role = $this->roleService->edit($id);

            return GlobalResponse::success($role, "role fetch successful", Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(RoleRequest $request, $id)
    {
        try {

            $role = $this->roleService->update($request, $id);

            return GlobalResponse::success($role, "update successful", Response::HTTP_OK);

        }catch (ValidationException $exception){

            return GlobalResponse::error($exception->errors(), $exception->getMessage(), $exception->status);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function destroy($id)
    {
        try {

            $role = $this->roleService->destroy($id);

            return GlobalResponse::success($role, "destroy successful", Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }
}
