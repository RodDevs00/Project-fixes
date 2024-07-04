<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, UserInfo};

class AccountController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getTotalApprovedUsers(Request $request)
    {
        $current_admin_info = UserInfo::where('user_id', auth()->user()->id)->first();
        // $admin_brgy = $current_admin_info->baranggay;

        $total_users = UserInfo::where('user_id', '<>', auth()->user()->id)
        // ->where('baranggay', $admin_brgy)
        ->whereHas('account', function ($query) {
            $query->where('account_activated', 1);
        })->count();

        return response()->json($total_users);
    }

    public function getTotalSuspendedUsers(Request $request)
    {
        $current_admin_info = UserInfo::where('user_id', auth()->user()->id)->first();
        // $admin_brgy = $current_admin_info->baranggay;
        // ::where('baranggay', $admin_brgy)
        // ->
        $total_users = UserInfo::whereHas('account', function ($query) {
            $query->where('account_activated', 2);
        })->count();

        return response()->json($total_users);
    }

    public function getPendingUsers(Request $request)
    {
        $current_admin_info = UserInfo::where('user_id', auth()->user()->id)->first();
        // $admin_brgy = $current_admin_info->baranggay;

        $users = UserInfo::where(function ($query) use ($request) {
            $query->where('first_name', 'LIKE', '%'.$request->keyword.'%')
            ->orWhere('middle_name', 'LIKE', '%'.$request->keyword.'%')
            ->orWhere('surname', 'LIKE', '%'.$request->keyword.'%');
        })
        // ->where('baranggay', $admin_brgy)
        ->whereHas('account', function ($query) {
            $query->where('account_activated', 0);
        })->with('account')
        ->orderBy('created_at', 'desc')
        ->paginate($request->limit);

        return response()->json($users);
    }

    public function getApprovedUsers(Request $request)
    {
        $current_admin_info = UserInfo::where('user_id', auth()->user()->id)->first();
        // $admin_brgy = $current_admin_info->baranggay;

        $users = UserInfo::where('user_id', '<>', auth()->user()->id)
        ->where(function ($query) use ($request) {
            $query->where('first_name', 'LIKE', '%'.$request->keyword.'%')
            ->orWhere('middle_name', 'LIKE', '%'.$request->keyword.'%')
            ->orWhere('surname', 'LIKE', '%'.$request->keyword.'%');
        })
        // ->where('baranggay', $admin_brgy)
        ->whereHas('account', function ($query) {
            $query->where('account_activated', 1);
        })->with('account')
        ->orderBy('created_at', 'desc')
        ->paginate($request->limit);

        return response()->json($users);
    }

    public function getSuspendedUsers(Request $request)
    {
        $current_admin_info = UserInfo::where('user_id', auth()->user()->id)->first();
        // $admin_brgy = $current_admin_info->baranggay;

        $users = UserInfo::where(function ($query) use ($request) {
            $query->where('first_name', 'LIKE', '%'.$request->keyword.'%')
            ->orWhere('middle_name', 'LIKE', '%'.$request->keyword.'%')
            ->orWhere('surname', 'LIKE', '%'.$request->keyword.'%');
        })
        // ->where('baranggay', $admin_brgy)
        ->whereHas('account', function ($query) {
            $query->where('account_activated', 2);
        })->with('account')
        ->orderBy('created_at', 'desc')
        ->paginate($request->limit);

        return response()->json($users);
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'account_activated' => 1
        ]);
        
        return response()->json(['success' => 'Account has been approved', $user], 200);
    }

    public function suspendUser($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'account_activated' => 2
        ]);
        
        return response()->json(['success' => 'Account has been suspended', $user], 200);
    }

    public function unsuspendUser($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'account_activated' => 1
        ]);
        
        return response()->json(['success' => 'Account has been activated', $user], 200);
    }

    public function declineUser($id)
    {
        $user = User::findOrFail($id);
        $user_info = UserInfo::where('user_id', $user->id)->first();

        $user->delete();
        $user_info->delete();
        
        return response()->json(['success' => 'Account has been removed permanently', $user], 200);
    }
}
