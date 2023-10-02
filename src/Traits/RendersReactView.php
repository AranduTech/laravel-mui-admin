<?php

namespace Arandu\LaravelMuiAdmin\Traits;

trait RendersReactView 
{

    public function getAdminView()
    {
        $roles = $this->roles;

        if ($roles->count() == 0) {
            return view('guest');
        }

        return view($roles->first()->name);
    }
}