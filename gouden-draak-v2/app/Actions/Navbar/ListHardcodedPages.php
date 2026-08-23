<?php

namespace App\Actions\Navbar;

class ListHardcodedPages
{
    /**
     * List the site's built-in (non-database) pages that a navbar item can
     * link to, keyed by a stable identifier used to store the selection.
     *
     * @return array<string, array{route: string, label: string}>
     */
    public function handle(): array
    {
        return [
            'home' => ['route' => 'home', 'label' => 'Home'],
            'menu' => ['route' => 'menu', 'label' => 'Menu'],
            'order' => ['route' => 'order.create', 'label' => 'Order Online'],
            'contact' => ['route' => 'contact', 'label' => 'Contact'],
        ];
    }
}
