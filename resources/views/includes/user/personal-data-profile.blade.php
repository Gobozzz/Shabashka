@php
    $user = auth()->user();
@endphp
<section>
    <h2 class="section_title">{{ __('app.profile_personal_data') }}</h2>
    <div class="flex gap-5 max-sm:flex-col">
        <div class="flex flex-col gap-y-2 flex-1/2 bg-gray p-4 rounded-sm text-white">
            <div class="text-lg gap-x-1">{{ __('app.name_user') }}: <span class="text-base">{{ $user->name }}</span>
            </div>
            <div class="text-lg gap-x-1">{{ __('app.phone_user') }}: <span
                    class="text-base">{{ $user->phone ?? "Не указан" }}</span>
            </div>
            <div class="text-lg gap-x-1">{{ __('app.email_user') }}: <span class="text-base">{{ $user->email }}</span>
            </div>
            <div class="text-lg gap-x-1">{{ __('app.city_user') }}: <span
                    class="text-base">{{ $user->city->name ?? "Не указан" }}</span></div>
        </div>
        <div class="flex-1/2 bg-gray p-4 rounded-sm text-white">
            <div class="text-lg">{{ __('app.date_register') }}: <span
                    class="text-base">{{ date('Y.m.d', strtotime($user->created_at))}}</span>
            </div>
            @AuthRole('employer')
                <div class="text-lg">{{ __('app.count_orders') }}: <span
                        class="text-base">{{ $user->employer->orders->count() }}</span></div>
            @endAuthRole
        </div>
    </div>
</section>