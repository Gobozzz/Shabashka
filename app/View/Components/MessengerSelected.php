<?php

namespace App\View\Components;

use App\Services\MessengerService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MessengerSelected extends Component
{
    public $messengers;
    /**
     * Create a new component instance.
     */
    public function __construct(public MessengerService $messengerService)
    {
        $this->messengers = $this->messengerService->getAllWithLinkedInfoUser(auth()->user());
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.messenger-selected');
    }
}
