
{{csrf_field()}}
<div class="mb-3 m-lg-3">
    <label for="name" class="form-label">Primer Nombre:</label>
    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Introduce tu nombre" value="{{old('first_name', isset($user) ? $user->first_name : '')}}">
</div>

<div class="mb-3 m-lg-3">
    <label for="name" class="form-label">Apellido:</label>
    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Introduce tu apellido" value="{{old('last_name', isset($user) ? $user->last_name : '')}}">
</div>

<div class="mb-3 m-lg-3">
    <label for="email" class="form-label">Email:</label>
    <input type="email" class="form-control" name="email" id="email" placeholder="Introduce tu Email" value="{{old('email', isset($user) ? $user->email : '')}}">
</div>

<div class="mb-3 m-lg-3">
    <label for="password" class="form-label">Contraseña:</label>
    <input type="password" class="form-control" name="password" id="password" placeholder="Mayor a 6 caracteres">
</div>

<div class="mb-3 m-lg-3">
    <label for="bio" class="form-label">Bio:</label>
    <textarea type="text" class="form-control" name="bio" id="bio" placeholder="Escribe algo sobre ti">{{old('bio', isset($user) ? $user->profile->bio : '')}}</textarea>
</div>

<div class="form-group">
    <label for="profession_id">Profesión</label>
    <select name="profession_id" id="profession_id" class="form-control">
        <option value="">Selecciona una profesión</option>
        @foreach($professions as $profession)
            <option value="{{$profession->id}}"{{old('profession_id', isset($user) ?$user->profile->profession_id : '') == $profession->id ? ' selected' : ''}}>
                {{$profession->title}}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3 m-lg-3">
    <label for="twitter" class="form-label">Twitter:</label>
    <input type="url" class="form-control" name="twitter" id="twitter" placeholder="https://twitter.com/"
           value="{{old('twitter', isset($user) ? $user->profile->twitter : '')}}">
</div>

<h5>Habilidades</h5>
@foreach($skills as $skill)
    <div class="form-check form-check-inline">
        <input name="skills[{{$skill->id}}]"
               class="form-check-input"
               type="checkbox"
               id="skill_{{$skill->id}}"
               value="{{$skill->id}}"
                {{ $errors->any() ? old("skills.{$skill->id}") :$user->skills->contains($skill) ? 'checked' : ''}}>
        <label class="form-check-label" for="skill_{{$skill->id}}">{{$skill->name}}</label>
    </div>
@endforeach

<h5 class="mt-3">Rol</h5>
@foreach($roles as $role => $name)
    <div class="form-check form-check-inline">
        <input class="form-check-input"
               type="radio"
               name="role"
               id="rol_{{$role}}"
               value="{{$role}}"
                {{old('role', $user->role) == $role ? 'checked' : ''}}>
        <label class="form-check-label" for="rol_{{$role}}">{{$name}}</label>
    </div>
@endforeach


<h5 class="mt-3">Estado</h5>
@foreach(trans('users.states') as $state => $text)
    <div class="form-check form-check-inline">
        <input class="form-check-input"
               type="radio"
               name="state"
               id="state_{{$state}}"
               value="{{$state}}"
                {{old('state', $user->$state) == $state ? 'checked' : ''}}>
        <label class="form-check-label" for="state_{{$state}}">{{$text}}</label>
    </div>
@endforeach

