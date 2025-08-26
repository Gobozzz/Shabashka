<div>
    @isset($title)
        <div class="input_title">{{ $title }} {{ isset($requared) && $requared ? "*" : "" }}</div>
    @endisset
    <div class="border-b-2 @error($name) border-b-red-500 @enderror">
        <input id="{{ $id ?? null }}" placeholder="{{ $placeholder ?? null }}"
            class="text-base pr-2 h-11 w-full block mt-0.5" 
            type="{{ $type ?? "text" }}" name="{{ $name }}"
            {{ isset($requared) && $requared ? "required" : "" }}
            value="{{ old($name, $default_value ?? "") }}" 
            />
    </div>
    @include('includes.components.error-input', ['name' => $name])
</div>