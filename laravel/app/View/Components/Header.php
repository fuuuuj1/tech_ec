<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Header extends Component
{
    # --- 省略 ---
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $user = Auth::user();

        return view('components.header')
            ->with('user', $user);
    }
}
