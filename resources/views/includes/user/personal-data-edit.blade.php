@php
    $user = auth()->user();
@endphp
<form enctype="multipart/form-data" class="form !mx-0 !mb-12" action="{{ route('user.update') }}" method="post">
    @csrf
    @method('PUT')
    @session('success-update-personal-data')
        <div class="success_message">{{ $value }}</div>
    @endsession
    @session('error-update-personal-data')
        <div class="error_message">{{ $value }}</div>
    @endsession
    <div class="flex flex-col gap-y-5">
        <div class="w-32 aspect-square rounded-full overflow-hidden">
            @if ($user->image)
                <img class="w-full h-full object-cover object-center" src="{{ asset("storage/{$user->image}") }}" alt="">
            @else
                <img class="w-full h-full object-cover object-center" src="{{ asset('images/user.png') }}" alt="">
            @endif
        </div>
        <input class="bg-black text-white py-2 px-2 rounded-lg max-w-max cursor-pointer text-sm" type="file"
            name="image" />
        @include('includes.components.error-input', ['name' => 'image'])
    </div>
    @include('includes.components.input', [
        'name' => 'name',
        'placeholder' => __('app.name_user'),
        'default_value' => $user->name,
    ]) @include('includes.components.input', [
    'name' => 'email',
    'placeholder' => __('app.email_user'),
    'default_value' => $user->email,
])  
    @include('includes.components.input', [
        'name' => 'phone',
        'placeholder' => __('app.phone_user'),
        'default_value' => $user->phone,
    ])
    <div class="text-lg ">{{ __('app.city') }}</div>
    <select name="city_id" class="select-default">
        <option value="">-</option>
        @foreach ($cities as $city)
            <option @selected($city->id == old('city_id', $user->city_id)) value="{{ $city->getKey() }}">{{ $city->name }}</option>
        @endforeach
    </select>
    @include('includes.components.error-input', ['name' => 'city_id'])
    <button class="button-default">{{ __('app.apply') }}</button>
</form>