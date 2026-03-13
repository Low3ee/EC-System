<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Utility;
use Illuminate\Http\Request;

class RoomUtilityController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Room $room)
    {
        $validated = $request->validate([
            'utility_id' => 'required|exists:utilities,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        if ($room->utilities()->where('utility_id', $validated['utility_id'])->exists()) {
            return back()->with('error', 'This utility is already assigned to this room.');
        }

        $room->utilities()->attach($validated['utility_id'], [
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Utility added to room successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room, Utility $utility)
    {
        $room->utilities()->detach($utility->id);

        return back()->with('success', 'Utility removed from room successfully.');
    }
}