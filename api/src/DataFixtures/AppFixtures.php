<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends BaseFixture
{
    const COMMENT_COUNT = 40;

    public function load(ObjectManager $manager)
    {
        $increment = 0;
        while ($increment < self::COMMENT_COUNT) {
            $comment = new Comment();
            $comment->setAuthor($this->faker->name);
            $comment->setComment($this->faker->text(255));
            $comment->setCreatedAt($this->faker->dateTime);
            $manager->persist($comment);

            $increment++;
        }

        $manager->flush();
    }
}
