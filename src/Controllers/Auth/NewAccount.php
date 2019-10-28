<?php namespace Ivacuum\Generic\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\User;
use Illuminate\Auth\Events\Registered;

class NewAccount extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function register()
    {
        $data = request()->validate($this->rules());

        /** @var User $user */
        $user = User::where('email', $data['email'])->first();

        if (null !== $user) {
            return $this->existingUserResponse($user);
        }

        $user = $this->createUser($data);

        event(new Registered($user));

        return $this->registeredResponse($user);
    }

    protected function createUser(array $data): User
    {
        event(new \Ivacuum\Generic\Events\Stats\UserRegisteredWithEmail);

        return User::create([
            'email' => $data['email'],
            'status' => User::STATUS_INACTIVE,
            'password' => $data['password'],
            'activation_token' => \Str::random(16),
        ]);
    }

    protected function existingUserResponse(User $user)
    {
        \Password::broker()->sendResetLink(['email' => $user->email]);

        event(new \Ivacuum\Generic\Events\Stats\UserPasswordRemindedDuringRegistration);

        return back()->with(
            'message', trans('auth.email_taken', ['email' => $user->email])
        );
    }

    protected function registeredResponse(User $user)
    {
        \Auth::login($user, true);

        return redirect(path(HomeController::class));
    }

    protected function rules(): array
    {
        return [
            'email' => 'required|string|email|max:125',
            'password' => 'required|string|min:8',
        ];
    }
}
