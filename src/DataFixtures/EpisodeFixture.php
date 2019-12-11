<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;

use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class EpisodeFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 500; $i++) {
            $faker = Faker\Factory::create('en_US');
            $episode = new Episode();
            $episode->setNumber(random_int(1, 10));
            $episode->setTitle($faker->text);
            $episode->setSynopsis($faker->realText);
            $episode->setSeason($this->getReference('season_' . random_int(1, 80)));
            $slugify = new Slugify();
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $manager->persist($episode);
//            $this->addReference('episode_' . $i, $season);
        }
        $manager->flush();
    }

    public function getDependencies()

    {
        return [SeasonFixture::class];
    }

}
