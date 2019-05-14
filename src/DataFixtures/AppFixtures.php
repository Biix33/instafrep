<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++):
            $content = $faker->realText(280);
            $isPublic = $faker->boolean(50);
            $date = new \DateTime();
            $post = new Post();
            $post
                ->setContent($content)
                ->setPublic($isPublic)
                ->setCreatedAt($date)
                ->setPublishedAt($date->add(new \DateInterval('P1D')));
            $manager->persist($post);
        endfor;

        $manager->flush();
    }
}
