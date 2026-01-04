<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dentist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DentistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dentists = Dentist::latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $dentists,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'specialization' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'years_of_experience' => $request->years_of_experience,
            'specialization' => $request->specialization,
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('dentists', 'public');
        }

        $dentist = Dentist::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Dentist created successfully',
            'data' => $dentist,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dentist = Dentist::find($id);

        if (! $dentist) {
            return response()->json([
                'success' => false,
                'message' => 'Dentist not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $dentist,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        /** @var \App\Models\Dentist|null $dentist */
        $dentist = Dentist::find($id);

        if (! $dentist) {
            return response()->json([
                'success' => false,
                'message' => 'Dentist not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'specialization' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'years_of_experience' => $request->years_of_experience,
            'specialization' => $request->specialization,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($dentist->image_path) {
                Storage::disk('public')->delete($dentist->image_path);
            }
            $data['image_path'] = $request->file('image')->store('dentists', 'public');
        }

        $dentist->update($data);
        $dentist = $dentist->fresh();

        return response()->json([
            'success' => true,
            'message' => 'Dentist updated successfully',
            'data' => $dentist,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /** @var \App\Models\Dentist|null $dentist */
        $dentist = Dentist::find($id);

        if (! $dentist) {
            return response()->json([
                'success' => false,
                'message' => 'Dentist not found',
            ], 404);
        }

        // Delete image if exists
        if ($dentist->image_path) {
            Storage::disk('public')->delete($dentist->image_path);
        }

        $dentist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dentist deleted successfully',
        ], 200);
    }
}
