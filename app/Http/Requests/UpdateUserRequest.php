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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' =>
                ['required','email', Rule::unique('users')->ignore($this->user)
                ],
            'password' => '',
            'role' => 'required',
            'bio' => 'required',
            'profession_id' => '',
            'twitter' => '',
            'skills' => '',
            'state' => [
              'required', Rule::in(['active', 'inactive'])
            ],
        ];
    }

    public function updateUser(User $user)
    {
        $user->fill([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'state' => $this->state,
        ]);

        if($this->password != null)
        {
           $user->password = bcrypt( $this->password);
        }else{
            unset($this->password);
        }

        $user->role = $this->role;
        $user->save();

        $user->profile->update([
            'twitter' => $this->twitter,
            'bio' => $this->bio,
            'profession_id' => $this->profession_id,
        ]);

        $user->skills()->sync($this->skills ?: []);
    }
}
