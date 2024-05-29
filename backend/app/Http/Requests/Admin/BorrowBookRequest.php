<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BorrowBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if (Request::routeIs('borrowedBook.store'))
        {
            return [
                'book_id' => 'required|exists:books,id',
                'member_id' => 'required|exists:members,id',
                'borrow_date' => 'required|date',
                'return_date' => 'required|date',
                'status' => 'required',
            ];
        }

        if (Request::routeIs('borrowedBook.update'))
        {
            return [
                'book_id' => 'sometimes|required|exists:books,id',
                'member_id' => 'sometimes|required|exists:members,id',
                'borrow_date' => 'sometimes|required|date',
                'return_date' => 'sometimes|required|date',
                'status' => 'sometimes|required',
            ];
        }

        return [];
    }
}
