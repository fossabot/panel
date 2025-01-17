<?php

namespace Convoy\Http\Requests\Client\Servers;

use Illuminate\Foundation\Http\FormRequest;

class SendPowerCommandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'action' => 'required|in:start,shutdown,kill,reboot',
        ];
    }
}
