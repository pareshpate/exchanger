<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Currency;

class CurrencyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $currency = new Currency();
        $currency->setName('INR');
        $manager->persist($currency);
        $manager->flush();
        
        $currency = new Currency();
        $currency->setName('EUR');
        $manager->persist($currency);
        $manager->flush();
    }
}
