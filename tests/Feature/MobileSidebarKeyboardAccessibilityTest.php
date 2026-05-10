<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class MobileSidebarKeyboardAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that mobile sidebar has Escape key handler for closing
     *
     * @return void
     */
    public function test_mobile_sidebar_has_escape_key_handler()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'role' => 'user'
        ]);

        $this->actingAs($user);

        // Request the dashboard page
        $response = $this->get(route('dashboard'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the mobile sidebar contains the Escape key handler
        $response->assertSee('@keydown.escape.window="closeMobileMenu()"', false);
    }

    /**
     * Test that mobile sidebar close button is keyboard accessible
     *
     * @return void
     */
    public function test_mobile_sidebar_close_button_exists()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'role' => 'user'
        ]);

        $this->actingAs($user);

        // Request the dashboard page
        $response = $this->get(route('dashboard'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the close button exists with the closeMobileMenu() handler
        $response->assertSee('@click="closeMobileMenu()"', false);
    }

    /**
     * Test that mobile sidebar overlay closes menu on click
     *
     * @return void
     */
    public function test_mobile_sidebar_overlay_closes_on_click()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'role' => 'user'
        ]);

        $this->actingAs($user);

        // Request the dashboard page
        $response = $this->get(route('dashboard'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert the overlay has click handler to close menu
        $response->assertSee('bg-gray-900/70', false);
        $response->assertSee('@click="closeMobileMenu()"', false);
    }

    /**
     * Test that mobile sidebar has proper z-index layering
     *
     * @return void
     */
    public function test_mobile_sidebar_has_correct_z_index()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'role' => 'user'
        ]);

        $this->actingAs($user);

        // Request the dashboard page
        $response = $this->get(route('dashboard'));

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert overlay has z-index 100
        $response->assertSee('z-[100]', false);
        
        // Assert sidebar content has z-index 110
        $response->assertSee('z-[110]', false);
    }
}
