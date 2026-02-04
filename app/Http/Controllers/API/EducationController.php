<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users\UserEducation;
use App\Http\Controllers\Controller;

class EducationController extends Controller
{
    // API: List all education
    public function apiIndex()
    {
        $educations = UserEducation::where('user_id', Auth::id())->orderByDesc('id')->get();
        return response()->json(['success' => true, 'data' => $educations]);
    }

    // API: Show single education by ID
    public function apiShow($id)
    {
        $education = UserEducation::where('user_id', Auth::id())->find($id);

        if (!$education) {
            return response()->json(['success' => false, 'message' => 'Education not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => $education]);
    }

    // API: Store or update an education
    public function apiStore(Request $request, $id = null)
    {
        $request->validate([
            'college_name' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'year_graduated' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        if ($id) {
            $education = UserEducation::where('user_id', Auth::id())->find($id);

            if (!$education) {
                return response()->json(['success' => false, 'message' => 'Education not found.'], 404);
            }

            $education->update([
                'college_university' => $request->college_name,
                'degree_diploma' => $request->degree,
                'year' => $request->year_graduated,
            ]);

            return response()->json(['success' => true, 'message' => 'Education updated successfully.', 'data' => $education]);
        } else {
            $education = UserEducation::create([
                'user_id' => Auth::id(),
                'college_university' => $request->college_name,
                'degree_diploma' => $request->degree,
                'year' => $request->year_graduated,
            ]);

            return response()->json(['success' => true, 'message' => 'Education added successfully.', 'data' => $education]);
        }
    }

    // API: Delete an education
    public function apiDelete($id)
    {
        $education = UserEducation::where('user_id', Auth::id())->find($id);

        if (!$education) {
            return response()->json(['success' => false, 'message' => 'Education not found.'], 404);
        }

        $education->delete();

        return response()->json(['success' => true, 'message' => 'Education deleted successfully.']);
    }
}
