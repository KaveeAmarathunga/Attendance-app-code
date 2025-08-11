<?php

namespace App\Http\Controllers;

use App\Models\LeaveQuantity;
use Illuminate\Http\Request;

class LeaveQuantityController extends Controller
{
    /**
     * Display a listing of the leave quantities.
     */
    public function index()
    {
        $leaveQuantities = LeaveQuantity::all();
        return view('leave_quantities.index', compact('leaveQuantities'));
    }

    /**
     * Show the form for creating a new leave quantity.
     */
    public function create()
    {
        return view('leave_quantities.create');
    }

    /**
     * Store a newly created leave quantity in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string|max:20',
            'leaves_for_executive' => 'required|integer|min:0',
            'leaves_for_technicians' => 'required|integer|min:0',
            'leaves_for_topmanagement' => 'required|integer|min:0',
        ]);

        LeaveQuantity::create($request->all());

        return redirect()->route('leave-quantities.index')->with('success', 'Leave quantity created successfully.');
    }

    /**
     * Display the specified leave quantity.
     */
    public function show(LeaveQuantity $leaveQuantity)
    {
        return view('leave_quantities.show', compact('leaveQuantity'));
    }

    /**
     * Show the form for editing the specified leave quantity.
     */
    public function edit(LeaveQuantity $leaveQuantity)
    {
        return view('leave_quantities.edit', compact('leaveQuantity'));
    }

    /**
     * Update the specified leave quantity in storage.
     */
    public function update(Request $request, LeaveQuantity $leaveQuantity)
    {
        $request->validate([
            'leave_type' => 'required|string|max:20',
            'leaves_for_executive' => 'required|integer|min:0',
            'leaves_for_technicians' => 'required|integer|min:0',
            'leaves_for_topmanagement' => 'required|integer|min:0',
        ]);

        $leaveQuantity->update($request->all());

        return redirect()->route('leave-quantities.index')->with('success', 'Leave quantity updated successfully.');
    }

    /**
     * Remove the specified leave quantity from storage.
     */
    public function destroy(LeaveQuantity $leaveQuantity)
    {
        $leaveQuantity->delete();

        return redirect()->route('leave-quantities.index')->with('success', 'Leave quantity deleted successfully.');
    }
}
