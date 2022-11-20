<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 1; $i <= 25; $i++) {
            $programOfReference = $this->getReference('program_Title_' . $i);
            $numberOfSeasons = count($programOfReference->getSeasons());
            for ($j = 1; $j <= $numberOfSeasons; $j++) {
                $seasonOfReference = $this->getReference('program_' . $programOfReference->getTitle() . '_S' . $j);
                $numberOfEpisodes = rand(10, 20);
                $k = 1;
                while ($k <= $numberOfEpisodes) {
                    $episode = new Episode();
                    $episode->setSeason($seasonOfReference);
                    $episode->setTitle($faker->sentence($faker->numberBetween(1, 8)));
                    $episode->setNumber($k);
                    $episode->setSynopsis($faker->paragraphs(3, true));
                    $manager->persist($episode);
                    $k++;
                }
            }
        }
        $manager->flush();
    }
}
