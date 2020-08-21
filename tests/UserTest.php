<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

use App\Models\User;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::find(1); // Admin
        $this->actingAs($user);
    }

    /**
     * [GET] /users
     */
    public function testShouldReturnAllUsers()
    {
        $response =  $this->call('GET', '/users');
        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertIsArray(json_decode($response->getContent(), true));
    }
}
