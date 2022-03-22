<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BlogPostsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $blogPost = new BlogPost();
        $blogPost->setName('Boda');
        $blogPost->setContent('Hi i am having fun');
        $blogPost->setCurrentState('in_review');
        $manager->persist($blogPost);
        $manager->flush();

        $blogPost = new BlogPost();
        $blogPost->setName('Mega');
        $blogPost->setContent('Hi i am still having fun');
        $blogPost->setCurrentState('discarded');
        $manager->persist($blogPost);
        $manager->flush();

        $blogPost = new BlogPost();
        $blogPost->setName('Mon');
        $blogPost->setContent('Hello');
        $blogPost->setCurrentState('discarded');
        $manager->persist($blogPost);
        $manager->flush();

        $blogPost = new BlogPost();
        $blogPost->setName('Holy');
        $blogPost->setContent('Yahoo');
        $blogPost->setCurrentState('published');
        $manager->persist($blogPost);
        $manager->flush();

        $blogPost = new BlogPost();
        $blogPost->setName('Oh My');
        $blogPost->setContent('HAHAHAHA');
        $blogPost->setCurrentState('published');
        $manager->persist($blogPost);
        $manager->flush();

        $blogPost = new BlogPost();
        $blogPost->setName('Are you READY?');
        $blogPost->setContent('YES WE ARE READY!');
        $blogPost->setCurrentState('published');
        $manager->persist($blogPost);
        $manager->flush();
    }
}
