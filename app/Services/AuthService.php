<?php

namespace App\Services;
use App\Models\User;
use App\Models\Cart;
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
            'role' => 'Cliente',
        ]);

        // Generate JWT token
        $token = JWTUtil::generateToken(['user_id' => $user->user_id, 'email' => $user->email, 'role' => $user->role, 'username' => $user->username]);

        return $token;
    }

    public function login($credentials)
    {
        // Validate required fields
        $this->validator->validate($credentials, ['username']);

        // Search for user
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !password_verify($credentials['password'], $user->password)) {
            throw new \Exception('Correo electrónico o contraseña incorrectos.');
        }

        // Generate JWT token
        $token = JWTUtil::generateToken(['user_id' => $user->user_id, 'email' => $user->email, 'role' => $user->role, 'username' => $user->username]);

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
                    'role' => 'Cliente',
                ],
            );
        }

        $cart = Cart::where('user_id', $user->user_id)->get();

        $cartCount = count($cart);

        $token = JWTUtil::generateToken(['user_id' => $user->user_id, 'email' => $user->email, 'role' => $user->role, 'username' => $user->username]);

        return redirect(
            'http://localhost:5173' .
            '/auth/google/callback?token=' . $token . '&cartCount='. $cartCount
        );
    }

    public function validateToken($token)
    {
        return JWTUtil::validateToken($token);
    }

    public function refreshToken($token)
    {
        $payload = JWTUtil::validateToken($token);
        if (!$payload) throw new \Exception('Token inválido.');

        $newToken = JWTUtil::generateToken(['user_id' => $payload['user_id'], 'email' => $payload['email'], 'role' => $payload['role'], 'username' => $payload['username']]);

        return $newToken;
    }
}
