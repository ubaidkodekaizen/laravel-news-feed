<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Users\UserEducation;

class EducationController extends Controller
{
    /**
     * Display a list of user education records.
     */
    public function index()
    {
        $educations = UserEducation::where('user_id', Auth::id())->get();
        return view('user.user-qualifications', compact('educations'));
    }

    /**
     * Show the form for adding or editing an education record.
     */
    public function addEditEducation($id = null)
    {
        $education = $id ? UserEducation::findOrFail($id) : null;

        // Ensure the user owns the education record
        if ($education && $education->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.add-qualification', compact('education'));
    }

    /**
     * Store or update an education record.
     */
    public function storeEducation(Request $request, $id = null)
    {
        $request->validate([
            'college_name' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'year_graduated' => 'required|integer|min:1900|max:' . date('Y'),
        ]);

        if ($id) {
            $education = UserEducation::findOrFail($id);
            
         
            if ($education->user_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $education->update([
                'college_university' => $request->college_name,
                'degree_diploma' => $request->degree,
                'year' => $request->year_graduated,
            ]);

            return redirect()->route('user.qualifications')->with('success', 'Education updated successfully.');
        } else {
            UserEducation::create([
                'user_id' => Auth::id(),
                'college_university' => $request->college_name,
                'degree_diploma' => $request->degree,
                'year' => $request->year_graduated,
            ]);

            return redirect()->route('user.qualifications')->with('success', 'Education added successfully.');
        }
    }

    /**
     * Delete an education record.
     */
    public function deleteEducation($id)
    {
        $education = UserEducation::findOrFail($id);
        
        // Ensure the user owns the education record
        if ($education->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $education->delete();
        return redirect()->route('user.qualifications')->with('success', 'Education deleted successfully.');
    }
}
