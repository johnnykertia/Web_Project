<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminResetPasswordSend;
use App\Http\Requests\HandleLoginRequest;
use App\Http\Requests\SendResetLinkRequest;
use App\Mail\AdminSendResetLinkMail;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminAuthentificationController extends Controller
{
    public function login()
    {
        return view('admin.auth.login');
    }

    public function handleLogin(HandleLoginRequest $request)
    {
        $request->authenticate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function forgotPassword()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLink(SendResetLinkRequest $request)
    {
        // $token = \Str::random(64);
        $token = Str::random(64);

        $admin = Admin::where('email', $request->email)->first();
        $admin->remember_token = $token;
        $admin->save();

        Mail::to($request->email)->send(new AdminSendResetLinkMail($token, $request->email));
        // return redirect()->back()->with('success', 'A mail has been send to your email address, please your check!');
        return redirect()->back()->with('success', __('A mail has been sent to your email address please check!'));
    }

    public function resetPassword($token)
    {
        return view('admin.auth.reset-link-password', compact('token'));
    }

    public function handleResetPassword(AdminResetPasswordSend $request)
    {
        $admin = Admin::where(['email' => $request->email, 'remember_token' => $request->token])->first();

        if (!$admin) {
            return back()->with('error', __('admin.token is invalid'));
        }

        $admin->password = bcrypt($request->password);
        $admin->remember_token = null;
        $admin->save();

        return redirect()->route('admin.login')->with('success', __('admin.Password reset successfull'));
    }

    // public function resetPassword($token)
    // {
    //     return view('admin.auth.reset-link-password', compact('token'));
    // }

    // public function handleResetPassword(AdminResetPasswordSend $request)
    // {
    //     $admin = Admin::where(['email' => $request->email, 'remember_token' => $request->token])->first();

    //     if (!$admin) {
    //         return back()->with('error', 'token is invalid');
    //     };

    //     $admin->password = bcrypt($request->password);
    //     $admin->remember_token = null;
    //     $admin->save();

    //     return redirect()->route('admin.login')->with('success', 'your password succesly reset');
    // }
}