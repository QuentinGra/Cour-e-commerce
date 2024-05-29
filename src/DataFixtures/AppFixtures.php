<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('admin@test.com')
            ->setPassword(
                $this->hasher->hashPassword(new User(), 'Test1234!')
            )
            ->setRoles(['ROLE_ADMIN'])
            ->setFirstName('Quentin')
            ->setLastName('Grange');

        $manager->persist($user);
        $users[] = $user;

        for ($i = 0; $i < 10; $i++) {
            $user = (new User())
                ->setEmail("user-$i@test.com")
                ->setPassword(
                    $this->hasher->hashPassword(new User(), 'Test1234!')
                )
                ->setFirstName("User $i")
                ->setLastName('Test');

            $manager->persist($user);
            $users[] = $user;
        }

        $manager->flush();
    }
}
