<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ResponsiveBreakpointBehaviorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that mobile sidebar has md:hidden class
     *
     * @return void
     */
    public function test_mobile_sidebar_has_md_hidden_class()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        // Visit the dashboard
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert the mobile sidebar has md:hidden class
        // Mobile sidebar should be hidden on medium screens and above
        $response->assertSee('md:hidden', false);
        
        // Verify mobile sidebar overlay has md:hidden
        $response->assertSee('z-[100] md:hidden', false);
        
        // Verify mobile sidebar content has md:hidden
        $response->assertSee('z-[110] md:hidden', false);
    }

    /**
     * Test that desktop sidebar has hidden md:flex class
     *
     * @return void
     */
    public function test_desktop_sidebar_has_hidden_md_flex_class()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        // Visit the dashboard
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert the desktop sidebar has hidden md:flex class
        // Desktop sidebar should be hidden on small screens and flex on medium+
        $response->assertSee('hidden md:flex', false);
    }

    /**
     * Test that hamburger button has md:hidden class
     *
     * @return void
     */
    public function test_hamburger_button_has_md_hidden_class()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        // Visit the dashboard
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert the hamburger button has md:hidden class
        // Hamburger button should only be visible on mobile
        $response->assertSee('md:hidden', false);
        
        // Verify hamburger button exists with mobile menu toggle
        $response->assertSee('@click="mobileMenuOpen = true"', false);
    }

    /**
     * Test that appShell function has handleResize method
     *
     * @return void
     */
    public function test_app_shell_has_handle_resize_method()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        // Visit the dashboard
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert the appShell function has handleResize method
        $response->assertSee('handleResize()', false);
        
        // Verify resize handler closes mobile menu at 768px breakpoint
        $response->assertSee('window.innerWidth >= 768', false);
        $response->assertSee('this.mobileMenuOpen = false', false);
    }

    /**
     * Test that resize event listener is added with debouncing
     *
     * @return void
     */
    public function test_resize_event_listener_with_debouncing()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        // Visit the dashboard
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert resize event listener is added
        $response->assertSee("window.addEventListener('resize'", false);
        
        // Verify debouncing with 100ms timeout
        $response->assertSee('setTimeout', false);
        $response->assertSee('100', false);
        
        // Verify handleResize is called
        $response->assertSee('this.handleResize()', false);
    }

    /**
     * Test that mobile menu state initializes to false
     *
     * @return void
     */
    public function test_mobile_menu_initializes_closed()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        // Visit the dashboard
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert mobileMenuOpen initializes to false
        $response->assertSee('mobileMenuOpen: false', false);
    }

    /**
     * Test that desktop collapse state uses localStorage
     *
     * @return void
     */
    public function test_desktop_collapse_uses_local_storage()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        // Visit the dashboard
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert localStorage is used for collapsed state
        $response->assertSee("localStorage.getItem('sidebarCollapsed')", false);
        $response->assertSee("localStorage.setItem('sidebarCollapsed'", false);
    }

    /**
     * Test that desktop sidebar width changes based on collapsed state
     *
     * @return void
     */
    public function test_desktop_sidebar_width_changes_on_collapse()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        // Visit the dashboard
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert desktop sidebar has dynamic width classes
        $response->assertSee(":class=\"collapsed ? 'w-[72px]' : 'w-64'\"", false);
    }

    /**
     * Test that mobile sidebar transitions are properly configured
     *
     * @return void
     */
    public function test_mobile_sidebar_has_proper_transitions()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        // Visit the dashboard
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert mobile sidebar overlay has fade transitions
        $response->assertSee('x-transition:enter="transition ease-out duration-300"', false);
        $response->assertSee('x-transition:leave="transition ease-in duration-200"', false);
        
        // Assert mobile sidebar content has slide transitions
        $response->assertSee('x-transition:enter-start="-translate-x-full"', false);
        $response->assertSee('x-transition:enter-end="translate-x-0"', false);
    }

    /**
     * Test that desktop sidebar has smooth transitions
     *
     * @return void
     */
    public function test_desktop_sidebar_has_smooth_transitions()
    {
        // Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $this->actingAs($user);

        // Visit the dashboard
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);

        // Assert desktop sidebar has transition classes
        $response->assertSee('transition-all duration-300', false);
    }
}
