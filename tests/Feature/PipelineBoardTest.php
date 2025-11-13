<?php

use App\Livewire\PipelineBoard;
use App\Models\Lead;
use App\Models\User;
use Livewire\Livewire;

it('can create a lead', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(PipelineBoard::class)
        ->set('title', 'Test Lead')
        ->set('email', 'test@example.com')
        ->call('saveLead');

    expect(Lead::where('title', 'Test Lead')->exists())->toBeTrue();
});
