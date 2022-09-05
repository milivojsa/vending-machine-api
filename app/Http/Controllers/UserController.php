<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::paginate());
    }

    public function show(User $user)
    {
        $this->authorize('view', [$user]);

        return new UserResource($user);
    }

    public function store(StoreRequest $request)
    {
        $user = User::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'User created successfully!',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
        ]);
    }

    public function update(UpdateRequest $request, User $user)
    {
        $this->authorize('update', [$user]);

        $user->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully!',
        ]);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', [$user]);

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully!',
        ]);
    }

    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->only(['username', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Your credentials does not match with our record!',
            ], 401);
        }

        $user = User::where('username', $request->username)->first();

        $userAlreadyLoggedIn = (bool) PersonalAccessToken::where('tokenable_id', $user->id)
            ->where('tokenable_type', User::class)
            ->count();

        if ($userAlreadyLoggedIn) {
            return response()->json([
                'status' => false,
                'message' => 'There is already an active session using your account.',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully!',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
        ]);
    }

    public function logoutAll()
    {
        PersonalAccessToken::where('tokenable_id', Auth::id())
            ->where('tokenable_type', User::class)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'All of your active sessions are terminated!',
        ]);
    }
}
