<?php

namespace App\Http\Requests;

use App\Role;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required',
            'email' =>
                ['required','email', Rule::unique('users')->ignore($this->user)
                ],
            'password' => '',
            'role' => 'required',
            'bio' => 'required',
            'profession_id' => '',
            'twitter' => '',
            'skills' => '',
        ];
    }

    public function updateUser(User $user)
    {

        $data = $this->validated();
        if($data['password'] != null)
        {
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }

        $user->fill($data);
        $user->role = $data['role'];
        $user->save();

        $user->profile->update($data);

        $user->skills()->sync($data['skills'] ?? []);
    }
}
