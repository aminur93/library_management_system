<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BookRequest extends FormRequest
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
        if (Request::routeIs('book.store'))
        {
            return [
                'author_id' => 'required|integer',
                'title' => 'required',
                'isbn' => 'required',
                'published_date' => 'required|date',
                'available_copies' => 'required|integer',
                'total_copies' => 'required|integer'
            ];
        }

        if (Request::routeIs('book.update'))
        {
            return [
                'author_id' => 'required|integer',
                'title' => 'sometimes|required',
                'isbn' => 'sometimes|required',
                'published_date' => 'sometimes|required|date',
                'available_copies' => 'sometimes|required|integer',
                'total_copies' => 'sometimes|required|integer'
            ];
        }

        return [];

    }
}
