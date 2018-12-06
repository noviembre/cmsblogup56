<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        #--- if user is NOT the default user or if the user is NOT the current user
        #--- return the below function forbiddenResponse()
        #--- this message only will show up if the current user delete button is not blocked
        return !($this->route('users') == config('cms.default_user_id') ||
            $this->route('users') == auth()->user()->id);
    }

    public function forbiddenResponse()
    {
        return redirect()->back()->with('error-message', 'You cannot delete default user or delete yourself!');
    }


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
