<?php


namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Service\Slugify;


class ActorFixture extends Fixture implements DependentFixtureInterface
{



    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 50; $i++) {
            $faker = Faker\Factory::create('en_US');
            $actor = new Actor();
            $actor->setName($faker->name);

            if ($i < 8) {
                $actor->addProgram($this->getReference('program_0'));
                $actor->addProgram($this->getReference('program_3'));
            } elseif ($i < 16) {
                $actor->addProgram($this->getReference('program_2'));
                $actor->addProgram($this->getReference('program_4'));
            } elseif ($i < 24) {
                $actor->addProgram($this->getReference('program_3'));
                $actor->addProgram($this->getReference('program_5'));
            } elseif ($i < 32) {
                $actor->addProgram($this->getReference('program_4'));
            } elseif ($i < 40) {
                $actor->addProgram($this->getReference('program_5'));
                $actor->addProgram($this->getReference('program_0'));
            } else {
                $actor->addProgram($this->getReference('program_3'));
            }

            $slugify = new Slugify();
            $slug = $slugify->generate($actor->getName());
            $actor->setSlug($slug);

            $manager->persist($actor);
            $this->addReference('acteur_'.$i, $actor);
        }
        $manager->flush();
    }

    public function getDependencies()

    {
        return [ProgramFixture::class];
    }
}
