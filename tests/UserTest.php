<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserTest extends ApiTestCase
{
    // This trait provided by Foundry will take care of refreshing the database content to a known state before each test
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        // Create 100 users using our factory
        UserFactory::createMany(100);

        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/contexts/User',
            '@id' => '/users',
            '@type' => 'Collection',
            'totalItems' => 100,
            'view' => [
                '@id' => '/users?page=1',
                '@type' => 'PartialCollectionView',
                'first' => '/users?page=1',
                'last' => '/users?page=4',
                'next' => '/users?page=2',
            ],
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(30, $response->toArray()['member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        $this->assertMatchesResourceCollectionJsonSchema(User::class);
    }

    public function testCreateUser(): void
    {
        $faker = \Faker\Factory::create();
        $response = static::createClient()->request('POST', '/users', ['json' => [
            'email' => "test@gmail.com",
            'username' => "picasso",
            'firstName' => 'Picasso',
            'lastName' => 'Doe',
            'birthdate' => '1995-07-31T00:00:00+00:00',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/contexts/User',
            '@type' => 'User',
            'email' => "test@gmail.com",
            'username' => "picasso",
            'firstName' => 'Picasso',
            'lastName' => 'Doe',
            'birthdate' => '1995-07-31T00:00:00+00:00',
        ]);
        $this->assertMatchesRegularExpression('~^/users/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(User::class);
    }

    public function testCreateInvalidUser(): void
    {
        static::createClient()->request('POST', '/users', ['json' => [
            'email' => '',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'title' => 'An error occurred',
            'description' => 'isbn: This value is neither a valid ISBN-10 nor a valid ISBN-13.
username: This value should not be blank.
firstName: This value should not be blank.
lastName: This value should not be blank.
birthdate: This value should not be null.',
        ]);
    }

    public function testUpdateUser(): void
    {
        // Only create the book we need with a given ISBN
        UserFactory::createOne(['email' => 'test@gmail.com']);

        $client = static::createClient();
        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        $iri = $this->findIriBy(User::class, ['isbn' => '9781344037075']);

        // Use the PATCH method here to do a partial update
        $client->request('PATCH', $iri, [
            'json' => [
                'firstName' => 'Jean',
            ],
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $iri,
            'email' => 'test@gmail.com',
            'firstName' => 'Jean',
        ]);
    }

    public function testDeleteUser(): void
    {
        // Only create the book we need with a given ISBN
        UserFactory::createOne(['email' => 'test@gmail.com']);

        $client = static::createClient();
        $iri = $this->findIriBy(User::class, ['email' => 'test@gmail.com']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
        // Through the container, you can access all your services from the tests, including the ORM, the mailer, remote API clients...
            static::getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => 'test@gmail.com'])
        );
    }
}

