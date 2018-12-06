<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #-- if the id that will be remove is equal to the protected Uncategorized id, this request will be reject
        return !($this->route('category') == config('cms.default_category_id'));
    }

    #--- after the protected uncategorized is deleted(of course that wont happen because is protected) it will show a message
    # se puso en handler



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
