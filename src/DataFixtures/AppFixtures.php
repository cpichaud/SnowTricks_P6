<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Video;
use DateTimeImmutable;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {
        // Create a user
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                'password123'
            )
        );
        $user->setIsVerified(true);
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        // Create an array of trick names
        $trickNames = [
            'Japan',
            'Mute',
            'Indy',
            'Stalefish',
            'Nose grab',
            'Seat beath',
            'Sad',
            'Tail grab',
            'Method',
            'Melon'
        ];

        // Create 10 tricks and associate them with the user
        for ($i = 0; $i < 10; $i++) {
            $trick = new Trick();
            $trick->setName($trickNames[$i]);
            $trick->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.');
            $trick->setCreatedAt(new DateTimeImmutable());
            $trick->setUpdatedAt(new DateTimeImmutable());
            $trick->setUser($user);

            $video = new Video();
            $video->setUrl('https://www.youtube.com/watch?v=PxhfDec8Ays');
            $video->setTrick($trick);

             // Add 3-5 comments per trick
             $numComments = random_int(3, 5);
             for ($j = 0; $j < $numComments; $j++) {
                 $comment = new Comment();
                 $comment->setAuthor($user);
                 $comment->setContent('Lorem ipsum dolor sit amet !');
                 $comment->setCreatedAt(new \DateTimeImmutable());
                 $comment->setTrick($trick);
 
                 $manager->persist($comment);
             }


            $manager->persist($video);
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
