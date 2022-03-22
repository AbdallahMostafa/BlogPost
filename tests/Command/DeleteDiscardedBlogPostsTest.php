<?php

namespace App\Tests\Command;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;


class DeleteDiscardedBlogPostsTest extends KernelTestCase
{
    use FixturesTrait;

    public function setUp() : void
    {
        $this->loadFixtures(array(
            'App\DataFixtures\BlogPostsFixtures',
        ));
    }

    public function testSuccessCommand(){
        $kernel = self::bootKernel([
            'environment' => 'test',
            'debug'       => false,
        ]);

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $application = new Application($kernel);

        $command = $application->find('app:delete-discarded-blog-posts');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Success', $output);

        $query = $entityManager->createQuery(
            'SELECT post 
            FROM App\Entity\BlogPost post
            WHERE post.currentState=:state
            '
        )->setParameter('state', 'discarded');

        $this->assertEquals( [], $query->getResult());

        $query = $entityManager->createQuery(
            'SELECT post 
            FROM App\Entity\BlogPost post
            WHERE post.currentState=:state
            '
        )->setParameter('state', 'in_review');
        $this->assertEquals( 1, $this->count($query->getResult()));

    }
}