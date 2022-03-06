<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;


use DB;

class BackPatientController extends Controller
{
    //add patient index
    public function add_index()
    {
        $data['content'] = 'backend/patients/add_index';
        return view('backend/template/template', $data);
    }
    public function addPatient(Request $request)
    { 
        $request->validate([
            'name' => 'required|min:2',   
            'bdate' => 'required',   
            'contactno' => 'required|max:10',  
            'nic' => 'required|min:10',   
        ]);
         
            

            if (!Patient::where('nic', '=', request()->nic)->exists()) {
 
                try{
                    $patientImage = 'main_' . uniqid() . '_' . time() . '.' . request()->mainImg->extension();  
                        if(request()->mainImg->move(public_path('assets/backend/main/patients'), $patientImage)){
                            
                            $patient = new Patient(); 
                            $patient->name = $request->name;
                            $patient->birthday = $request->bdate;   
                            $patient->contact_no = $request->contactno;
                            $patient->photo = $patientImage;
                            $patient->nic = $request->nic;
                            $patient->notes = $request->note;

                            $patient->save();
            
                            return redirect()->back() ->with('alert', 'Sucessfullly Added !');

                        } else {
                            $response['status'] = 500;
                            $response['message'] = 'Operation Failed!!';
                            $response['alert'] = 'Operation Failed!';

                        } 
                    }
                    catch(Exception $e){
                        return back()->with('failed',"operation failed");
                    }
            }else{ 
                return redirect()->back() ->with('alert', 'Already Exists..!');
            }
      
    }

    

    
    
}
