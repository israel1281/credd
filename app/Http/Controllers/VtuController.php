<?php

namespace App\Http\Controllers;

use App\Http\Resources\VtuAirtimeListResource;
use App\Http\Resources\VtuAirtimeResource;
use App\Http\Resources\VtuCableListResource;
use App\Http\Resources\VtuElectricityListResource;
use App\Http\Resources\VtuTransactionsCollection;
use App\Http\Resources\VtuTransactionsResource;
use App\Models\Status;
use App\Models\VtuBillPayment;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VtuController extends Controller
{
    // Verify Customer
    public function verifyCustomer(Request $request) {
        $request->validate([
            'customer_id' => 'required|min:10',
            'service_id' => 'required',
        ]);

        $bpRequest = [
            'username' => config('vtu.username'),
            'password' => config('vtu.password'),
            'customer_id' => $request->customer_id,
            'service_id' => $request->service_id,
            'variation_id' => $request->variation_id
        ];
        try {
            $vtuResponse = HTTP::get(config('vtu.url.verify'), $bpRequest);
        } catch (\Throwable $th) {
            return apiError('An error was encountered while processing your request: '.$th->getMessage());
        }

        if ($vtuResponse != null) {
            $data = $vtuResponse->json();
            try {
                if ($data['code'] == 'success') {
                    return $data;
                } else if($data['code'] == 'failure') {
                    $this->reportVTUError($data['message']);
                    return apiError($data['message']);
                } else if($data['code'] == 'processing') {
                    return apiError("Oops something went wrong! Please try again later or contact support.");
                } else {
                    $this->reportVTUError($data['message']);
                    return apiError("Oops something went wrong! Please try again later or contact support.");
                }
            } catch (\Throwable $th) {
                $this->reportVTUError($th->getMessage());
                return apiError("Oops something went wrong! Please try again later or contact support");
            }
        } else {
            $this->reportVTUError("VTU api returned an empty or null response");
            return apiError("Oops something went wrong, please try again later");
        }
    }

    // Get all Cable packages
    public function getCablePackages() {
        $packages = DB::table('vtu_bill_payments')
                        ->where('bp_type', 'cable')
                        ->groupBy('service_id')
                        ->get();
        return apiSuccess(VtuCableListResource::collection($packages), "Cable list retrieved successfully!");
    }

    // All Airtime packages
    public function airtimePackages() {
        $packages = VtuBillPayment::where('bp_type', VtuBillPayment::TYPE_AIRTIME)
                                    ->where('status_id', status_active_id())
                                    ->get();
        return apiSuccess(VtuAirtimeListResource::collection($packages), "Vtu airtime packages retrieved successfully!");
    }

    // All Gotv packages
    public function gotvPackages() {
        $packages = VtuBillPayment::where('bp_type', VtuBillPayment::TYPE_CABLE)
                                    ->where('service_id', 'gotv')
                                    ->where('status_id', status_active_id())
                                    ->get();
        return apiSuccess(VtuCableListResource::collection($packages), "Vtu gotv packages retrieved successfully!");
    }

    // All Startimes packages
    public function startimesPackages() {
        $packages = VtuBillPayment::where('bp_type', VtuBillPayment::TYPE_CABLE)
                                    ->where('service_id', 'startimes')
                                    ->where('status_id', status_active_id())
                                    ->get();
        return apiSuccess(VtuCableListResource::collection($packages), "Vtu startimes packages retrieved successfully!");
    }

    // All Dstv packages
    public function dstvPackages() {
        $packages = VtuBillPayment::where('bp_type', VtuBillPayment::TYPE_CABLE)
                                    ->where('service_id', 'dstv')
                                    ->where('status_id', status_active_id())
                                    ->get();
        return apiSuccess(VtuCableListResource::collection($packages), "Vtu dstv packages retrieved successfully!");
    }

    // All Electricity packages
    public function electricityPackages() {
        $packages = VtuBillPayment::where('bp_type', VtuBillPayment::TYPE_ELECTRICITY)
                                    ->where('status_id', status_active_id())
                                    ->get();
        return apiSuccess(VtuElectricityListResource::collection($packages), "Vtu electricity packages retrieved successfully!");
    }

    // Pay Airtime
    public function payAirtime(VtuBillPayment $billPayment, Request $request) {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/0[7-9][01]\d{8}$/i'],
            'amount' => ['required', 'numeric', 'min:50', 'max:10000'],
        ]);
        if (auth()->user()->wallet->amt < $request->amount) {
            return apiError("Insufficient funds! Please fund wallet to purchase.");
        }
        $bpRequest = [
            'username' => config('vtu.username'),
            'password' => config('vtu.password'),
            'network_id' => $billPayment->service_id,
            'amount' => $request->amount,
            'phone' => $request->phone
        ];
        try {
            $vtuResponse = HTTP::get(config('vtu.url.airtime'), $bpRequest);
        } catch (\Throwable $th) {
            return apiError('An error was encountered while processing your request: '.$th->getMessage());
        }

        if ($vtuResponse != null) {
            $data = $vtuResponse->json();
            if ($data['code'] == 'success') {
                // Deduct wallet
                auth()->user()->wallet->amt -= $request->amount;
                auth()->user()->wallet->save();

                return $this->storeTransaction(
                    $billPayment,
                    $request->amount,
                    $request->phone,
                    $data['data']['order_id'],
                    status_completed_id(),
                    'Airtime purchased successfully!'
                );
            } else if($data['code'] == 'failure') {
                $this->reportVTUError($data['message']);
                return apiError("Oops something went wrong! Please try again later or contact support.");
            } else if($data['code'] == 'processing') {
                return $this->storeTransaction(
                    $billPayment,
                    $request->amount,
                    $request->phone,
                    $data['data']['order_id'],
                    status_processing_id(),
                    'Please wait! Your airtime is being processed!'
                );
            } else {
                $this->reportVTUError($data['message']);
                return apiError("Oops something went wrong! Please try again later or contact support.");
            }
        } else {
            $this->reportVTUError("VTU api returned an empty or null response");
            return apiError("Oops something went wrong, please try again later");
        }
    }

    // Pay Cable
    public function payCable(VtuBillPayment $billPayment, Request $request) {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/0[7-9][01]\d{8}$/i'],
            'smartcard_number' => ['required', 'string', 'min:10']
        ]);

        $amount = $billPayment->amount + $billPayment->fee;

        if (auth()->user()->wallet->amt < $amount) {
            return apiError("Insufficient funds! Please fund wallet to purchase.");
        }

        $bpRequest = [
            'username' => config('vtu.username'),
            'password' => config('vtu.password'),
            'smartcard_number' => $request->smartcard_number,
            'service_id' => $billPayment->service_id,
            'variation_id' => $billPayment->variation_id,
            'phone' => $request->phone
        ];
        try {
            $vtuResponse = HTTP::get(config('vtu.url.cable'), $bpRequest);
        } catch (\Throwable $th) {
            return apiError('An error was encountered while processing your request: '.$th->getMessage());
        }

        if ($vtuResponse != null) {
            $data = $vtuResponse->json();
            try {
                if ($data['code'] == 'success') {
                    // Deduct wallet
                    auth()->user()->wallet->amt -= $amount;
                    auth()->user()->wallet->save();

                    return $this->storeTransaction(
                        $billPayment,
                        $billPayment->amount + $billPayment->fee,
                        $request->smartcard_number,
                        $data['data']['order_id'],
                        status_completed_id(),
                        'Cable/TV subscription bought successfully!'
                    );
                } elseif ($data['code'] == 'failure') {
                    $this->reportVTUError($data['message']);
                    return apiError("Oops something went wrong! Please try again later or contact support.");
                } elseif ($data['code'] == 'processing') {
                    return $this->storeTransaction(
                        $billPayment,
                        $billPayment->amount + $billPayment->fee,
                        $request->phone,
                        $data['data']['order_id'],
                        status_completed_id(),
                        'Your subscription is being processed.'
                    );
                } else {
                    $this->reportVTUError($data['message']);
                    return apiError("Oops something went wrong! Please try again later or contact support.");
                }
            } catch (\Throwable $th) {
                $this->reportVTUError($th->getMessage());
                return apiError("Oops something went wrong! Please try again later or contact support");
            }
        } else {
            $this->reportVTUError("VTU api returned an empty or null response");
            return apiError("Oops something went wrong, please try again later");
        }
    }

    // Pay Electricity
    public function payElectricity(VtuBillPayment $billPayment, Request $request) {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/0[7-9][01]\d{8}$/i'],
            'meter_number' => ['required'],
            'amount' => ['required', 'numeric', 'min:500'],
        ]);
        $bpRequest = [
            'username' => config('vtu.username'),
            'password' => config('vtu.password'),
            'meter_number' => $request->meter_number,
            'service_id' => $billPayment->service_id,
            'variation_id' => $billPayment->variation_id,
            'amount' => $request->amount,
            'phone' => $request->phone
        ];
        try {
            $vtuResponse = HTTP::get(config('vtu.url.electricity'), $bpRequest);
        } catch (\Throwable $th) {
            return apiError('An error was encountered while processing your request: '.$th->getMessage());
        }

        if ($vtuResponse != null) {
            $data = $vtuResponse->json();
            if ($data['code'] == 'success') {
                return $this->storeTransaction(
                    $billPayment,
                    $request->amount,
                    $request->meter_number,
                    $data['data']['order_id'],
                    status_completed_id(),
                    'Electricity bills payment successfully!'
                );
            } else if($data['code'] == 'failure') {
                $this->reportVTUError($data['message']);
                return apiError("Oops something went wrong! Please try again later or contact support.");
            } else if($data['code'] == 'processing') {
                return $this->storeTransaction(
                    $billPayment,
                    $request->amount,
                    $request->meter_number,
                    $data['data']['order_id'],
                    status_processing_id(),
                    'Please wait! Your electricity bills payment is being processed!'
                );
            } else {
                $this->reportVTUError($data['message']);
                return apiError("Oops something went wrong! Please try again later or contact support.");
            }
        } else {
            $this->reportVTUError("VTU api returned an empty or null response");
            return apiError("Oops something went wrong, please try again later");
        }
    }

    // transactions
    public function getBPTransactions($bpType, $count, $status = null) {
        $bpTransactions = auth()->user()->bpTransactions()->where('bp_type', $bpType);
        if ($status) {
            $status = Status::where('title', $status)->first();
            $bpTransactions = $bpTransactions->where('status_id', $status->id);
        }
        $bpTransactions = $bpTransactions->latest()->with(['status', 'billable'])->paginate($count);

        return apiSuccess(new VtuTransactionsCollection($bpTransactions), "Bill payment transactions queried successfully!");
    }

    // store transaction to database
    protected function storeTransaction(VtuBillPayment $billPayment, $amount, $customer, $orderId, $statusId, $message) {
        $bp = auth()->user()->bpTransactions()->create([
            'bp_type' => $billPayment->bp_type,
            'bp_name' => $billPayment->name,
            'amount' => $amount,
            'reference' => 'VTUBP'.$orderId,
            'customer' => $customer,
            'billable_id' => $billPayment->id,
            'billable_type' => 'App\Models\VtuBillPayment',
            'status_id' => $statusId
        ]);
        return apiSuccess(new VtuAirtimeResource($bp), $message);
    }

    // Report error
    protected function reportVTUError($message) {
        reportError('VTU ERROR: '.$message);
    }
}
