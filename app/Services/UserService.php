<?php

namespace App\Services;
use App\Models\User;
use App\Models\Product;
use App\Models\Address;
use App\Http\Resources\PublicProductsCollection;
use App\Http\Validators\UserValidator;
use App\Http\Validators\AddressValidator;
use App\Http\Validators\PasswordValidator;
use App\Http\Resources\UserResource;
use App\Http\Resources\AddressResource;

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

    public function getLikedProducts($user, $page = 1){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        $likedProductIds = $userModel->likes()->pluck('product_id')->toArray();

        $products = Product::with(['stocks', 'images'])
            ->whereIn('product_id', $likedProductIds)
            ->paginate(1, ['*'], 'page', $page);

        return new PublicProductsCollection($products);
    }

    public function getUserInfo($user){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        return new UserResource($userModel);
    }

    public function updateUserInfo($user, $newInfo){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        $this->userValidator->validate($newInfo);

        $userModel->update([
            "username" => $newInfo["username"],
            "email"=> $newInfo["email"],
            "phone_number" => $newInfo["phone_number"],
        ]);

        return new UserResource($userModel);
    }

    public function getUserAddress($user){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        $address = $userModel->addresses()->where("active", true)->first();

        return new AddressResource($address);
    }

    public function updateUserAddress($user, $newInfo){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

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

        return new AddressResource($updatedAddress);
    }

    public function updateUserPassword($user, $newInfo){
        $userModel = User::find($user->user_id);

        if(!$userModel) throw new \Exception("Usuario no encontrado");

        $this->passwordValidator->validate($newInfo);

        if(!password_verify($newInfo["current_password"], $userModel->password)){
            throw new \Exception("La contraseña actual no coincide con la contraseña enviada");
        }

        $user->password = bcrypt($newInfo['new_password']);
        $user->save();

        return new UserResource($user);
    }
}
