<?php

namespace App\Tests\APITestCase;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class BlogPostTest extends ApiTestCase
{
    use FixturesTrait;

    public function setUp() : void
    {
        $this->loadFixtures(array(
            'App\DataFixtures\BlogPostsFixtures',
        ));
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testInReviewAPI(): void
    {
        $response = static::createClient()->request('POST', '/blog-posts', ['json' => [
            "name" => "test",
            "content" => "test comment"
        ]]);

        $this->assertResponseIsSuccessful();

        $inReview = json_decode($response->getContent(),  true);
        $inReview = $inReview['currentState'];


        $this->assertEquals( 'in_review', $inReview);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testPublishAPI(): void
    {
        $response = static::createClient()->request('POST', '/blog-posts', ['json' => [
            "name" => "test",
            "content" => "test comment"
        ]]);

        $this->assertResponseIsSuccessful();

        $response= json_decode($response->getContent(), true);

        $id = $response['id'];

        $response = static::createClient()->request('POST', '/blog-posts/'.$id.'/publish', ['json' => []]);

        $this->assertResponseIsSuccessful();

        $published = json_decode($response->getContent(),  true);
        $published = $published['currentState'];

        $this->assertEquals( 'published', $published);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testDiscardAPI(): void
    {
        $response = static::createClient()->request('POST', '/blog-posts', ['json' => [
            "name" => "test",
            "content" => "test comment"
        ]]);

        $this->assertResponseIsSuccessful();

        $response= json_decode($response->getContent(), true);

        $id = $response['id'];

        $response = static::createClient()->request('POST', '/blog-posts/'.$id.'/discard', ['json' => []]);

        $this->assertResponseIsSuccessful();

        $discarded = json_decode($response->getContent(),  true);
        $discarded = $discarded['currentState'];
        $this->assertEquals( 'discarded', $discarded);
    }
}
