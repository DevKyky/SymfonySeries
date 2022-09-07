<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private Generator $generator;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->generator = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->addSeries($manager, $this->generator);
        $this->addUsers($manager, $this->generator);
    }

    public function addUsers(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {

            $user = new User();
            $user->setRoles(['ROLE_USER'])
                ->setEmail($this->generator->email)
                ->setFirstname($this->generator->firstName)
                ->setLastname($this->generator->lastName)
                ->setPassword($this->userPasswordHasher->hashPassword($user, 'Pa$$w0rd'));

            $manager->persist($user);
            $manager->flush();
        }
    }

    public function addSeries(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {

            $genres = ['SF', 'Drama', 'Western', 'Polar'];

            $serie = new Serie();
            $serie->setBackdrop($this->generator->word.".png")
                ->setDateCreated($this->generator->dateTimeBetween('-2 year', '-6 month'))
                ->setFirstAirDate($this->generator->dateTimeBetween('-1 year', '-1 month'))
                ->setGenres($this->generator->randomElement($genres))
                ->setLastAirDate(new \DateTime())
                ->setName("Serie $i")
                ->setPopularity($this->generator->numberBetween(100, 9000))
                ->setPoster($this->generator->word.".png")
                ->setStatus("Ended")
                ->setTmdbId(1234)
                ->setVote(10.0);

            $manager->persist($serie);
        }

        $manager->flush();
    }
}
