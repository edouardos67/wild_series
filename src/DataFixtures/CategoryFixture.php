<?php


namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class CategoryFixture extends Fixture
{
    const CATEGORIES = [
        'Action', 'Aventure', 'Fantastique', 'Animation','Horreur', 'Comédie','Animation',
    ];


    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $this->addReference('categorie_'.$key, $category);
        }
        $manager->flush();
    }
}
