<?php

namespace App\Http\Requests;

use App\Exceptions\ThrottleException;
use App\Reply;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize ()
    {
        return Gate::allows('create', new Reply);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            'body' => 'required|spamfree',
        ];
    }

    protected function failedAuthorization ()
    {
        throw new ThrottleException(
            'You Posting Too Frequently. Please Take A Break. :) '
        );
    }

}
