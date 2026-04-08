<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(): View
    {
        return view('activities.index', [
            'activities' => Activity::with(['lead', 'user'])->latest()->paginate(10),
            'leads' => Lead::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lead_id' => ['required', 'exists:leads,id'],
            'type' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
        ]);

        Activity::create([
            ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity added successfully.');
    }

    public function destroy(Activity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity deleted successfully.');
    }
}
