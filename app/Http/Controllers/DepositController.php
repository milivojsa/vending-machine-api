<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function __invoke(DepositRequest $request)
    {
        $this->authorize('deposit', User::class);

        $user = Auth::user();

        $user->update([
            'deposit' => $user->deposit + $request->coin,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Deposit added successfully!',
        ]);
    }
}
