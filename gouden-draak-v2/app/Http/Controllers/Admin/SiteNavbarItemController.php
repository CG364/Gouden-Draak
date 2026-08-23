<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Navbar\ListHardcodedPages;
use App\Actions\Navbar\ReorderNavbarItem;
use App\Actions\Navbar\ResolveNavbarLinkTarget;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSiteNavbarItemRequest;
use App\Http\Requests\Admin\UpdateSiteNavbarItemRequest;
use App\Models\Page;
use App\Models\SiteNavbarItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteNavbarItemController extends Controller
{
    /**
     * Display the public site's navbar items in their display order.
     */
    public function index(): View
    {
        return view('admin.navbar-items.index', [
            'navbarItems' => SiteNavbarItem::query()->with('page')->orderBy('order')->get(),
        ]);
    }

    /**
     * Show the form for creating a new navbar item.
     */
    public function create(ListHardcodedPages $listHardcodedPages): View
    {
        return view('admin.navbar-items.create', [
            'navbarItem' => new SiteNavbarItem,
            'pages' => Page::query()->orderBy('slug')->get(),
            'hardcodedPages' => $listHardcodedPages->handle(),
        ]);
    }

    /**
     * Store a newly created navbar item in storage, appending it to the end of the nav.
     */
    public function store(StoreSiteNavbarItemRequest $request, ResolveNavbarLinkTarget $resolveNavbarLinkTarget): RedirectResponse
    {
        $target = $resolveNavbarLinkTarget->handle($request->validated('link_target'), $request->validated('custom_url'));

        SiteNavbarItem::query()->create([
            'header' => $request->validated('header'),
            'page_id' => $target['page_id'],
            'foreign_url' => $target['foreign_url'],
            'order' => (SiteNavbarItem::query()->max('order') ?? -1) + 1,
        ]);

        return redirect()->route('admin.navbar-items.index')->with('status', 'Navbar item created.');
    }

    /**
     * Show the form for editing the specified navbar item.
     */
    public function edit(SiteNavbarItem $navbarItem, ListHardcodedPages $listHardcodedPages): View
    {
        $hardcodedPages = $listHardcodedPages->handle();
        $selectedLinkTarget = 'custom';

        if ($navbarItem->page_id !== null) {
            $selectedLinkTarget = "page:{$navbarItem->page_id}";
        } else {
            foreach ($hardcodedPages as $key => $hardcodedPage) {
                if ($navbarItem->foreign_url === route($hardcodedPage['route'])) {
                    $selectedLinkTarget = "route:{$key}";
                    break;
                }
            }
        }

        return view('admin.navbar-items.edit', [
            'navbarItem' => $navbarItem,
            'pages' => Page::query()->orderBy('slug')->get(),
            'hardcodedPages' => $hardcodedPages,
            'selectedLinkTarget' => $selectedLinkTarget,
        ]);
    }

    /**
     * Update the specified navbar item in storage.
     */
    public function update(UpdateSiteNavbarItemRequest $request, SiteNavbarItem $navbarItem, ResolveNavbarLinkTarget $resolveNavbarLinkTarget): RedirectResponse
    {
        $target = $resolveNavbarLinkTarget->handle($request->validated('link_target'), $request->validated('custom_url'));

        $navbarItem->update([
            'header' => $request->validated('header'),
            'page_id' => $target['page_id'],
            'foreign_url' => $target['foreign_url'],
        ]);

        return redirect()->route('admin.navbar-items.index')->with('status', 'Navbar item updated.');
    }

    /**
     * Remove the specified navbar item from storage.
     */
    public function destroy(SiteNavbarItem $navbarItem): RedirectResponse
    {
        $navbarItem->delete();

        return redirect()->route('admin.navbar-items.index')->with('status', 'Navbar item deleted.');
    }

    /**
     * Move the specified navbar item one position earlier in the nav.
     */
    public function moveUp(SiteNavbarItem $navbarItem, ReorderNavbarItem $reorderNavbarItem): RedirectResponse
    {
        $reorderNavbarItem->handle($navbarItem, 'up');

        return redirect()->route('admin.navbar-items.index');
    }

    /**
     * Move the specified navbar item one position later in the nav.
     */
    public function moveDown(SiteNavbarItem $navbarItem, ReorderNavbarItem $reorderNavbarItem): RedirectResponse
    {
        $reorderNavbarItem->handle($navbarItem, 'down');

        return redirect()->route('admin.navbar-items.index');
    }
}
