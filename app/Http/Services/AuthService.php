<?php

namespace App\Http\Services;
use App\Models\User;
use App\Http\Validators\AuthValidator;
use App\Utils\JWTUtil;
use Laravel\Socialite\Facades\Socialite;

class AuthService
{
    protected $validator;

    public function __construct(AuthValidator $validator)
    {
        $this->validator = $validator;
    }

    public function register($userData)
    {
        // Validate required fields
        $this->validator->validate($userData);

        // Search for existing user
        $existingUser = User::where('email', $userData['email'])->first();

        if ($existingUser) throw new \Exception('El correo electrónico ya está en uso.');

        // Create new user
        $user = new User();

        $user->create([
            'username' => $userData['username'],
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
            'role' => 'cliente',
        ]);

        // Generate JWT token
        $token = JWTUtil::generateToken(['user_id' => $user->user_id, 'email' => $user->email, 'role' => $user->role]);

        return $token;
    }

    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Search for existing user
        $user = User::where('google_id', $googleUser->getId())->first();
        if(!$user){
            $user = User::where('email', $googleUser->getEmail())->first();
            if($user){
                $user->google_id = $googleUser->getId();
                $user->save();
            }
        }

        // If user does not exist, create new user
        if(!$user){
            $user = User::create(
                [
                    'email' => $googleUser->getEmail(),
                    'username' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(bin2hex(random_bytes(16))),
                    'role' => 'cliente',
                ],
            );
        }

        $token = JWTUtil::generateToken(['user_id' => $user->user_id, 'email' => $user->email, 'role' => $user->role]);

        return redirect(
            'http://localhost:5173' .
            '/auth/google/callback?token=' . $token
        );
    }
}
