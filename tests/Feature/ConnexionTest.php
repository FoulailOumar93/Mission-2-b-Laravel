<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
class ConnexionTest extends TestCase
{
    /**
     * @test
     */
    public function retourneFormulaireConnexion()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSeeText('Login*');
        $response->assertSeeText('Mot de passe*');
    }
     /**
     * @test
     */
    public function valideLaConnexionConforme()
    {
        $data = ['login'=>'toto','mdp'=>'titi'];
        $response = $this->post('/',$data);
        $response->assertStatus(200);
        $response->assertSessionHas('visiteur');
        $response->assertSeeText('Andre David');
    }
    /**
     * @test
     */
    public function echecIdentificationDeConnexion()
    {
        $data = ['login'=>'toto','mdp'=>'toto'];
        $response = $this->post('/',$data);
        $response->assertStatus(200);
        $response->assertSessionMissing('visiteur');
        $response->assertSeeText('Login*');
        $response->assertSeeText('Mot de passe*');
    }

}
