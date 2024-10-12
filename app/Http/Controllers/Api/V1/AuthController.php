<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ServiceResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthServiceInterface $authServiceInterface)
    {
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $user = $this->authServiceInterface->login($validatedData['email'], $validatedData['password']);

        $user = json_decode(json_encode(new UserResource($user)));


        // Return the response
        $resp = new ServiceResponse('user login successfully', true, $user);

        return response()->json($resp->getRepr(), JsonResponse::HTTP_OK);
    }
}
