<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //Admin
        $admin = new User();
        $admin->setEmail('root@root.dev');
        $admin->setPassword(
            $this->encoder->encodePassword($admin, 'root')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        //User
        $user = new User();
        $user->setEmail('user@user.dev');
        $user->setPassword(
            $this->encoder->encodePassword($user, 'user')
        );
        $manager->persist($user);

        $manager->flush();

        $manager->flush();
    }
}
