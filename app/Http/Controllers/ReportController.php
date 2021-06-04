<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceDetail;

class ReportController extends Controller
{
    public function index(){
        $report = InvoiceDetail::with('product')->select('product_id', 'product_type')->get();
        return view('report.index', compact('report'));
    }
}
