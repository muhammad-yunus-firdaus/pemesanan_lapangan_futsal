<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after logout.
     *
     * @var string
     */
    protected function loggedOut(Request $request)
    {
        return redirect('/login');  // Arahkan ke halaman login setelah logout
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
    {
        if (auth()->user()->role == 'admin') {
            return route('admin.dashboard'); // Arahkan ke dashboard admin jika admin
        }

        if (auth()->user()->role == 'user') {
            return route('user.bookings.index'); // Arahkan ke halaman user
        }

        // Redirect to home or error if role is not valid
        return route('home');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');  // Menampilkan halaman login
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validasi inputan login
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        // Attempt login dengan username saja
        if (auth()->attempt(['username' => $request->login, 'password' => $request->password], $request->filled('remember'))) {
            return redirect()->intended($this->redirectTo());
        }

        // Jika login gagal
        return back()->withErrors([
            'login' => 'Username atau password salah.',
        ])->withInput($request->only('login', 'remember'));
    }
}
