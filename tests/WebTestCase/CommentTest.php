<?php

namespace App\Tests\WebTestCase;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentTest extends WebTestCase
{
    public function testPublishCommentWhenPostIsPublished(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/comments',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            '{
            "authorName" : "test",
            "authorEmail" : "test@gmail.com",
            "content" : "test comment",
            "blogPost" : "4"
            }'
        );

        $this->assertResponseIsSuccessful();

        $response= json_decode($client->getResponse()->getContent(), true);

        $authName = $response ['authorName'];

        $this->assertEquals( 'test', $authName);
        $this->assertEquals( 'published', $response['blogPost']['currentState']);



    }
    public function testPublishCommentWhenPostIsNotPublished(): void
    {
        $client = static::createClient();
        $client->request('POST', '/comments',
            [],
            [],
            [
            'CONTENT_TYPE' => 'application/json',
            ],
            '{  
                    "authorName" : "test",
                    "authorEmail" : "test@gmail.com",
                    "content" : "test comment",
                    "blogPost" : "1"
                    }'
            );

        $response = json_decode($client->getResponse()->getContent());
        $response = $response->message;
        $this->assertEquals("The Post must be published in order to be able to comment", $response);
    }
}
