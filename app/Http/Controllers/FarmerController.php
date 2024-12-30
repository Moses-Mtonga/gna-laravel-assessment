<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function index()
    {
        $farmers = Farmer::all();
        return view('dashboard', compact('farmers'));
    }

    // Handle an incoming farmer creation request.
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        try {
            Farmer::create($request->all());
            return redirect()->route('dashboard')->with('success', 'Farmer created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Failed to create farmer.');
        }
    }

    //Handle an incoming farmer update request.
    public function update(Request $request, Farmer $farmer)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        try {
            $farmer->update($request->all());
            return redirect()->route('dashboard')->with('success', 'Farmer updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Failed to update farmer.');
        }
    }

    public function destroy(Farmer $farmer)
    {
        try {
            $farmer->delete();
            return redirect()->route('dashboard')->with('success', 'Farmer deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Failed to delete farmer.');
        }
    }
}
