<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = request()->query('status', 'Active');

        $tenantsQuery = Tenant::with(['room', 'invoices']);
        $movedOutTenants = Tenant::where('status', 'Moved Out')->get();
        
        if ($status === 'Moved Out') {
            $tenantsQuery->where('status', 'Moved Out');
        } else {
            // Default to showing only active tenants
            $status = 'Active';
            $tenantsQuery->where('status', 'Active');
        }

        $tenants = $tenantsQuery->latest()->get();

        return view('tenants.index', compact('tenants', 'status', 'movedOutTenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $rooms = Room::where('is_available', true)->get();
    
    return view('tenants.create', compact('rooms'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:tenants,email',
        'phone' => 'required|string',
        'room_id' => 'required|exists:rooms,id',
        'base_rent' => 'required|numeric',
        'due_day' => 'required|integer|min:1|max:31',
        'lease_start' => 'required|date',
    ]);

    $room = Room::findOrFail($request->room_id);

    // Check if room is fully occupied
    if ($room->tenants()->count() >= $room->capacity) {
        return back()->withErrors(['room_id' => 'The selected room is fully occupied.'])->withInput();
    }

    Tenant::create($validated);

    // Update room availability based on capacity vs occupancy
    if ($room->tenants()->count() >= $room->capacity) {
        $room->update(['is_available' => false]);
    }

    return redirect()->route('tenants.index')->with('success', 'Tenant added and room assigned!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tenant = Tenant::with('invoices.items', 'invoices.payments')->findOrFail($id);
        return view('tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tenant = Tenant::findOrFail($id);
        $rooms = Room::all();
        return view('tenants.edit', compact('tenant', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tenant = Tenant::findOrFail($id);
        $originalRoom = $tenant->room;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email,' . $tenant->id,
            'phone' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'base_rent' => 'required|numeric',
            'due_day' => 'required|integer|min:1|max:31',
            'lease_start' => 'required|date',
            'status' => 'required|string|in:Active,Moved Out',
        ]);

        $tenant->update($validated);

        // Handle room change
        $newRoom = Room::find($validated['room_id']);
        if ($originalRoom && $originalRoom->id !== $newRoom->id) {
            // Decrement old room's occupancy
            if ($originalRoom->tenants()->count() < $originalRoom->capacity) {
                $originalRoom->update(['is_available' => true]);
            }

            // Increment new room's occupancy
            if ($newRoom->tenants()->count() >= $newRoom->capacity) {
                $newRoom->update(['is_available' => false]);
            }
        }
        
        if ($validated['status'] == 'Moved Out') {
            $tenant->update(['room_id' => null]);
            if ($originalRoom) {
                if ($originalRoom->tenants()->count() < $originalRoom->capacity) {
                    $originalRoom->update(['is_available' => true]);
                }
            }
        }


        return redirect()->route('tenants.index')->with('success', 'Tenant details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tenant = Tenant::findOrFail($id);
        $room = $tenant->room;

        // Handle Move Out
        $tenant->status = 'Moved Out';
        $tenant->room_id = null; // Detach from room to free up capacity
        $tenant->move_out_date = now();
        $tenant->save();

        // Update Room Availability
        if ($room) {
            if ($room->tenants()->count() < $room->capacity) {
                $room->update(['is_available' => true]);
            }
        }

        return back()->with('success', 'Tenant moved out successfully.');
    }
}
