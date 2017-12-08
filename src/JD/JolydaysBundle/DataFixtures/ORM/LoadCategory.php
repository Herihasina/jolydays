<?php

namespace JD\JolydaysBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JD\JolydaysBundle\Entity\Category;

class LoadCategory implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    for ($i = 1; $i < 6; $i++) {
      $category = new Category();
      $category->setName('Category '.$i);
      $manager->persist($category);
    }

    // Persist in db
    $manager->flush();


  }
}