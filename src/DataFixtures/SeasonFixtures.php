<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

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
        for ($i = 1; $i <= 25; $i++) {
            $programOfReference = $this->getReference('program_Title_' . $i);
            $firstSeasonYear = rand(1990, 2021);
            $numberOfSeasons = rand(1, 7);
            $j = 1;
            while (($j <= $numberOfSeasons) && ($firstSeasonYear + $j <= 2022)) {
                $season = new Season();
                $season->setProgram($programOfReference);
                $season->setNumber($j);
                $season->setYear($firstSeasonYear + $j - 1);
                $season->setDescription('Description season ' . $j);
                $manager->persist($season);
                $this->addReference('program_' . $programOfReference->getTitle() . '_S' . $j, $season);
                $j++;
            }
        }
        $manager->flush();
    }
}
