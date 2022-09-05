<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ResetController extends Controller
{
    public function __invoke()
    {
        $this->authorize('reset', User::class);

        Auth::user()->update([
            'deposit' => 0,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Deposit reset successfully!',
        ]);
    }
}
