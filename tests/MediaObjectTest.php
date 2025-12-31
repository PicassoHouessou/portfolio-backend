<?php
// tests/MediaObjectTest.php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\MediaObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaObjectTest extends ApiTestCase
{
   // use RefreshDatabaseTrait;

    public function testCreateAMediaObject(): void
    {
        $file = new UploadedFile('fixtures/files/image.jpg', 'image.jpg');
        $client = self::createClient();

        $client->request('POST', '/api/media_objects', [
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'files' => [
                    'file' => $file,
                ],
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceItemJsonSchema(MediaObject::class);
    }
}
