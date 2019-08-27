<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\ShortLink;

class ShortLinkTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testsShortLinkAreCreatedCorrectly()
    {        
        $user = factory(User::class)->create();
        $token = $user->generateToken();

        $headers = ['Authorization' => "Bearer $token"];

        $bodyParams = [
            'link' => 'https://petewhodidnottweet.com/2018/04/creating-the-listener-and-database-in-silent-mode',
        ];        

        $response = $this->json('POST', 'api/createshorten', $bodyParams, $headers);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'response' => [
                    'code',
                    'message'
                ],
                'data' => [
                    'code',
                    'link_sort',
                ],
            ]);        
    }

    public function testsShortLinkAreUpdateCorrectly()
    {                
        $shortLinkDb = ShortLink::orderBy('created_at','desc')->first();
        if($shortLinkDb) {
            $user = factory(User::class)->create();
            $token = $user->generateToken();

            $headers = ['Authorization' => "Bearer $token"];

            $bodyParams = [
                'code' => $shortLinkDb->code,
                'link' => 'https://petewhodidnottweet.com/2018/04/creating-the-listener-and-database-in-silent-mode',
            ];        

            $response = $this->json('POST', 'api/updateshorten', $bodyParams, $headers);
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'response' => [
                        'code',
                        'message'
                    ],
                    'data' => [
                        'code',
                        'link_sort',
                    ],    
                ]);    

        }
        else {
            $response = $this->get('/');
            $response->assertStatus(200);    
        }
    }

    public function testsShortLinkAreDeleteCorrectly()
    {               
        $shortLinkDb = ShortLink::orderBy('created_at','desc')->first();
        if($shortLinkDb) {
            $user = factory(User::class)->create();
            $token = $user->generateToken();

            $headers = ['Authorization' => "Bearer $token"];

            $bodyParams = [
                'code' => $shortLinkDb->code,
            ];        

            $response = $this->json('POST', 'api/deleteshorten', $bodyParams, $headers);
            $response->assertStatus(200)
                ->assertJsonStructure([
                    'response' => [
                        'code',
                        'message'
                    ],
                    'data' => [
                        'code',
                    ],    
                ]);    
        }
        else {
            $response = $this->get('/');
            $response->assertStatus(200);        
        }
    }
}
