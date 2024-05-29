<?php

namespace App\Http\Services\Admin;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorService
{
    public function index(Request $request)
    {
        $author = new Author();

        if ($request->has('sortBy') && $request->has('sortDesc')) {
            $sortBy = $request->query('sortBy');
            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';
            $author = $author->orderBy($sortBy, $sortDesc);
        } else {
            $author = $author->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = 10;

        if ($searchValue)
        {
            $author->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
            });


            if($request->has('itemsPerPage')) {

                $itemsPerPage = $request->get('itemsPerPage');

                return $author->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($request->has('itemsPerPage'))
        {
            $itemsPerPage = $request->get('itemsPerPage');
        }

        return $author->paginate($itemsPerPage);
    }

    public function getAllAuthor()
    {
        $author = Author::latest()->get();

        return $author;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $author = new Author();

            $author->name = $request->name;
            $author->bio = $request->bio;

            $author->save();

            DB::commit();

            return $author;

        }catch (\Throwable $th){
            DB::rollBack();

            throw $th;
        }
    }

    public function show($id)
    {
        $author = Author::findOrFail($id);

        return $author;
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $author = Author::findOrFail($id);

            $author->name = $request->name ?? $author->name;
            $author->bio = $request->bio ?? $author->bio;

            $author->save();

            DB::commit();

            return $author;

        }catch (\Throwable $th){
            DB::rollBack();

            throw $th;
        }
    }

    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        $author->delete();

        return $author;
    }
}
