<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Arandu\LaravelMuiAdmin\Services\JsService;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm(JsService $js)
    {
        if (old('email')) {
            $js->set('old.email', old('email'));
        }

        if (session('status')) {
            $js->set('status', session('status'));
        }

        $js->catches(['email']);

        return view('guest')->with(['js' => $js]);
    }
}
