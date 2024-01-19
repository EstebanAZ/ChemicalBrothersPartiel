<?php

namespace App\DataFixtures;

use App\Entity\Fds;
use App\Entity\Notification;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            //création des produits
            $product = new Product();
            $product->setName('Produit n°'.$i);
            $product->setDescription('Description du produit n°'.$i);
            $manager->persist($product);

            //création des utilisateurs
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setEmail('user'.$i.'@gmail.com');
            $user->setPassword('password'.$i);
            $user->addProduct($product);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
