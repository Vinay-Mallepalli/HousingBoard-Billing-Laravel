<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HousingData;
use illuminate\Support\Facades\Log;
use illuminate\Support\Facades\DB;

class SuperAdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $housings = HousingData::all(); 
        $housingId = $request->input('housingId');
        $housingName = $request->input('housingName');
        return view('housing.dashboard', compact('housings', 'housingId', 'housingName'));
    }
    
}
