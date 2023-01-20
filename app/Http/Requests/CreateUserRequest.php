<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required',
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
            $data = $this->validated();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            $user->profile()->create([
                'bio' => $data['bio'],
                'twitter' => $data['twitter'] ?? null,
                'profession_id' => $data['profession_id'] ?? null,
            ]);
            $user->skills()->attach($data['skills'] ?? []);
        });


        //Otra forma de solucionar el problema de que twitter sea
        //null es accediendo directamente con $this->twitter
        //ya que el objeto request lo permite
    }
}
