<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;

class BackPaymenttController extends Controller
{
    public function index()
    {
        $data['content'] = 'backend/patients/payment';
        return view('backend/template/template', $data);
    }

    public function getPaymentBill(Request $request)
    {
        $payment = DB::table('payments')->join('patients','payments.patients_id','=','patients.id')
        ->join('prescriptions','payments.prescriptions_id','=','prescriptions.id')
        ->select('payments.id','patients.name','patients.birthday','patients.contact_no','patients.photo','patients.nic','payments.payment','prescriptions.p_note','payments.created_at')
        ->where('payments.id','=', $request->val)
        ->get();

        if ($payment != null) {
            $response['status'] = 200; 
             $response['data'] = $payment;
        }
        else {
            $response['status'] = 500; 
        }

        echo json_encode($response);

    }
}
