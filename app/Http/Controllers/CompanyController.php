<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::where('is_active', 1)->orderBy('name', 'asc')->get();

        if ($company->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return response()->json($company, 200);
    }
}
