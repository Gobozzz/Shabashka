<div>
    @isset($title)
        <div class="input_title">{{ $title }} {{ isset($requared) && $requared ? "*" : "" }}</div>
    @endisset
    <div class="border-2 rounded-xl @error($name) border-red-500 @enderror">
        <textarea id="{{ $id ?? null }}" placeholder="{{ $placeholder ?? null }}"
            class="text-base p-2 h-80 w-full block mt-0.5 resize-none" type="{{ $type ?? "text" }}" {{ isset($requared) && $requared ? "required" : "" }} name="{{ $name }}">{{ old($name, $default_value ?? "") }}</textarea>
    </div>
    @include('includes.components.error-input', ['name' => $name])
</div>