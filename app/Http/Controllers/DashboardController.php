<?php

namespace App\Http\Controllers;

use App\Models\TransactionSales;
use App\Models\TransactionSalesDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function rupiah($number)
    {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }

    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $dailyIncome = TransactionSales::whereMonth('created_at', $currentMonth)->selectRaw('DAY(created_at) as day, SUM(total_bayar) as total')->groupBy('day')->orderBy('day')->get()->pluck('total', 'day')->toArray();
        // return $dailyIncome;
        $daysInMonth = Carbon::now()->daysInMonth;
        $incomeData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $incomeData[$day] = $dailyIncome[$day] ?? 0;
        }

        $data = [
            'totalUser' => count(User::all()),
            'totalTransactionThisMonth' => count(TransactionSales::whereMonth('created_at', Carbon::now()->month)->get()),
            'totalIncomeThisMonth' => $this->rupiah(TransactionSales::whereMonth('created_at', Carbon::now()->month)->sum('total_bayar')),
            'totalTransactionThisWeek' => TransactionSales::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),
            'totalIncomeThisWeek' => $this->rupiah(TransactionSales::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_bayar')),
            'incomeData' => $incomeData,
        ];
        // return $data;
        return view('dashboard', compact('data'));
    }
}
