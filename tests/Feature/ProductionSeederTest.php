<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_seeder_fails_when_admin_env_is_empty(): void
    {
        $this->app['env'] = 'production';
        putenv('ADMIN_EMAIL=');
        putenv('ADMIN_PASSWORD=');
        $_ENV['ADMIN_EMAIL'] = '';
        $_ENV['ADMIN_PASSWORD'] = '';
        $_SERVER['ADMIN_EMAIL'] = '';
        $_SERVER['ADMIN_PASSWORD'] = '';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Seeder produksi gagal: Variabel ADMIN_EMAIL dan ADMIN_PASSWORD wajib diisi pada .env di lingkungan production!');

        $seeder = new DatabaseSeeder;
        $seeder->run();
    }
}
