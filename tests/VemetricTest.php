<?php

declare(strict_types=1);

namespace Vemetric\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use Vemetric\Vemetric;

class VemetricTest extends TestCase
{
    private Vemetric $vemetric;
    private Client $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->mockClient = Mockery::mock(Client::class);
        $this->vemetric = new Vemetric([
            'token' => 'test-token',
            'host' => 'https://test.vemetric.com'
        ], $this->mockClient);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testConstructorThrowsExceptionWhenTokenIsMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Vemetric: "token" is required.');
        
        new Vemetric([]);
    }

    public function testTrackEventThrowsExceptionWhenEventNameIsMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Vemetric: trackEvent(): "eventName" is required.');
        
        $this->vemetric->trackEvent('', ['userIdentifier' => 'test-user']);
    }

    public function testTrackEventThrowsExceptionWhenUserIdentifierIsMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Vemetric: trackEvent(): "userIdentifier" is required.');
        
        $this->vemetric->trackEvent('test-event', []);
    }

    public function testTrackEventSendsCorrectPayload(): void
    {
        $eventName = 'test-event';
        $userIdentifier = 'test-user';
        $userDisplayName = 'John Doe';
        $eventData = ['key' => 'value'];
        $userData = ['name' => 'Test User'];

        $expectedPayload = [
            'name' => $eventName,
            'userIdentifier' => $userIdentifier,
            'displayName' => $userDisplayName,
            'customData' => $eventData,
            'userData' => $userData
        ];

        $this->mockClient->shouldReceive('post')
            ->once()
            ->with('/e', ['json' => $expectedPayload])
            ->andReturn(new Response(200));

        $this->vemetric->trackEvent($eventName, [
            'userIdentifier' => $userIdentifier,
            'userDisplayName' => $userDisplayName,
            'eventData' => $eventData,
            'userData' => $userData
        ]);

        $this->assertTrue(true); // This test passes if no exception is thrown
    }

    public function testUpdateUserThrowsExceptionWhenUserIdentifierIsMissing(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Vemetric: updateUser(): "userIdentifier" is required.');
        
        $this->vemetric->updateUser([]);
    }

    public function testUpdateUserSendsCorrectPayload(): void
    {
        $userIdentifier = 'test-user';
        $userData = ['name' => 'Test User'];

        $expectedPayload = [
            'userIdentifier' => $userIdentifier,
            'data' => $userData
        ];

        $this->mockClient->shouldReceive('post')
            ->once()
            ->with('/u', ['json' => $expectedPayload])
            ->andReturn(new Response(200));

        $this->vemetric->updateUser([
            'userIdentifier' => $userIdentifier,
            'userData' => $userData
        ]);

        $this->assertTrue(true); // This test passes if no exception is thrown
    }
} 