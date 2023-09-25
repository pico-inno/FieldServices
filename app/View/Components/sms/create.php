<?php

namespace App\View\Components\sms;

use Closure;
use Illuminate\View\Component;
use App\Models\Contact\Contact;
use Illuminate\Contracts\View\View;

class create extends Component
{
    /**
     * Create a new component instance.
     */
    public $service;
    public function __construct($service)
    {
        $this->service=$service;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        $contact = Contact::where('type', 'Customer')->whereNotNull('mobile')->orWhere('type', 'Both')->get();
        return view('components.sms.create',compact('contact'));
    }
}
