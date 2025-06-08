<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class DataPemesananAdminController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');
    
        $transactions = Transaction::with(['detailTransactions.order', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where('id', 'like', "%$search%")
                            ->orWhere('id_user', 'like', "%$search%")
                            ->orWhereHas('detailTransactions.order', function ($q) use ($search) {
                                $q->where('id', 'like', "%$search%");
                            });
            })
            ->orderBy('created_at', 'desc') // sebelum paginate()
            ->paginate($perPage);

        return view('admin.data-pemesanan', compact('transactions'));
    }
}