<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Role;

class CreateUserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required',
            'role' => 'nullable|in:'.implode(',', Role::getList()),
            'bio' => 'required',
            'twitter' => ['nullable','url'],
            'profession_id' => [
                'nullable',
                Rule::exists('professions', 'id')->where('selectable', true),
            ],
            'skills' => ['array',
                Rule::exists('skills', 'id')],

        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio',
            'email.required' => 'El campo email es obligatorio',
            'password.required' => 'El campo contraseña es obligatorio',
            'bio.required' => 'El campo bio es obligatorio',
        ];
    }

    public function createUser()
    {
        DB::transaction(function () {

            $user = User::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'role' => $this->role ?? 'user',
            ]);

            $user->save();

            $user->profile()->create([
                'bio' => $this->bio,
                'twitter' => $this->twitter ?? null,
                'profession_id' => $this->profession_id ?? null,
            ]);

            if ($this->skills != null){
                $user->skills()->attach($this->skills);
            }
        });


        //Otra forma de solucionar el problema de que twitter sea
        //null es accediendo directamente con $this->twitter
        //ya que el objeto request lo permite
    }
}
