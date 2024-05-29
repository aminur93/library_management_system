<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Helper\GlobalResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BorrowBookRequest;
use App\Http\Services\Admin\BorrowBookService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BorrowedBookController extends Controller
{
    private $borrowBookService;

    public function __construct(BorrowBookService $borrowBookService)
    {
        $this->borrowBookService = $borrowBookService;
    }

    public function index(Request $request)
    {
        $pagination = $request->get('pagination', true);

        if ($pagination === true) {

            $borrow_book = $this->borrowBookService->index($request);

            return GlobalResponse::success($borrow_book, "All borrow book fetch successful with pagination", \Illuminate\Http\Response::HTTP_OK);

        }

        if ($request->get('pagination') === "false")
        {
            $borrow_book = $this->borrowBookService->getAllBorrowBook();

            return GlobalResponse::success($borrow_book, "All borrow book fetch successful", \Illuminate\Http\Response::HTTP_OK);
        }
    }

    public function store(BorrowBookRequest $request)
    {
        try {

            $borrow_book = $this->borrowBookService->store($request);

            return GlobalResponse::success($borrow_book, "Store successful", Response::HTTP_CREATED);

        }catch (ValidationException $exception){

            return GlobalResponse::success($exception->errors(), $exception->getMessage(), $exception->status);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), $exception->getCode());
        }
    }

    public function show($id)
    {
        try {
            $borrow_book = $this->borrowBookService->show($id);

            return GlobalResponse::success($borrow_book, "Borrow book fetch successful", Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(BorrowBookRequest $request, $id)
    {
        try {
            $borrow_book = $this->borrowBookService->update($request, $id);

            return GlobalResponse::success($borrow_book, "Update successful", Response::HTTP_OK);

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
            $borrow_book = $this->borrowBookService->destroy($id);

            return GlobalResponse::success($borrow_book, "Delete successful", Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $borrow_book = $this->borrowBookService->changeStatus($request, $id);

            return GlobalResponse::success($borrow_book, "Change status successful", Response::HTTP_OK);

        }catch (ModelNotFoundException $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_NOT_FOUND);

        }catch (\Exception $exception){

            return GlobalResponse::error("", $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
