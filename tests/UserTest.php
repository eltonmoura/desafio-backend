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

    public function testCreateValidate()
    {
        $this->json('POST', '/users', [
            'email' => 'teste10@mail.com',
            'password' => 'senha123',
            'identity' => '111.111.1111-10',
        ])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['name' => ['The name field is required.']]);

        $this->json('POST', '/users', [
            'name' => 'Teste12',
            'password' => 'senha123',
            'identity' => '111.111.1111-10',
        ])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['email' => ['The email field is required.']]);

        $this->json('POST', '/users', [
            'name' => 'Teste12',
            'email' => 'teste10@mail.com',
            'identity' => '111.111.1111-10',
        ])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['password' => ['The password field is required.']]);

        $this->json('POST', '/users', [
            'name' => 'Teste12',
            'email' => 'teste10@mail.com',
            'password' => 'senha123',
        ])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['identity' => ['The identity field is required.']]);

        $this->json('POST', '/users', [
            'name' => 'Teste12',
            'email' => 'nao_e_email_valido',
            'password' => 'senha123',
            'identity' => '111.111.1111-10',
        ])
            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->seeJson(['email' => ['The email must be a valid email address.']]);
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
