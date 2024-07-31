<?php

namespace App\Http\Controllers;
use App\Models\HousingData;
use Illuminate\Http\Request;

use illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminDashboardController extends Controller
{
    public function manageHousing($housingId)
    {
        $title = 'Delete!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        
        $housings = HousingData::all();
        $housingDetails = HousingData::find($housingId);
        $sheetData = json_decode($housingDetails->sheet_data, true);

        // Create a collection from the sheet data
        $collection = collect($sheetData[0]);

        // Remove header row if needed
        $collection = $collection->slice(1);

        // Set current page and items per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10; // Adjust as necessary
        $currentPageItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        // Create paginator
        $paginatedItems = new LengthAwarePaginator($currentPageItems, $collection->count(), $perPage);
        $paginatedItems->setPath(route('admin.manageHousing', ['housingId' => $housingId]));

        

        return view('housing.manage-housing', compact('housings', 'paginatedItems', 'housingId', 'housingDetails'));
    }
}
