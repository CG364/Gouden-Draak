<?php

namespace App\Actions\Navbar;

use InvalidArgumentException;

class ResolveNavbarLinkTarget
{
    public function __construct(private ListHardcodedPages $listHardcodedPages) {}

    /**
     * Turn the navbar item form's single "links to" selection (a database
     * page, a built-in site page, or a custom URL) into the `page_id` /
     * `foreign_url` pair actually stored on the model.
     *
     * @return array{page_id: ?int, foreign_url: ?string}
     */
    public function handle(string $linkTarget, ?string $customUrl): array
    {
        if ($linkTarget === 'custom') {
            return ['page_id' => null, 'foreign_url' => $customUrl];
        }

        if (str_starts_with($linkTarget, 'page:')) {
            return ['page_id' => (int) substr($linkTarget, 5), 'foreign_url' => null];
        }

        if (str_starts_with($linkTarget, 'route:')) {
            $key = substr($linkTarget, 6);
            $hardcodedPages = $this->listHardcodedPages->handle();

            if (! isset($hardcodedPages[$key])) {
                throw new InvalidArgumentException("Unknown site page [{$key}].");
            }

            return ['page_id' => null, 'foreign_url' => route($hardcodedPages[$key]['route'])];
        }

        throw new InvalidArgumentException("Unknown link target [{$linkTarget}].");
    }
}
