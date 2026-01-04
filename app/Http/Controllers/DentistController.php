<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DentistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dentists = Dentist::latest()->paginate(10);
        return view('Admin.dentist.index', compact('dentists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.dentist.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'specialization' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'years_of_experience' => $validated['years_of_experience'],
            'specialization' => $validated['specialization'],
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('dentists', 'public');
        }

        Dentist::create($data);

        return redirect()->route('dentists.index')
            ->with('success', 'Dentist created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dentist = Dentist::findOrFail($id);
        return view('Admin.dentist.show', compact('dentist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dentist = Dentist::findOrFail($id);
        return view('Admin.dentist.edit', compact('dentist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dentist = Dentist::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'specialization' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $validated['name'],
            'years_of_experience' => $validated['years_of_experience'],
            'specialization' => $validated['specialization'],
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($dentist->image_path) {
                Storage::disk('public')->delete($dentist->image_path);
            }
            $data['image_path'] = $request->file('image')->store('dentists', 'public');
        }

        $dentist->update($data);

        return redirect()->route('dentists.index')
            ->with('success', 'Dentist updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dentist = Dentist::findOrFail($id);
        
        // Delete image if exists
        if ($dentist->image_path) {
            Storage::disk('public')->delete($dentist->image_path);
        }

        $dentist->delete();

        return redirect()->route('dentists.index')
            ->with('success', 'Dentist deleted successfully.');
    }
}
