<?php

use App\Models\Role;

function role_admin() {
    return Role::where('title', 'admin')->first()->id;
}

function role_user() {
    return Role::where('title', 'user')->first()->id;
}