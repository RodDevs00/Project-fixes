<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{CedulaRequirement, CedulaRequest, UserInfo};
use Illuminate\Support\Facades\Storage;
use App\Mail\{ApprovedRequest, RejectedRequest};
use Illuminate\Support\Facades\Mail;

class CedulaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAllCedulaRequestsByAdmin(Request $request)
    {
        $current_admin_info = UserInfo::where('user_id', auth()->user()->id)->first();
        // $admin_brgy = $current_admin_info->baranggay;
        
        try {
            $cedulaRequests = CedulaRequest::
            when($request->cedula_status, function ($query) use ($request) {
                $query->where('status', $request->cedula_status ?? "");
            })
            ->where(function ($query) use ($request) {
                $query->where('request_uuid', 'LIKE', '%'.$request->keyword.'%');
            })
            // ->whereHas('owner.userInfo', function ($query) use ($admin_brgy) {
            //     $query->where('baranggay', $admin_brgy);
            // })
            ->with(['requirements', 'owner.userInfo.account'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->limit);
    
            
            return response()->json($cedulaRequests, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => "Request failed, try again later."], 500);
        }
    }

    public function getUserCedulaRequest()
    {
        try {
            $cedulaRequests = CedulaRequest::where('user_id', auth()->user()->id)
            ->with(['requirements', 'owner.userInfo'])
            ->get();
            
            return response()->json($cedulaRequests, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => "Request failed, try again later."], 500);
        }
    }

    public function createCedulaRequest(Request $request)
    {
        try {
            $newRequest = CedulaRequest::create([
                'user_id'       => auth()->user()->id,
                'type'          => $request->type,
                'request_uuid'  => random_int(1, 9999999999),
            ]);
            
            return response()->json(['success' => 'Submitted! Your request will be validated by our staff.', 'cedula_req_id' => $newRequest->id], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => "Request failed, try again later."], 500);
        }
    }

    public function updateRequest(Request $request, $id)
    {
        try {
            $cedulaRequest = CedulaRequest::findOrFail($id);
            $cedulaRequest->update([
                'pick_up_date'  => $request->pick_up_date,
                'pick_up_time'  => $request->pick_up_time,
                'staff_comment' => $request->staff_comment,
                'status'        => "FOR RELEASE",
            ]);
            
            return response()->json(['success' => 'Request updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => "Update failed, try again later."], 500);
        }
    }

    public function approveRequest(Request $request, $id)
    {
        try {
            $cedulaRequest = CedulaRequest::with(['owner.userInfo'])->findOrFail($id);
            $cedulaRequest->update([
                'pick_up_date'  => $request->pick_up_date,
                'pick_up_time'  => $request->pick_up_time,
                'staff_comment' => $request->staff_comment,
                'status'        => "FOR RELEASE",
            ]);

            $name = $cedulaRequest->owner->userInfo->first_name;
            $email = $cedulaRequest->owner->email;
            Mail::to($email)->send(new ApprovedRequest($name)); 
            return response()->json(['success' => 'Request is now ready for release'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => "Approve failed, try again later."], 500);
        }
    }

    public function rejectRequest(Request $request, $id)
    {
        try {
            $cedulaRequest = CedulaRequest::with(['owner.userInfo'])->findOrFail($id);
            $cedulaRequest->update([
                'staff_comment' => $request->staff_comment,
                'status'        => "REJECTED",
            ]);
        
            $name = $cedulaRequest->owner->userInfo->first_name;
            $email = $cedulaRequest->owner->email;
            Mail::to($email)->send(new RejectedRequest($name)); 
            return response()->json(['success' => 'Request rejected'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => "Reject failed, try again later."], 500);
        }
    }

    public function markAsReleased(Request $request, $id)
    {
        try {
            $cedulaRequest = CedulaRequest::with(['owner.userInfo'])->findOrFail($id);
            $cedulaRequest->update([
                'status' => "RELEASED",
            ]);
        
            return response()->json(['success' => 'Request marked as released'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => "Marking as released failed, try again later."], 500);
        }
    }

    public function uploadCedulaRequirements(Request $request, $id)
    {
        try{
            $ext      = $request->file->extension();
            $fileName = random_int(1, 9999999999) . '.' . $ext;
            $request->file->move( storage_path('app/public/cedula/requirements/request_'.$id) , $fileName);
            
            $doc                    = new CedulaRequirement();
            $doc->file_name         = $fileName;
            $doc->file_path         = env('APP_URL').'/storage/cedula/requirements/request_'.$id.'/'.$fileName;
            $doc->cedula_request_id = $id;
            $doc->save();
            return response()->json(['success'=>'File uploaded successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => "Upload failed, try again later."], 500);
        }
    }
}
