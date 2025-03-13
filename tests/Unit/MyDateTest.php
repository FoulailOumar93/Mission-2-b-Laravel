<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MyDate;         // ajouté pour pouvoir utiliser la classe

class MyDateTest extends TestCase
{
    /**
     * @test
     */
    public function extractionDuMoisDuChampMois()
    {
        $mois = MyDate::extraireMois('201901','Problème sur extraction du mois');
        $this->assertEquals('01',$mois);
    }
    /**
     * @test
     */
    public function retourneUneDateAnglaiseAuFormatFrançais()
    {
        $date = MyDate::getFormatFrançais('2019-1-22');
        $this->assertEquals('22-01-2019',$date);
    }
}
