<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');

        $this->middleware('throttle:3,1')->only('login');
    }

    protected function attemptLogin(Request $request)
	{
		// Your custom login logic
		$credentials = $this->credentials($request);

		// Retrieve user by email
		$user = User::where('email', $credentials['email'])->first();

		// Check if the user exists and the password is correct
		if (!$user || !Hash::check($credentials['password'], $user->password)) {
			return false;
		}

		// Check if the user is active (status == 1)
		if (!$user->status) {
			throw ValidationException::withMessages([
				'email' => [trans('You account access is disabled')],
			]);
		}

		// Attempt to log in the user
		return $this->guard()->attempt(
			$credentials, $request->filled('remember')
		);
	}

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('login');
    }

}
