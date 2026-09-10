<?php

declare(strict_types=1);

use Domain\User\Entities\User;
use Domain\User\Enums\UserStatus;
use Domain\WebsiteBuilder\Entities\Page;
use Domain\WebsiteBuilder\Entities\Website;
use Domain\WebsiteBuilder\Enums\PageStatus;
use Domain\WebsiteBuilder\Enums\WebsiteStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

test('guest can view login and register pages', function (): void {
    $this->get(route('login'))->assertStatus(200)->assertSee('Đăng Nhập TechHub');
    $this->get(route('register'))->assertStatus(200)->assertSee('Tạo Tài Khoản Mới');
});

test('guest can register a new account and is redirected to builder', function (): void {
    $response = $this->post(route('register.post'), [
        'name' => 'Nguyen Van A',
        'email' => 'nguyenvana@techhub.local',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertRedirect(route('builder.index'));
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'nguyenvana@techhub.local',
        'role' => 'user',
    ]);
});

test('user cannot register with duplicate email or mismatched password', function (): void {
    User::factory()->create(['email' => 'existing@techhub.local']);

    $response = $this->post(route('register.post'), [
        'name' => 'Nguyen Van B',
        'email' => 'existing@techhub.local',
        'password' => 'secret123',
        'password_confirmation' => 'wrongpass',
    ]);

    $response->assertSessionHasErrors(['email', 'password']);
    $this->assertGuest();
});

test('user can login with valid credentials', function (): void {
    $user = User::factory()->create([
        'email' => 'activeuser@techhub.local',
        'password' => bcrypt('password123'),
        'status' => UserStatus::Active,
    ]);

    $response = $this->post(route('login.post'), [
        'email' => 'activeuser@techhub.local',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('builder.index'));
    $this->assertAuthenticatedAs($user);
});

test('user cannot login with invalid credentials', function (): void {
    User::factory()->create([
        'email' => 'activeuser@techhub.local',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post(route('login.post'), [
        'email' => 'activeuser@techhub.local',
        'password' => 'wrongpassword',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

test('authenticated user can logout successfully', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect('/');
    $this->assertGuest();
});

test('unauthenticated guest is redirected to login when visiting builder dashboard', function (): void {
    $response = $this->get(route('builder.index'));

    $response->assertRedirect(route('login'));
});

test('unauthenticated guest is redirected to login when visiting builder editor', function (): void {
    $response = $this->get('/builder/editor/1');

    $response->assertRedirect(route('login'));
});

test('builder dashboard only displays websites owned by authenticated user', function (): void {
    $userA = User::factory()->create(['name' => 'User A']);
    $userB = User::factory()->create(['name' => 'User B']);

    $siteA = Website::create([
        'ulid' => (string) Str::ulid(),
        'user_id' => $userA->id,
        'name' => 'Website of User A',
        'subdomain' => 'site-a-' . time(),
        'status' => WebsiteStatus::DRAFT,
    ]);

    $siteB = Website::create([
        'ulid' => (string) Str::ulid(),
        'user_id' => $userB->id,
        'name' => 'Website of User B',
        'subdomain' => 'site-b-' . time(),
        'status' => WebsiteStatus::DRAFT,
    ]);

    // User A should only see Site A
    $responseA = $this->actingAs($userA)->get(route('builder.index'));
    $responseA->assertStatus(200);
    $responseA->assertSee('Website of User A');
    $responseA->assertDontSee('Website of User B');

    // User B should only see Site B
    $responseB = $this->actingAs($userB)->get(route('builder.index'));
    $responseB->assertStatus(200);
    $responseB->assertSee('Website of User B');
    $responseB->assertDontSee('Website of User A');
});

test('user cannot open editor for a page belonging to another user', function (): void {
    $userA = User::factory()->create(['name' => 'User A']);
    $userB = User::factory()->create(['name' => 'User B']);

    $siteA = Website::create([
        'ulid' => (string) Str::ulid(),
        'user_id' => $userA->id,
        'name' => 'Website of User A',
        'subdomain' => 'site-a-editor-' . time(),
        'status' => WebsiteStatus::DRAFT,
    ]);

    $pageA = Page::create([
        'ulid' => (string) Str::ulid(),
        'website_id' => $siteA->id,
        'title' => 'Home of A',
        'slug' => 'home',
        'is_home' => true,
        'draft_content' => ['gjs_html' => '<div>Content A</div>', 'gjs_css' => ''],
        'version_number' => 1,
        'status' => PageStatus::DRAFT,
    ]);

    // User A can open their own editor
    $this->actingAs($userA)->get("/builder/editor/{$pageA->id}")->assertStatus(200);

    // User B cannot open User A's editor and gets 403 Forbidden
    $this->actingAs($userB)->get("/builder/editor/{$pageA->id}")->assertStatus(403);
});

test('user cannot autosave or update a page belonging to another user', function (): void {
    $userA = User::factory()->create();
    $userB = User::factory()->create();

    $siteA = Website::create([
        'ulid' => (string) Str::ulid(),
        'user_id' => $userA->id,
        'name' => 'Site A',
        'subdomain' => 'site-a-api-' . time(),
        'status' => WebsiteStatus::DRAFT,
    ]);

    $pageA = Page::create([
        'ulid' => (string) Str::ulid(),
        'website_id' => $siteA->id,
        'title' => 'Home of A',
        'slug' => 'home',
        'is_home' => true,
        'draft_content' => ['gjs_html' => '<div>Content A</div>'],
        'version_number' => 1,
        'status' => PageStatus::DRAFT,
    ]);

    // User B attempts to autosave User A's page
    $response = $this->actingAs($userB)->postJson("/api/builder/pages/{$pageA->id}/autosave", [
        'base_version' => 1,
        'content_json' => ['gjs_html' => '<div>Hacked Content</div>'],
    ]);

    $response->assertStatus(403);
});
