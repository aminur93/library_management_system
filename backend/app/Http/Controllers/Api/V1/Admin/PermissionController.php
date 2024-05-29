<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helper\GlobalResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Services\Admin\PermissionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
    private $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        $pagination = $request->get('pagination', true);

        if ($pagination === true) {

            $permission = $this->permissionService->index($request);

            return GlobalResponse::success($permission, "All Permissions fetch successful", \Illuminate\Http\Response::HTTP_OK);

        }

        if ($request->get('pagination') === "false")
        {
            $permissions = $this->permissionService->getAllPermissions();

            return GlobalResponse::success($permissions, "All permissions fetch successful", \Illuminate\Http\Response::HTTP_OK);
        }
    }

    public function store(PermissionRequest $request)
    {
        try {

            $permission = $this->permissionService->store($request);

            return GlobalResponse::success($permission, "Store successful", \Illuminate\Http\Response::HTTP_CREATED);

        } catch (ValidationException $exception) {

            return GlobalResponse::error($exception->errors(), $exception->getMessage(), $exception->status);

        } catch (\Exception $exception) {

            return GlobalResponse::error("", $exception->getMessage(), $exception->status);
        }
    }

    public function edit($id)
    {
        try {
            $permission = $this->permissionService->edit($id);

            return GlobalResponse::success($permission, "permission fetch successful", Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), $exception->status);
        }
    }

    public function update(PermissionRequest $request, $id)
    {
        try {
            $permission = $this->permissionService->update($request, $id);

            return GlobalResponse::success($permission, "Update successful", Response::HTTP_OK);

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
            $this->permissionService->destroy($id);

            return GlobalResponse::success("", "Delete successful", Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
