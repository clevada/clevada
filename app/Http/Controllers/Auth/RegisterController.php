<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Auth;
use DB;
use App\Models\Core;
use App\Models\User;
use App\Mail\Register;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->UserModel = new User();
        $this->config = Core::config();

        if (Auth::user()) return redirect(route('homepage'));
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if ($this->config->registration_disabled ?? null) exit('Registration is disabled');

        if (($this->config->google_recaptcha_enabled ?? null) && ($this->config->registration_recaptcha ?? null) && ($this->config->google_recaptcha_site_key ?? null) && ($this->config->google_recaptcha_secret_key ?? null)) {
            // Build POST request:
            $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
            $recaptcha_secret = $this->config->google_recaptcha_secret_key;
            $recaptcha_response = $data['recaptcha_response'];

            // Make and decode POST request:
            $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
            $recaptcha = json_decode($recaptcha);

            // Take action based on the score returned:           
            if ($recaptcha->success) {
                if ($recaptcha->score < 0.5) exit('Recaptcha error. Go back and try again');
            } else redirect(route('register'))->with('error', 'recaptcha');
        }

        if (DB::table('users')->where('email', $data['email'])->exists()) return redirect(route('register'))->with('error', 'duplicate_email');

        // get role ID for user
        $role_id =  $this->UserModel->get_role_id_from_role('user');
        $code = strtoupper(Str::random(8));

        if (DB::table('users')->where('code', $code)->exists()) exit('Error. Duplicate user code. Please try again.');

        if (($this->config->registration_verify_email_disabled ?? null)) $email_verified_at = now();
        else $email_verified_at = null;

        $user_code = strtoupper(Str::random(12));

        Mail::to($data['email'])->locale('en')->send(new Register($user_code));

        // log email in database       
        Core::log_email(array(
            'email' => $data['email'],
            'subject' => 'Your account has beeen created',
            'message' => (new Register($user_code))->render(),
            'module' => 'core',
            'type' => 'new_account',
            'created_at' => now(),
        ));

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role_id ?? 0,
            'slug' => Str::slug($data['name'], '-'),
            'active' => 1,
            'created_at' => now(),
            'code' => $user_code,
            'is_deleted' => 0,
            'email_verified_at' => $email_verified_at,
            'register_ip' => $_SERVER['REMOTE_ADDR'],
        ]);
    }
}
