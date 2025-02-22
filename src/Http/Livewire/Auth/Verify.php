<?php

namespace JeffGreco13\FilamentBreezy\Http\Livewire\Auth;

use Filament\Forms;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Verify extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public bool $hasBeenSent = false;

    public function mount()
    {
        if (
            auth()
                ->user()
                ->hasVerifiedEmail()
        ) {
            return redirect(config("filament.home_url"));
        }
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route("filament.auth.login");
    }

    public function resend()
    {
        $this->hasBeenSent = true;
        auth()
            ->user()
            ->sendEmailVerificationNotification();

        session()->flash("notify", [
            "status" => "success",
            "message" => "Verification email has been resent.",
        ]);
    }

    public function render(): View
    {
        $view = view("filament-breezy::verify");

        $view->layout("filament::components.layouts.base", [
            "title" => __("filament-breezy::default.verification.title"),
        ]);

        return $view;
    }
}
