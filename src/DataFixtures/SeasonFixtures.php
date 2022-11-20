<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 1; $i <= 25; $i++) {
            $programOfReference = $this->getReference('program_Title_' . $i);
            $firstSeasonYear = $faker->numberBetween(1990, 2021);
            $numberOfSeasons = $faker->numberBetween(1, 10);
            $j = 1;
            while (($j <= $numberOfSeasons) && ($firstSeasonYear + $j <= 2022)) {
                $season = new Season();
                $season->setProgram($programOfReference);
                $season->setNumber($j);
                $season->setYear($firstSeasonYear + $j - 1);
                $season->setDescription($faker->paragraphs(3, true));
                $manager->persist($season);
                $this->addReference('program_' . $programOfReference->getTitle() . '_S' . $j, $season);
                $j++;
            }
        }
        $manager->flush();
    }
}
