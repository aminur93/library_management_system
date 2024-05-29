<?php

namespace App\Http\Services\Admin;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberService
{
    public function index(Request $request)
    {
        $member = new Member();

        if ($request->has('sortBy') && $request->has('sortDesc')) {
            $sortBy = $request->query('sortBy');
            $sortDesc = $request->query('sortDesc') === 'true' ? 'desc' : 'asc';
            $member = $member->orderBy($sortBy, $sortDesc);
        } else {
            $member = $member->orderBy('id', 'desc');
        }

        $searchValue = $request->input('search');
        $itemsPerPage = 10;

        if ($searchValue)
        {
            $member->where(function ($query) use ($searchValue) {
                $query->where('first_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('last_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%')
                    ->orWhere('phone_number', 'like', '%' . $searchValue . '%');
            });


            if($request->has('itemsPerPage')) {

                $itemsPerPage = $request->get('itemsPerPage');

                return $member->paginate($itemsPerPage, ['*'], $request->get('page'));
            }
        }

        if ($request->has('itemsPerPage'))
        {
            $itemsPerPage = $request->get('itemsPerPage');
        }

        return $member->paginate($itemsPerPage);
    }

    public function getAllMember()
    {
        $members = Member::latest()->get();

        return $members;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $member = new Member();

            $member->first_name = $request->first_name;
            $member->last_name = $request->last_name;
            $member->email = $request->email;
            $member->phone_number = $request->phone_number;
            $member->registration_date = $request->registration_date;

            $member->save();

            DB::commit();

            return $member;

        }catch (\Throwable $th){
            DB::rollBack();

            throw $th;
        }
    }

    public function show($id)
    {
        $member = Member::findOrFail($id);

        return $member;
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $member = Member::findOrFail($id);

            $member->first_name = $request->first_name ?? $member->first_name;
            $member->last_name = $request->last_name ?? $member->last_name;
            $member->email = $request->email ?? $member->email;
            $member->phone_number = $request->phone_number ?? $member->phone_number;
            $member->registration_date = $request->registration_date ?? $member->registration_date;

            $member->save();

            DB::commit();

            return $member;

        }catch (\Throwable $th){
            DB::rollBack();

            throw $th;
        }
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return $member;
    }
}
