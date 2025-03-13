<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class etatFraisTest extends TestCase
{
    /**
     * @test
     */
    public function recupereLesMois()
    {
        $visiteur = ['id'=>'a17','nom'=>'Andre','prenom' =>'David'];
        session(['visiteur' => $visiteur]);

        $response = $this
                    ->withSession(["visiteur",$visiteur])
                    ->get('/selectionMois');
        $response
                ->assertViewHas("lesMois")
                ->assertStatus(200)
                ->assertSeeText("Mes fiches de frais");

    }
}
