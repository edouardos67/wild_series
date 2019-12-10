<?php


namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Season;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class SeasonFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 80; $i++) {
            $faker = Faker\Factory::create('en_US');
            $season = new Season();
            $season->setNumber(random_int(1, 10));
            $seasonYear = $faker->year($max = 'now');
            while ($seasonYear<1999) {
                $seasonYear = $faker->year($max = 'now');
            }
            $season->setYear($seasonYear);
            $season->setDescription($faker->realText);
            $season->setProgram($this->getReference('program_' . random_int(0, 5)));

            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }
        $manager->flush();
    }

    public function getDependencies()

    {
        return [ProgramFixture::class];
    }

}
