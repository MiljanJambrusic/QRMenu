<?php

namespace App\Helper;

class Helper
{

    /**
     * The list of accessible pages by the user role
     */

    const URL_PATH = 'http://localhost:8000';
    const URL_LOGO = self::URL_PATH.'/storage/images/logo/logo_main.png';

    public function navWithAccessRoles()
    {
        return [
            'sadmin' => [
                ['route' => 'dashboard', 'name' => 'Početna'],
                ['route' => 'tables', 'name' => 'Stolovi'],
                ['route' => 'users', 'name' => 'Korisnici'],
                ['route' => 'categories', 'name' => 'Kategorije'],
                ['route' => 'sub-categories', 'name' => 'Potkategorije'],
                ['route' => 'articles', 'name' => 'Artikli'],
                ['route' => 'view-orders', 'name' => 'Porudžbine'],
            ],
            'admin' => [
                ['route' => 'dashboard', 'name' => 'Početna'],
                ['route' => 'tables', 'name' => 'Stolovi'],
                ['route' => 'categories', 'name' => 'Kategorije'],
                ['route' => 'sub-categories', 'name' => 'Potkategorije'],
                ['route' => 'articles', 'name' => 'Artikli'],
                ['route' => 'view-orders', 'name' => 'Porudžbine'],
            ],
            'konobar' => [
                ['route' => 'dashboard', 'name' => 'Početna'],
                ['route' => 'view-orders', 'name' => 'Porudžbine']
            ],
        ];
    }

    public function userAccessRole()
    {
        return [
            'sadmin' => [
                'dashboard',
                'tables',
                'users',
                'categories',
                'sub-categories',
                'articles',
                'view-orders'
            ],
            'admin' => [
                'dashboard',
                'tables',
                'categories',
                'sub-categories',
                'articles',
                'view-orders'
            ],
            'konobar' => [
                'dashboard',
                'view-orders'
            ]
        ];
    }
}
