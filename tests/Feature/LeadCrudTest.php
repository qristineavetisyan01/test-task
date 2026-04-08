<?php

namespace Tests\Feature;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_index_renders_successfully(): void
    {
        $response = $this->get(route('leads.index'));

        $response->assertOk();
        $response->assertSee('Leads Management Dashboard');
    }

    public function test_user_can_create_lead(): void
    {
        $payload = [
            'name' => 'John Carter',
            'email' => 'john.carter@example.com',
            'phone' => '+1 202-555-0199',
            'company' => 'Acme Corp',
            'status' => 'new',
            'notes' => 'Potential enterprise account.',
        ];

        $response = $this->post(route('leads.store'), $payload);

        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseHas('leads', [
            'email' => 'john.carter@example.com',
            'status' => 'new',
        ]);
    }

    public function test_user_can_update_lead(): void
    {
        $lead = Lead::create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'status' => 'new',
        ]);

        $response = $this->put(route('leads.update', $lead), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '+1 202-555-0111',
            'company' => 'Updated Inc',
            'status' => 'qualified',
            'notes' => 'Requested proposal.',
        ]);

        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'name' => 'Updated Name',
            'status' => 'qualified',
        ]);
    }

    public function test_user_can_delete_lead(): void
    {
        $lead = Lead::create([
            'name' => 'Delete Me',
            'email' => 'deleteme@example.com',
            'status' => 'lost',
        ]);

        $response = $this->delete(route('leads.destroy', $lead));

        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseMissing('leads', ['id' => $lead->id]);
    }

    public function test_search_and_status_filter_work_together(): void
    {
        Lead::create([
            'name' => 'Alice New',
            'email' => 'alice@example.com',
            'status' => 'new',
        ]);

        Lead::create([
            'name' => 'Alice Qualified',
            'email' => 'alice2@example.com',
            'status' => 'qualified',
        ]);

        $response = $this->get(route('leads.index', [
            'search' => 'Alice',
            'status' => 'qualified',
        ]));

        $response->assertOk();
        $response->assertSee('Alice Qualified');
        $response->assertDontSee('Alice New');
    }

    public function test_lead_validation_rejects_invalid_payload(): void
    {
        $response = $this->post(route('leads.store'), [
            'name' => 'Jo',
            'email' => 'not-an-email',
            'phone' => 'bad',
            'status' => 'invalid',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'phone', 'status']);
    }
}
