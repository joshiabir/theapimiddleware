<?php

// ── Authentication & route access ───────────────────────────────────────────

it('redirects unauthenticated users away from the dashboard', function () {
    $this->get('/dashboard')->assertRedirect();
});

it('redirects unauthenticated users away from the logs page', function () {
    $this->get('/logs')->assertRedirect();
});

it('redirects unauthenticated users away from the APIs page', function () {
    $this->get('/APIs')->assertRedirect();
});

it('redirects unauthenticated users away from the security incidents page', function () {
    $this->get('/security/incidents')->assertRedirect();
});

it('allows an authenticated user to reach the dashboard', function () {
    $user = $this->createUser();

    $this->actingAs($user)
         ->get('/dashboard')
         ->assertOk();
});

it('allows an authenticated user to reach the logs page', function () {
    $user = $this->createUser();

    $this->actingAs($user)
         ->get('/logs')
         ->assertOk();
});

it('allows an authenticated user to reach the APIs page', function () {
    $user = $this->createUser();

    $this->actingAs($user)
         ->get('/APIs')
         ->assertOk();
});

it('allows an authenticated user to reach the security incidents page', function () {
    $user = $this->createUser();

    $this->actingAs($user)
         ->get('/security/incidents')
         ->assertOk();
});

it('filters the dashboard by domain when a base64-encoded domain is provided', function () {
    $user   = $this->createUser();
    $domain = base64_encode('api.example.com');

    $this->actingAs($user)
         ->get("/dashboard/{$domain}")
         ->assertOk();
});
