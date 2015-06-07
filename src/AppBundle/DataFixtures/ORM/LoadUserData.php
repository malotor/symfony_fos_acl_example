<?php

// src/Acme/HelloBundle/DataFixtures/ORM/LoadUserData.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;


use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface,  ContainerAwareInterface
{
  /**
   * @var ContainerInterface
   */
  private $container;

  /**
   * {@inheritDoc}
   */
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }

  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
    $userAdmin = new User();
    $userAdmin->setUsername('admin');

    //$userAdmin->setSalt(md5(uniqid()));

    $encoder = $this->container->get('security.encoder_factory')->getEncoder($userAdmin);

    //$encoder = $encoderFactory->getEncoder($userAdmin);
    $encodedPass = $encoder->encodePassword("test", $userAdmin->getSalt());

    $userAdmin->setPassword($encodedPass);

    $userAdmin->setEmail('admin@example.com');
    $userAdmin->addRole('ROL_USER');
    $userAdmin->addRole('ROL_ADMIN');
    $userAdmin->setEnabled(true);
    $manager->persist($userAdmin);
    $manager->flush();
  }
}