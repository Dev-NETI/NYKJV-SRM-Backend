<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Exception;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $department = Department::with(['company'])
                ->where('is_active', 1)
                ->orderBy('name', 'asc')
                ->get();

            if ($department->isEmpty()) {
                return response()->json(['message' => 'No data found'], 404);
            }

            return response()->json($department, 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'companyId' => 'required',
            'departmentName' => 'required',

        ]);
        try {
            Department::create([
                'company_id' => $request['companyId'],
                'name' => $request['departmentName']
            ]);

            return response()->json(['response' => true, 'message' => 'Department created successfully!'], 200);
        } catch (Exception $e) {
            return response()->json(['response' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function getAllDepartment()
    {
        try {
            $department = Department::with(['company'])
                ->orderBy('name', 'asc')
                ->get();

            if ($department->isEmpty()) {
                return response()->json(['message' => 'No data found'], 404);
            }

            return response()->json($department, 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function updateDepartment(Request $request)
    {
        try {
            $departmentData = Department::where('id', $request['departmentId'])->first();

            $departmentData->update([
                'company_id' => $request['companyId'],
                'name' => $request['name']
            ]);

            return response()->json(['response' => true, 'message' => 'Department updated successfully!'], 200);
        } catch (Exception $e) {
            return response()->json(['response' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function handleActivation(Request $request)
    {
        try {
            $departmentData = Department::where('id', $request['departmentId'])->first();

            $departmentData->update([
                'is_active' => $request['isActive'],
            ]);

            return response()->json(['response' => true, 'message' => 'Department updated successfully!'], 200);
        } catch (Exception $e) {
            return response()->json(['response' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
