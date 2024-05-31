<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private UserPasswordHasherInterface $hasher,
    ) {
        $this->faker = Factory::create('fr_FR');
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

        for ($i = 0; $i < 10; ++$i) {
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

        for ($i = 0; $i < 50; ++$i) {
            $address = (new Address())
                ->setAddress($this->faker->address())
                ->setZipCode($this->faker->postcode())
                ->setCity($this->faker->city())
                ->addUser($this->faker->randomElement($users));

            $manager->persist($address);
        }

        $manager->flush();
    }
}
