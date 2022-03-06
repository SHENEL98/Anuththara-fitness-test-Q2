<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Payment;
use App\Models\Patient;


use DB;

class BackPrescriptionsController extends Controller
{

    public function prescriptions_index()
    {
        $data['content'] = 'backend/patients/prescriptions_index';
        $data['get_prescriptions'] = Patient::all();
        return view('backend/template/template', $data);
    }

    public function selectPatient(Request $request)
    {
        $result = DB::table('prescriptions')
        ->where('patients_id','=',$request->patient_id)
        ->get();
 
        return response()->json(['data' => $result]);
    }

    public function AddPrescriptions(Request $request)
    { 
        $request->validate([
            'pid' => 'required',
            'pnote' => 'required',   
            'payment' => 'required',   
        ]);
        try{  

            $prescription = new Prescription();
            $prescription->patients_id = $request->pid;
            $prescription->p_note = $request->pnote;   
            $prescription->save();

            $payment['patients_id'] = $request->pid;  
            $payment['prescriptions_id'] = $prescription->id; 
            $payment['payment'] = $request->payment;  

            $result = Payment::create($payment);
 
            if ($result) {
                $respose['status'] = 200; 
            }
             else { 
                $respose['status'] = 500;
            }
            echo json_encode($respose);

        } catch (Exception $ex) {
            return $ex;
        }
    }
}
