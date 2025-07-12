<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\CV;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CVTest extends ApiTestCase
{
    //use RefreshDatabaseTrait;

    public function testCreateACV(): void
    {
        $file = new UploadedFile('fixtures/files/cv.pdf', 'cv.pdf');
        $client = self::createClient();

        $client->request('POST', '/api/c_vs', [
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'files' => [
                    'file' => $file,
                ],
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceItemJsonSchema(CV::class);
    }
}
