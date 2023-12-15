<?php

namespace App\Http\Requests;

use App\Models\Ruang;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreRuangRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ruang_create');
    }

    public function rules()
    {
        return [
            'code' => [
                'string',
                'required',
                'unique:ruangs',
            ],
            'name' => [
                'string',
                'required',
            ],
            'slug' => [
                'string',
                'nullable',
            ],
            'capacity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'images' => [
                'array',
            ],
        ];
    }
}
