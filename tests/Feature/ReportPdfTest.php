<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class ReportPdfTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin()
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_report_pdf_routes_require_authentication()
    {
        $pdfRoutes = [
            '/reports/plantings/pdf',
            '/reports/harvests/pdf',
            '/reports/transactions/pdf',
            '/reports/materials/pdf',
        ];

        foreach ($pdfRoutes as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_admin_can_access_report_pdf_endpoints()
    {
        $admin = $this->createAdmin();

        $pdfRoutes = [
            '/reports/plantings/pdf',
            '/reports/harvests/pdf',
            '/reports/transactions/pdf',
            '/reports/materials/pdf',
        ];

        foreach ($pdfRoutes as $url) {
            $response = $this->actingAs($admin)->get($url);
            $response->assertStatus(200);
            $response->assertHeader('content-type', 'application/pdf');
        }
    }

    public function test_admin_can_download_report_pdf_with_parameter()
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get('/reports/plantings/pdf?download=1');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition'));
    }
}

