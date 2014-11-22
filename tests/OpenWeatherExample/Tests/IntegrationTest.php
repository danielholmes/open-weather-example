<?php

namespace OpenWeatherExample\Tests;

class IntegrationTest extends IntegrationTestCase
{
    public function testEntryForm()
    {
        $response = $this->createTestClient()->get('/')->send();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertContains('Enter a location', $response->getBody(true));
    }

    public function testValidSubmission()
    {
        $response = $this->createTestClient()->get('/?location=Sydney%20AU')->send();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertNotContains('Enter a location', $response->getBody(true));
    }

    public function testInvalidSubmission()
    {
        $response = $this->createTestClient()->get(
            '/?location=Sydney%20ZZ',
            null,
            ['exceptions' => false]
        )->send();

        $this->assertSame(400, $response->getStatusCode());
        $this->assertContains(
            'The location you entered &quot;Sydney ZZ&quot; could not be found',
            $response->getBody(true)
        );
        $this->assertContains('Enter a location', $response->getBody(true));
    }
}