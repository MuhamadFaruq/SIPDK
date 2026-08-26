<?php

namespace Tests\Feature;

use App\Models\Letter;
use App\Models\OutgoingLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemFullAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all_routes_accessible_for_admin_without_errors()
    {
        $admin = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->first();
        $this->actingAs($admin);

        $routesToTest = [
            'dashboard',
            'letters.index',
            'letters.create',
            'outgoing-letters.index',
            'outgoing-letters.create',
            'dispositions.index',
            'archive.index',
            'reports.index',
            'audit-logs.index',
            'master.users',
            'master.categories',
        ];

        foreach ($routesToTest as $routeName) {
            $response = $this->get(route($routeName));
            $this->assertEquals(200, $response->getStatusCode(), "Route {$routeName} failed with status " . $response->getStatusCode());
        }

        // Test letter show & print
        $letter = Letter::first();
        if ($letter) {
            $this->get(route('letters.show', $letter->id))->assertStatus(200);
            $this->get(route('letters.print-agenda', $letter->id))->assertStatus(200);
            $this->get(route('dispositions.print-sheet', $letter->id))->assertStatus(200);
        }
    }

    public function test_all_routes_accessible_for_pimpinan_without_errors()
    {
        $pimpinan = User::whereHas('role', fn($q) => $q->where('name', 'pimpinan'))->first();
        $this->actingAs($pimpinan);

        $routesToTest = [
            'dashboard',
            'letters.index',
            'outgoing-letters.index',
            'dispositions.index',
            'archive.index',
            'reports.index',
        ];

        foreach ($routesToTest as $routeName) {
            $response = $this->get(route($routeName));
            $this->assertEquals(200, $response->getStatusCode(), "Route {$routeName} failed for pimpinan with status " . $response->getStatusCode());
        }

        $letter = Letter::first();
        if ($letter) {
            $this->get(route('letters.show', $letter->id))->assertStatus(200);
            $this->get(route('letters.print-agenda', $letter->id))->assertStatus(200);
            $this->get(route('dispositions.print-sheet', $letter->id))->assertStatus(200);
        }
    }

    public function test_all_routes_for_pelaksana_work_and_properly_secured()
    {
        $pelaksana = User::whereHas('role', fn($q) => $q->where('name', 'pelaksana'))->first();
        $this->actingAs($pelaksana);

        // Dashboard & Dispositions should be 200
        $this->get(route('dashboard'))->assertStatus(200);
        $this->get(route('dispositions.index'))->assertStatus(200);

        // Letters index & Outgoing letters should redirect pelaksana to dispositions
        $this->get(route('letters.index'))->assertRedirect(route('dispositions.index'));
        $this->get(route('outgoing-letters.index'))->assertRedirect(route('dispositions.index'));

        // Master & Audit logs & Reports must redirect to dashboard with error message
        $this->get(route('reports.index'))->assertRedirect(route('dashboard'));
        $this->get(route('audit-logs.index'))->assertRedirect(route('dashboard'));
        $this->get(route('master.users'))->assertRedirect(route('dashboard'));
        $this->get(route('master.categories'))->assertRedirect(route('dashboard'));
    }
}
