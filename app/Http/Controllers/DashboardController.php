<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Shipment;
use App\Models\Invoice;
use App\Models\Receivable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $activeShipments = Shipment::whereNotIn('status', ['completed', 'cancelled'])->count();
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->sum('grand_total');
        $overdueInvoices = Invoice::where('status', 'overdue')
            ->orWhere(function($query) {
                $query->where('status', 'sent')
                      ->where('due_date', '<', now());
            })->count();

        $recentActivities = DB::table('shipment_histories')
            ->join('shipments', 'shipment_histories.shipment_id', '=', 'shipments.id')
            ->join('users', 'shipment_histories.user_id', '=', 'users.id')
            ->select('shipment_histories.*', 'shipments.job_number', 'users.name as user_name')
            ->orderBy('shipment_histories.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalCustomers', 
            'activeShipments', 
            'monthlyRevenue', 
            'overdueInvoices',
            'recentActivities'
        ));
    }
}
