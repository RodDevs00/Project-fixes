<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;

class VerificationController extends Controller
{
    // Mark the email as verified
    public function verify(Request $request)
    {
        // Retrieve user manually by ID from the route parameters
        $user = User::find($request->route('id'));

        // Check if user is null
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        // Check if the email is already verified
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Your email has already been verified.',
                'instruction' => 'Go back to the website and try to log in.'
            ], 200);
        }

        // Mark the email as verified and trigger the event
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'message' => 'Your email has already been verified.',
            'instruction' => 'Go back to the website and try to log in.'
        ], 200);
    }

    // Resend the verification email
    public function resend(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email resent.'], 200);
    }
}
