<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class AppController extends Controller
{
    /**
     * Subscribe the user to a free app and open it.
     * For paid apps this would handle a payment flow — out of scope for now.
     */
    public function subscribe(string $slug): RedirectResponse
    {
        $apps = config('apps');

        abort_unless(isset($apps[$slug]), 404);

        auth()->user()->appSubscriptions()->firstOrCreate(['app_slug' => $slug]);

        return redirect()->route($apps[$slug]['entry_route'])
            ->with('success', "You've added {$apps[$slug]['name']} to your apps!");
    }

    /**
     * Remove an app from the user's dashboard (unsubscribe).
     */
    public function unsubscribe(string $slug): RedirectResponse
    {
        auth()->user()->appSubscriptions()->where('app_slug', $slug)->delete();

        return redirect()->route('dashboard')
            ->with('success', 'App removed from your dashboard.');
    }

    /**
     * Open an app the user already has. Just a redirect to its entry route.
     */
    public function open(string $slug): RedirectResponse
    {
        $apps = config('apps');

        abort_unless(isset($apps[$slug]), 404);

        return redirect()->route($apps[$slug]['entry_route']);
    }
}
