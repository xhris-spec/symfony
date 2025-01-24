<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Sonata\User;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setUsername('xhris');
        $admin->setEmail('christian@iquadrat.com');
        $admin->setEnabled(true);
        $admin->setSuperAdmin(true);
        $admin->setPlainPassword('admin1234');

        $manager->persist($admin);
        $manager->flush();
    }
}
