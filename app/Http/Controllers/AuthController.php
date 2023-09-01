<?php

namespace App\Http\Controllers;

use App\Helpers\Facades\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\RepositoryInterfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param \App\RepositoryInterfaces\UserRepositoryInterface $userRepository
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

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        if ($request->user()->currentAccessToken()->delete()) {
            return JsonResponse::success(null, __('Logout successfully.'));
        }

        return JsonResponse::error(__('Logout failed.'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
