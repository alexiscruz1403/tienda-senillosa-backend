<?php

namespace App\Services;
use App\Models\User;
use App\Models\Address;
use App\Http\Validators\UserValidator;
use App\Http\Validators\AddressValidator;
use App\Http\Validators\PasswordValidator;
use App\Http\Resources\UserResource;
use App\Http\Resources\AddressResource;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Support\Facades\Cache;

class UserService
{
    protected $userValidator;
    protected $addressValidator;
    protected $passwordValidator;

    public function __construct(UserValidator $validator, AddressValidator $addressValidator, PasswordValidator $passwordValidator)
    {
        $this->userValidator = $validator;
        $this->addressValidator = $addressValidator;
        $this->passwordValidator = $passwordValidator;
    }

    public function getUserInfo($user){
        $userModel = Cache::remember("info.user.{$user->user_id}", now()->addHour(), function () use($user) {
            return User::find($user->user_id);
        });

        if(!$userModel) throw new UnauthorizedHttpException("Usuario no encontrado");

        return new UserResource($userModel);
    }

    public function updateUserInfo($user, $newInfo){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new UnauthorizedHttpException("Usuario no encontrado");

        $this->userValidator->validate($newInfo);

        $userModel->update([
            "username" => $newInfo["username"],
            "email"=> $newInfo["email"],
            "phone_number" => $newInfo["phone_number"],
        ]);

        Cache::forget("info.user.{$user->user_id}");

        return new UserResource($userModel);
    }

    public function getUserAddress($user){
        $userModel = Cache::remember("address.user.{$user->user_id}", now()->addHour(), function () use($user) {
            return User::find($user->user_id);
        });

        if(!$userModel) throw new UnauthorizedHttpException("Usuario no encontrado");

        $address = $userModel->addresses()->where("active", true)->first();

        return new AddressResource($address);
    }

    public function updateUserAddress($user, $newInfo){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new UnauthorizedHttpException("Usuario no encontrado");

        $this->addressValidator->validate($newInfo);

        $existingAddress = $user->addresses()
            ->where('postal_code', $newInfo['postal_code'])
            ->where('street', $newInfo['street'])
            ->first();

        $activeAddress = $user->addresses()
            ->where('active', true)
            ->first();

        $updatedAddress = null;

        if($existingAddress){
            $updatedAddress = Address::find($existingAddress->address_id);
            $updatedAddress->update([...$newInfo, "active" => true]);

            if($existingAddress->address_id !== $activeAddress->address_id){
                $unactiveAddress = Address::find($activeAddress->address_id);
                $unactiveAddress->update(["active" => false]);
            }
        }else{
            if($activeAddress) {
                $unactiveAddress = Address::find($activeAddress->address_id);
                $unactiveAddress->update(["active" => false]);
            }

            $updatedAddress = $user->addresses()->create($newInfo);
        }

        Cache::forget("address.user.{$user->user_id}");

        return new AddressResource($updatedAddress);
    }

    public function updateUserPassword($user, $newInfo){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new UnauthorizedHttpException("Usuario no encontrado");

        $this->passwordValidator->validate($newInfo);

        if(!password_verify($newInfo["current_password"], $userModel->password)){
            throw new UnauthorizedHttpException("La contraseña actual no coincide con la contraseña enviada");
        }

        $user->password = bcrypt($newInfo['new_password']);
        $user->save();

        return new UserResource($user);
    }
}
