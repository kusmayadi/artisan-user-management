<?php

namespace Kusmayadi\ArtisanUser\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Kusmayadi\ArtisanUser\Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Kusmayadi\ArtisanUser\Models\User;

class UserTest extends TestCase
{
  use RefreshDatabase;

  /** @test **/
  function userModel()
  {
    $user = factory(User::class)->create();

    $this->assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
        'password' => $user->password
    ]);
  }
}
