<?php

namespace Tests\Feature;

use App\Http\Controllers\ActivityLogsController;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        Parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }


}
