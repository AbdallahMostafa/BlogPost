<?php

namespace App\Tests\WebTestCase;

use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogPostTest extends WebTestCase
{

    use FixturesTrait;

    public function setUp() : void
    {
        $this->loadFixtures(array(
            'App\DataFixtures\BlogPostsFixtures',
        ));
        self::ensureKernelShutdown();

    }

    public function testInvalidPublishTransition() {

        $client = static::createClient();

        $client->request(
            'POST',
            '/blog-posts/6/publish',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],

        );
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertResponseStatusCodeSame(422, $statusCode);

        $response = json_decode($client->getResponse()->getContent());
        $message = $response->message;

        $this->assertEquals( 'Transition "published" is not enabled for workflow "blog_post_publishing".', $message);

    }
    public function testInvalidDiscardedTransition()
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/blog-posts/2/discard',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],

        );
        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertResponseStatusCodeSame(422, $statusCode);

        $response = json_decode($client->getResponse()->getContent());

        $message = $response->message;

        $this->assertEquals( 'Transition "discarded" is not enabled for workflow "blog_post_publishing".', $message);

    }
}
