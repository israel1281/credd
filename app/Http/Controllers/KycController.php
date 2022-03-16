<?php

namespace App\Http\Controllers;

use App\Models\Kyc;
use App\Models\Status;
use App\Notifications\KycNotify;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class KycController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'dob' => 'required',
            'gender' => ['required', Rule::in(['male', 'female'])],
            'phone' => 'required',
            'address' => 'required',
            'kin_name' => 'required',
            'kin_phone' => 'required',
            'relationship_status' => ['required', Rule::in(['single', 'married', 'divorced'])],
            'city' => 'required',
            'state' => 'required',
        ]);
        if (!auth()->user()->has_kyc) {
            auth()->user()->kyc()->create($request->all() + ['status_id' => status_pending_id()]);
            return apiSuccess(null, 'KYC completed successfully, awaiting approval!');
        } else {
            if (auth()->user()->has_kyc_pending)
                return apiSuccess(null, "Your KYC is being processed and you will be notified when it's approved!");
            else return apiSuccess(null, 'KYC already completed!');
        }
    }

    public function getKycs($count, $status = null) {
        $kycs = Kyc::query();
        if ($status) {
            $status = Status::where('title', $status)->first();
            $kycs = $kycs->where('status_id', $status->id);
        }
        $kycs = $kycs->latest()->with(['user', 'status'])->paginate($count);

        return apiSuccess($kycs, 'KYCs retrieved successfully!');
    }

    public function kycRespond(Kyc $kyc, $response) {
        if ($kyc->status_id != status_pending_id()) {
            return apiSuccess(null, 'You have already responded to this kyc');
        }
        if ($response == 'accept') {
            $kyc->update([
                'status_id' => status_accepted_id()
            ]);
            try {
                $kyc->user->notify(new KycNotify(KycNotify::ACCEPT));
            } catch (\Throwable $th) {
                reportError($th->getMessage());
            }
            return apiSuccess(null, 'KYC approved successfully!');
        } else if ($response == 'reject') {
            $kyc->update([
                'status_id' => status_rejected_id()
            ]);
            try {
                $kyc->user->notify(new KycNotify(KycNotify::REJECT, $kyc->amount_string));
            } catch (\Throwable $th) {
                reportError($th->getMessage());
            }
            return apiSuccess(null, 'KYC rejected successfully!');
        } else {
            return apiError('Invalid response, please try again!', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
