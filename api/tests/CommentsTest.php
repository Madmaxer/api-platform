<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Comment;
use Doctrine\Persistence\ManagerRegistry;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class CommentsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    /** @var ManagerRegistry */
    private $registryManager;

    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->registryManager = $kernel->getContainer()
            ->get('doctrine');
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/comments');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertCount(10, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Comment::class);
    }

    public function testCreateComment(): void
    {
        $response = static::createClient()->request('POST', '/comments', ['json' => [
            'comment' => 'Brilliantly conceived and executed.',
            'author' => 'Margaret Atwood',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertRegExp('~^/comments/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Comment::class);
    }

    public function testCreateInvalidComment(): void
    {
        static::createClient()->request('POST', '/comments', ['json' => [
            'id' => 'invalid',
        ]]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testUpdateComment(): void
    {
        $objectManager = $this->registryManager->getManagerForClass(Comment::class);
        /** @var Comment $comment */
        $comment = $objectManager->getRepository(Comment::class)->findOneBy([]);

        $client = static::createClient();
        $response = $client->request('PUT', \sprintf('/comments/%s', $comment->getId()), ['json' => [
            'comment' => 'updated comment',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertArraySubset([
            '@id' => sprintf('/comments/%s', $comment->getId()),
            'comment' => 'updated comment',
        ], $response->toArray());
    }

    public function testDeleteComment(): void
    {
        $objectManager = $this->registryManager->getManagerForClass(Comment::class);
        /** @var Comment $comment */
        $comment = $objectManager->getRepository(Comment::class)->findOneBy([]);

        $client = static::createClient();
        $client->request('DELETE', sprintf('/comments/%s', $comment->getId()));

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::$container->get('doctrine')
                ->getRepository(Comment::class)
                ->findOneBy(['id' => $comment->getId()])
        );
    }
}
