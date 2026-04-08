<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Lead::with(['status', 'source', 'assignedUser'])->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->integer('status_id'));
        }

        $leads = $query->paginate(10)->withQueryString();

        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'leads' => $leads,
            ]);
        }

        return view('leads.index', [
            'leads' => $leads,
            'statuses' => LeadStatus::orderBy('order_index')->get(),
            'sources' => LeadSource::orderBy('name')->get(),
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'success' => true,
        ]);
    }

    public function store(StoreLeadRequest $request): JsonResponse
    {
        $lead = Lead::create([
            ...$request->validated(),
            'assigned_to' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully.',
            'lead' => $lead->load(['status', 'source', 'assignedUser']),
        ]);
    }

    public function edit(Lead $lead): JsonResponse
    {
        return response()->json([
            'success' => true,
            'lead' => $lead->load(['status', 'source', 'assignedUser']),
        ]);
    }

    public function show(Lead $lead): JsonResponse
    {
        return response()->json([
            'success' => true,
            'lead' => $lead->load(['status', 'source', 'assignedUser', 'activities']),
        ]);
    }

    public function update(UpdateLeadRequest $request, Lead $lead): JsonResponse
    {
        $lead->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully.',
            'lead' => $lead->fresh()->load(['status', 'source', 'assignedUser']),
        ]);
    }

    public function destroy(Lead $lead): JsonResponse
    {
        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead deleted successfully.',
        ]);
    }
}
