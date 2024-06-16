<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // public function it_allows_users_to_login_with_valid_credentials()
    // {
    //     // Create a user
    //     // $user = User::create([
    //     //     'name' => 'test',
    //     //     'email' => 'test1@gmail.com',
    //     //     'password' => bcrypt('1234qwer'),
    //     //     'address' =>  'ss',
    //     //     'email_verified_at' => now(),
    //     //     'birth_date' => '1990-1-1',
    //     //     'image' => 'assets/images/users/17167156501044531237.jpg',
    //     //     'governorate' =>  'sss'
    //     // ]);

    //     // Attempt to login
    //     // $response = $this->postJson('/api/login', [
    //     //     'email' => 'admin111@gmail.com',
    //     //     'password' => '1234qwer',
    //     // ]);

    //     // Check if login was successful and token was issued
    //     // $response->assertStatus(400);
    //     // $response->assertJsonStructure(['token']);

    //     $this->assertAuthenticated();
    // }

    /** @test */
    public function it_rejects_login_with_invalid_credentials()
    {
        // Create a user
        // $user = User::create([
        //     'name' => 'test',
        //     'email' => 'test2@gmail.com',
        //     'password' => bcrypt('1234qwer'),
        //     'address' =>  'ss',
        //     'email_verified_at' => now(),
        //     'birth_date' => '1990-1-1',
        //     'image' => 'assets/images/users/17167156501044531237.jpg',
        //     'governorate' =>  'sss'
        // ]);

        // Attempt to login with incorrect password
        $response = $this->postJson('/api/login', [
            'email' => 'test2@gmail.com',
            'password' => '1234',
        ]);

        // // Check if login was rejected
        // $response->assertStatus(401);
        // $response->assertJson(['message' => 'Invalid credentials']);

        $this->assertGuest();
    }
}
