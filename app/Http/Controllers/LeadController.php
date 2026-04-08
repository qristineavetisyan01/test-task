<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function __construct(private readonly LeadService $leadService) {}

    public function index(Request $request): View|JsonResponse
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
        ];

        $leads = $this->leadService->paginated($filters);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('leads._table', compact('leads'))->render(),
            ]);
        }

        return view('leads.index', compact('leads', 'filters'));
    }

    public function create(): View
    {
        return view('leads.index', [
            'leads' => $this->leadService->paginated(['search' => '', 'status' => '']),
            'filters' => ['search' => '', 'status' => ''],
        ]);
    }

    public function store(StoreLeadRequest $request): RedirectResponse|JsonResponse
    {
        $lead = Lead::create($request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Lead created successfully.',
                'lead' => $lead,
            ]);
        }

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead created successfully.');
    }

    public function edit(Lead $lead): RedirectResponse|JsonResponse
    {
        if (request()->expectsJson()) {
            return response()->json(['lead' => $lead]);
        }

        return redirect()->route('leads.index');
    }

    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse|JsonResponse
    {
        $lead->update($request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Lead updated successfully.',
                'lead' => $lead->fresh(),
            ]);
        }

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Request $request, Lead $lead): JsonResponse|RedirectResponse
    {
        $lead->delete();

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Lead deleted successfully.',
            ]);
        }

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }
}
