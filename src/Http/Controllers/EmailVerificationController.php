<?php

namespace JeffGreco13\FilamentBreezy\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function __invoke(string $id, string $hash): RedirectResponse
    {
        if (! hash_equals((string) $id, (string) Auth::user()->getKey())) {
            throw new AuthorizationException();
        }

        if (
            ! hash_equals(
                (string) $hash,
                sha1(Auth::user()->getEmailForVerification())
            )
        ) {
            throw new AuthorizationException();
        }

        if (Auth::user()->hasVerifiedEmail()) {
            return redirect(config("filament.home_url"));
        }

        if (Auth::user()->markEmailAsVerified()) {
            event(new Verified(Auth::user()));
        }

        return redirect(config("filament.home_url"));
    }
}
