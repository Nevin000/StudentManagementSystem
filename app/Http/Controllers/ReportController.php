<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App;

class ReportController extends Controller
{
    public function report1($pid) {
        $payment = Payment::find($pid);
    $pdf = App::make('dompdf.wrapper');
    
    $print = "<div style='margin:20px; padding:20px;'>";
    $print .= "<h1 align='center'>Payment Report</h1>";
    $print .= "<hr/>";
    $print .= "<p> Receipt No: <b>" . $pid . "</b></p>";
    $print .= "<p> Date: <b>" . ($payment->payment_date ?? 'N/A') . "</b></p>";
    $print .= "<p> Amount: <b>" . ($payment->amount ?? 'N/A') . "</b></p>";

    // Check if enrollment and student exist
    $enrollment = $payment->enrollment;
    $studentName = $enrollment && $enrollment->student ? $enrollment->student->name : 'N/A';
    $batchName = $enrollment && $enrollment->batch ? $enrollment->batch->name : 'N/A';

    $print .= "<p> Student Name: <b>" . $studentName . "</b></p>";
    $print .= "<hr/>";

    $print .= "<table style='width:100%;'>";
    $print .= "<tr><td>Description</td><td>Amount</td></tr>";
    $print .= "<tr><td><h3>" . $batchName . "</h3></td>";
    $print .= "<td><h3>" . ($payment->amount ?? 'N/A') . "</h3></td></tr>";
    $print .= "</table>";
    $print .= "<hr/>";

    $print .= "<span>Printed By: <b>" . (Auth::user()->name ?? 'N/A') . "</b></span>";
    $print .= "<span>Printed Date: <b>" . date('d-m-y') . "</b></span>";
    $print .= "</div>";

    $pdf->loadHTML($print);
    return $pdf->stream('report.pdf');
    }
}
