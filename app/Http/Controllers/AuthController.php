<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\RepositoryInterfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
        //
    }

    /**
     * @param \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        ['email' => $email, 'password' => $password] = $request->only('email', 'password');

        /** @var \App\Models\User */
        $user = $this->userRepository->find($email, 'email');

        if (!$user || !Hash::check($password, $user->password)) {
            return JsonResponse::unauthorized(__('Email or password is incorrect.'));
        }

        $token = $user->createToken('Access Token');

        return JsonResponse::success([
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'user' => $user
        ], __('Login successfully.'));
    }
}
