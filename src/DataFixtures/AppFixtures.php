<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $users = [];

        for ($i = 0; $i < 10; $i++):
            $user = new User();
            $user
                ->setName($faker->name)
                ->setPseudo($faker->firstName.$i)
                ->setEmail($faker->email)
                ->setBirthday(new \DateTime($faker->dateTimeThisCentury->format('Y-m-d')))
                ->setAvatar("https://i.pravatar.cc/150?img=$i")
                ->setPassword($this->encoder->encodePassword($user, 'azeaze'))
                ->setCreatedAt(new \DateTime());
            $manager->persist($user);
            array_push($users, $user);
        endfor;

        for ($i = 0; $i < 30; $i++):
            $content = $faker->realText(280);
            $isPublic = $faker->boolean(50);
            $date = new \DateTime();
            $post = new Post();
            $post
                ->setContent($content)
                ->setPublic($isPublic)
                ->setCreatedAt($date)
                ->setPublishedAt($date->add(new \DateInterval('P1D')))
                ->setUserId($users[rand(1, 9)]);
            $manager->persist($post);
        endfor;

        $manager->flush();
    }
}
