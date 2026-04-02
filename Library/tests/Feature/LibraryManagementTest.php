<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LibraryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_assigns_member_role(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $response = $this->post(route('users_register_save'), [
            'name' => 'New Member',
            'email' => 'newmember@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('users_profile'));
        $this->assertDatabaseHas('users', ['email' => 'newmember@example.com']);
        $this->assertTrue(User::where('email', 'newmember@example.com')->first()->hasRole('Member'));
    }

    public function test_member_cannot_borrow_out_of_stock_book(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $member = User::create([
            'name' => 'Borrower',
            'email' => 'borrower@example.com',
            'password' => bcrypt('password'),
        ]);
        $member->assignRole('Member');

        $book = Book::create([
            'title' => 'Unavailable Book',
            'author' => 'Test Author',
            'isbn' => 'ISBN-0001',
            'copies' => 0,
        ]);

        $response = $this->actingAs($member)->post(route('borrow_save', $book->id));

        $response->assertRedirect(route('books_list'));
        $response->assertSessionHas('error', 'Book Currently Unavailable');
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'copies' => 0,
        ]);
        $this->assertDatabaseCount('borrows', 0);
    }

    public function test_admin_can_update_member_password(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin2@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('Admin');

        $member = User::create([
            'name' => 'Member User',
            'email' => 'member2@example.com',
            'password' => bcrypt('old-password'),
        ]);
        $member->assignRole('Member');

        $response = $this->actingAs($admin)->post(route('users_member_password_save', $member->id), [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect(route('users_list'));
        $member->refresh();
        $this->assertTrue(Hash::check('new-password', $member->password));
    }

    public function test_login_is_blocked_for_fifteen_seconds_after_three_failed_attempts(): void
    {
        RateLimiter::clear('member@library.com|127.0.0.1');

        for ($i = 0; $i < 3; $i++) {
            $response = $this->from(route('users_login'))->post(route('users_authenticate'), [
                'email' => 'member@library.com',
                'password' => 'wrong-password',
            ]);

            $response->assertRedirect(route('users_login'));
            $response->assertSessionHasErrors('email');
        }

        $blockedResponse = $this->from(route('users_login'))->post(route('users_authenticate'), [
            'email' => 'member@library.com',
            'password' => 'wrong-password',
        ]);

        $blockedResponse->assertRedirect(route('users_login'));
        $blockedResponse->assertSessionHasErrors('email');
        $this->assertStringContainsString(
            'Too many login attempts. Please try again after',
            session('errors')->first('email')
        );
    }
}
