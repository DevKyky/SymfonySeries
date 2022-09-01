<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $generator = Factory::create('fr_FR');

        $this->addSeries($manager, $generator);
    }

    public function addSeries(ObjectManager $manager, Generator $generator)
    {
        for ($i = 0; $i < 10; $i++) {

            $genres = ['SF', 'Drama', 'Western', 'Polar'];

            $serie = new Serie();
            $serie->setBackdrop($generator->word.".png")
                ->setDateCreated($generator->dateTimeBetween('-2 year', '-6 month'))
                ->setFirstAirDate($generator->dateTimeBetween('-1 year', '-1 month'))
                ->setGenres($generator->randomElement($genres))
                ->setLastAirDate(new \DateTime())
                ->setName("Serie $i")
                ->setPopularity($generator->numberBetween(100, 9000))
                ->setPoster($generator->word.".png")
                ->setStatus("Ended")
                ->setTmdbId(1234)
                ->setVote(10.0);

            $manager->persist($serie);
        }

        $manager->flush();
    }
}
