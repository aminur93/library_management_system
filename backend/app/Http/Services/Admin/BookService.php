<?php

namespace App\Http\Services\Admin;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookService
{
    public function index(Request $request)
    {
        $books = Book::with('author');

        if ($request->has('sortBy') && $request->has('sortDesc')) {
            $sortBy = $request->query('sortBy');
            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';
            $books = $books->orderBy($sortBy, $sortDesc);
        } else {
            $books = $books->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = 10;

        if ($searchValue)
        {
            $books->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
            });


            if($request->has('itemsPerPage')) {

                $itemsPerPage = $request->get('itemsPerPage');

                return $books->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($request->has('itemsPerPage'))
        {
            $itemsPerPage = $request->get('itemsPerPage');
        }

        return $books->paginate($itemsPerPage);
    }

    public function getAllBooks()
    {
        $books = Book::with('author')->latest()->get();

        return $books;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $book = new Book();

            $book->author_id = $request->author_id;
            $book->title = $request->title;
            $book->isbn = $request->isbn;
            $book->published_date = $request->published_date;
            $book->available_copies = $request->available_copies;
            $book->total_copies = $request->total_copies;

            $book->save();

            DB::commit();

            return $book;

        }catch (\Throwable $th){
            DB::rollBack();

            throw $th;
        }
    }

    public function show($id)
    {
        $book = Book::with('author')->where('id', $id)->first();

        return $book;
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $book = Book::findOrFail($id);

            $book->author_id = $request->author_id ?? $book->author_id;
            $book->title = $request->title ?? $book->title;
            $book->isbn = $request->isbn ?? $book->isbn;
            $book->published_date = $request->published_date ?? $book->published_date;
            $book->available_copies = $request->available_copies ?? $book->available_copies;
            $book->total_copies = $request->total_copies ?? $book->total_copies;

            $book->save();

            DB::commit();

            return $book;

        }catch (\Throwable $th){
            DB::rollBack();

            throw $th;
        }
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return $book;
    }
}
