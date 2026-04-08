<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private LeadStatus $status;

    private LeadSource $source;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->status = LeadStatus::create([
            'name' => 'New',
            'order_index' => 1,
        ]);
        $this->source = LeadSource::create([
            'name' => 'Website',
        ]);
    }

    public function test_lead_index_renders_successfully(): void
    {
        $response = $this->actingAs($this->user)->get(route('leads.index'));

        $response->assertOk();
        $response->assertSee('Leads Management');
    }

    public function test_user_can_create_lead(): void
    {
        $payload = [
            'name' => 'John Carter',
            'email' => 'john.carter@example.com',
            'phone' => '+1 202-555-0199',
            'company' => 'Acme Corp',
            'status_id' => $this->status->id,
            'source_id' => $this->source->id,
        ];

        $response = $this->actingAs($this->user)
            ->postJson(route('leads.store'), $payload);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('leads', [
            'email' => 'john.carter@example.com',
            'status_id' => $this->status->id,
        ]);
    }

    public function test_user_can_update_lead(): void
    {
        $lead = Lead::create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'status_id' => $this->status->id,
        ]);

        $qualified = LeadStatus::create([
            'name' => 'Qualified',
            'order_index' => 2,
        ]);

        $response = $this->actingAs($this->user)->putJson(route('leads.update', $lead), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '+1 202-555-0111',
            'company' => 'Updated Inc',
            'status_id' => $qualified->id,
            'source_id' => $this->source->id,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'name' => 'Updated Name',
            'status_id' => $qualified->id,
        ]);
    }

    public function test_user_can_delete_lead(): void
    {
        $lead = Lead::create([
            'name' => 'Delete Me',
            'email' => 'deleteme@example.com',
            'status_id' => $this->status->id,
        ]);

        $response = $this->actingAs($this->user)->deleteJson(route('leads.destroy', $lead));

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('leads', ['id' => $lead->id]);
    }

    public function test_index_ajax_returns_leads_json(): void
    {
        $lead = Lead::create([
            'name' => 'Alice New',
            'email' => 'alice@example.com',
            'status_id' => $this->status->id,
            'source_id' => $this->source->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('leads.index'));

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $response->assertJsonFragment(['id' => $lead->id]);
        $response->assertJsonPath('leads.per_page', 10);
    }

    public function test_lead_validation_rejects_invalid_payload(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('leads.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'phone' => 'bad',
            'status_id' => null,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'phone', 'status_id']);
    }
}
