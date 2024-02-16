<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Models\EmailVerificationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        if(!$user->email_verified_at){
            return response()->json(['error' => 'Email not verified'], 401);
        }

        $accessLevel = $user->access_level->access_level;

        if($accessLevel < 0){
            return response()->json(['error' => 'User blocked'], 403);
        }

        return $this->respondWithToken($token);
    }

    public function register(RegisterUserRequest $request)
    {
        $credentials = $request->all(['name', 'last_name', 'address', 'email', 'password']);

        $defaultRoleId = 2;

        $credentials['role_id'] = $defaultRoleId;

        $newUserId = User::create($credentials)->id;

        $activationToken = md5(uniqid(rand())).md5(time()).md5(uniqid(rand()));

        EmailVerificationToken::create(["user_id" => $newUserId, "role_id" => $defaultRoleId, "token" => $activationToken]);

        //In the future, send email here

        return response()->json(['message' => 'We sent you a verification email'], 201);
    }

    public function verifyEmail(VerifyEmailRequest $request){
        $routeToken = $request->route('token');
        $tokenObject = EmailVerificationToken::where('token', '=', $routeToken)->first();

        $userToActivateId = $tokenObject->user->id;
        $newUserRole = $tokenObject->role->id;
        $timeOfActivation = now();

        $user = User::find($userToActivateId);
        $user->role_id = $newUserRole;
        $user->email_verified_at = $timeOfActivation;
        $user->save();

        $tokenObject->delete();

        $token = auth()->login($user);

        return response()->json(['message' => 'Successfully activated account', 'body' => $token], 201);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
