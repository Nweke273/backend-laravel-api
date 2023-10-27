<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataSourceRequest extends FormRequest
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
        return [
            "keyword" => ["nullable", "string"],
            "from_date" => ["nullable", "date"],
            "to_date" => ["nullable", "date"],
            "category" => ["nullable", "string"],
            "source" => ["nullable", "string"]
        ];
    }
}
