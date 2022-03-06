<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use DB;
use PDF;
use Carbon\Carbon;



class BackReportController extends Controller
{
    public function index()
    {
        $data['content'] = 'backend/patients/report';
        $data['get_prescriptions'] = Patient::all();

        return view('backend/template/template', $data);
    }

    function fetch_report(Request $request)
    {
     if($request->ajax())
     {
         
      if($request->from_date != '' && $request->to_date != '')
      {
        
            $data = DB::table('payments')->join('patients','payments.patients_id','=','patients.id')
            ->join('prescriptions','payments.prescriptions_id','=','prescriptions.id')
            ->select('payments.id','patients.name','patients.birthday','patients.contact_no','patients.photo','patients.nic','payments.payment','prescriptions.p_note','payments.created_at')
            ->whereBetween('prescriptions.created_at', array($request->from_date, $request->to_date)) 
            ->where('prescriptions.patients_id','=',$request->pid)
            ->orderBy('prescriptions.created_at', 'asc')
            ->get();
          
           
      }
      else
      {
        $data = DB::table('payments')->join('patients','payments.patients_id','=','patients.id')
        ->join('prescriptions','payments.prescriptions_id','=','prescriptions.id')
        ->select('payments.id','patients.name','patients.birthday','patients.contact_no','patients.photo','patients.nic','payments.payment','prescriptions.p_note','payments.created_at')
        ->orderBy('prescriptions.created_at', 'asc')
        ->get();
      }
      echo json_encode($data);
     }
    }

    function pdf_report(Request $request)
    {
        $pdf = \App::make('dompdf.wrapper');
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $pid = $request->pid;

        $pdf->loadHTML($this->convert_pdf_report_data_to_html($pid,$from_date,$to_date));
         
        return $pdf->stream();
      
    }
 
    function convert_pdf_report_data_to_html($pid,$from_date,$to_date)
    {
         $today = Carbon::now();
      if($from_date != '' && $to_date != ''){
        if($pid != ''){
            $data = DB::table('payments')->join('patients','payments.patients_id','=','patients.id')
            ->join('prescriptions','payments.prescriptions_id','=','prescriptions.id')
            ->select('payments.id','patients.name','patients.birthday','patients.contact_no','patients.photo','patients.nic','payments.payment','prescriptions.p_note','payments.created_at')
            ->whereBetween('prescriptions.created_at', array($from_date, $to_date)) 
            ->where('prescriptions.patients_id','=',$pid)
            ->orderBy('prescriptions.created_at', 'asc')
            ->get();
          }

          else{
            $data = DB::table('payments')->join('patients','payments.patients_id','=','patients.id')
            ->join('prescriptions','payments.prescriptions_id','=','prescriptions.id')
            ->select('payments.id','patients.name','patients.birthday','patients.contact_no','patients.photo','patients.nic','payments.payment','prescriptions.p_note','payments.created_at')
            ->whereBetween('prescriptions.created_at', array($from_date, $to_date)) 
            ->orderBy('prescriptions.created_at', 'asc')
            ->get();
          }
      }

      else{

      $from_date = "--/--/----";
      $to_date = "--/--/----";

      $data = DB::table('payments')->join('patients','payments.patients_id','=','patients.id')
      ->join('prescriptions','payments.prescriptions_id','=','prescriptions.id')
      ->select('payments.id','patients.name','patients.birthday','patients.contact_no','patients.photo','patients.nic','payments.payment','prescriptions.p_note','payments.created_at')
      ->orderBy('prescriptions.created_at', 'asc')
      ->get();
 
      }
      
            $output ='<head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Generate Reports</title>
        
        
        
        </head>
        <body>
            <center><h4>Dr. John`s Clinic Center
             <h3> REPORT</h3></h4> 
                 <h5>'.$from_date.'- 00:00 &nbsp; To &nbsp;'.$to_date.' - 00:00</h5>
            </center>

            
 
            <BR>
            <table style=" border: 1px solid black; width: 100%; border-collapse: collapse;">
            <tr>
            <th style=" border: 1px solid black;">DATE</th>
            <th style=" border: 1px solid black;">Name</th>
            <th style=" border: 1px solid black;">Date of Brith</th>
            <th style=" border: 1px solid black;">Contact No</th>
            <th style=" border: 1px solid black;">NIC</th>
            <th style=" border: 1px solid black;">Prescriptions</th>
            <th style=" border: 1px solid black;">Payment</th> 
          </tr>
          <tbody>
          ';
          foreach($data as $data){
            $output.='

            <tr>
              <td style=" border: 1px solid black;">'.$data->created_at.'</td>
              <td style=" border: 1px solid black;">'.$data->name.'</td>
              <td style=" border: 1px solid black;">'.$data->birthday.'</td>
              <td style=" border: 1px solid black;">'.$data->contact_no.'</td>
              <td style=" border: 1px solid black;">'.$data->nic.'</td>
              <td style=" border: 1px solid black;">'.$data->p_note.'</td>
              <td style=" border: 1px solid black;">'.$data->payment.'</td> 

            </tr>

                ';}
                $output.=

                '</tbody>
 
            </table>

            

              <center><h6>This document generated on '.$today->format('Y-m-d H:i:s').'</h6> <p>by W.S.A.Kurera</p></center>

        </body>
        </html>  
         
            ';  
 
        return $output;
    }
}
