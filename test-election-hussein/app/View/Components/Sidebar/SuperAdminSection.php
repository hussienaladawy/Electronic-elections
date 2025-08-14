<?php
namespace App\View\Components\Sidebar;

use Illuminate\View\Component;

class SuperAdminSection extends Component
{
    public function shouldRender()
    {
        return auth()->check() && auth()->user()->role === 'super_admin';
    }

    public function render()
    {
        return view('components.sidebar.super-admin-section');
    }
}
