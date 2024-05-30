<?php

namespace App\Http\Services\Admin;

use App\Models\Book;
use App\Models\BorrowedBook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BorrowBookService
{
    /**
     * Retrieves a paginated list of borrowed books based on the provided request parameters.
     *
     * @param Request $request The HTTP request object containing the request parameters.
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator The paginated list of borrowed books.
     */
    public function index(Request $request)
    {
        $borrow_book = BorrowedBook::with('book', 'member');

        if ($request->has('sortBy') && $request->has('sortDesc')) {
            $sortBy = $request->query('sortBy');
            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';
            $borrow_book = $borrow_book->orderBy($sortBy, $sortDesc);
        } else {
            $borrow_book = $borrow_book->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = 10;

        if ($searchValue)
        {
            $borrow_book->whereHas('book', function ($query) use ($searchValue) {
                $query->where('title', 'like', '%' . $searchValue . '%');
            })->orWhereHas('member', function ($query) use ($searchValue) {
                $query->where('email', 'like', '%' . $searchValue . '%');
            });

            if($request->has('itemsPerPage')) {

                $itemsPerPage = $request->get('itemsPerPage');

                return $borrow_book->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($request->has('itemsPerPage'))
        {
            $itemsPerPage = $request->get('itemsPerPage');
        }

        return $borrow_book->paginate($itemsPerPage);
    }

    public function getAllBorrowBook()
    {
        $borrow_book = BorrowedBook::with('book', 'member')->get();

        return $borrow_book;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Check if there is any record of the book with 'Returned' status
            $returnedBookExists = BorrowedBook::where('book_id', $request->book_id)
                ->where('status', 'Returned')
                ->exists();

            // Check if the book is currently borrowed or overdue
            $borrowedOrOverdueBookExists = BorrowedBook::where('book_id', $request->book_id)
                ->whereIn('status', ['Borrowed', 'Overdue'])
                ->exists();

            // Fetch the book details
            $book = Book::find($request->book_id);

            if ($borrowedOrOverdueBookExists) {
                throw new \Exception("Book is not available", Response::HTTP_BAD_REQUEST);
            }

            if ($returnedBookExists && $book->available_copies > 0) {
                // Create a new BorrowedBook record
                $borrow_book = new BorrowedBook();

                $borrow_book->book_id = $request->book_id;
                $borrow_book->member_id = $request->member_id;
                $borrow_book->borrow_date = $request->borrow_date;
                $borrow_book->return_date = $request->return_date;
                $borrow_book->status = $request->status;

                $borrow_book->save();

                // Decrement the available copies of the book
                $book->available_copies = $book->available_copies - 1;
                $book->save();

                DB::commit();

                return response()->json($borrow_book, Response::HTTP_CREATED);
            } else {
                throw new \Exception("Book is not available", Response::HTTP_BAD_REQUEST);
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }


    public function show($id)
    {
        $borrow_book = BorrowedBook::with('book', 'member')->findOrFail($id);

        return $borrow_book;
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $borrow_book = BorrowedBook::findOrFail($id);

            $borrow_book->book_id = $request->book_id ?? $borrow_book->book_id;
            $borrow_book->member_id = $request->member_id ?? $borrow_book->member_id;
            $borrow_book->borrow_date = $request->borrow_date ?? $borrow_book->borrow_date;
            $borrow_book->return_date = $request->return_date ?? $borrow_book->return_date;
            $borrow_book->status = $request->status ?? $borrow_book->status;

            $borrow_book->save();

            DB::commit();

            return $borrow_book;

        }catch (\Throwable $th){
            DB::rollBack();

            throw $th;
        }
    }

    public function destroy($id)
    {
        $borrow_book = BorrowedBook::findOrFail($id);
        $borrow_book->delete();

        return $borrow_book;
    }

    public function changeStatus(Request $request, $id)
    {
        $borrow_book = BorrowedBook::findOrFail($id);

        $borrow_book->status = $request->status;
        $borrow_book->save();

        return $borrow_book;
    }
}
