<?php

declare(strict_types=1);

namespace Vemetric;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Composer\InstalledVersions;

final class Vemetric
{
    private string $token;
    private string $host;
    private Client $http;

    public function __construct(array $options, ?Client $httpClient = null)
    {
        if (empty($options['token'])) {
            throw new \InvalidArgumentException('Vemetric: "token" is required.');
        }

        $this->token = $options['token'];
        $this->host  = rtrim($options['host'] ?? 'https://hub.vemetric.com', '/');

        $this->http = $httpClient ?? new Client([
            'base_uri' => $this->host,
            'timeout'  => $options['timeout'] ?? 2.0,
            'headers'  => [
                'Content-Type' => 'application/json',
                'Token' => $this->token,
                'V-SDK' => 'php',
                'V-SDK-Version' => InstalledVersions::getPrettyVersion('vemetric/vemetric-php'),
            ],
        ]);
    }

    public function trackEvent(
        string $eventName,
        array  $args
    ): void {
        if (empty($eventName)) {
            throw new \InvalidArgumentException('Vemetric: trackEvent(): "eventName" is required.');
        }
        if (empty($args['userIdentifier'])) {
            throw new \InvalidArgumentException('Vemetric: trackEvent(): "userIdentifier" is required.');
        }

        $payload = [
            'name'      => $eventName,
            'userIdentifier' => $args['userIdentifier'],
        ];
        if (!empty($args['eventData'])) {
            $payload['customData'] = $args['eventData'];
        }
        if (!empty($args['userDisplayName'])) {
            $payload['displayName'] = $args['userDisplayName'];
        }
        if (!empty($args['userData'])) {
            $payload['userData'] = $args['userData'];
        }

        try {
            $this->post('/e', $payload);
        } catch (\Throwable $e) {
            echo "Vemetric: Error tracking event: " . $e->getMessage() . " \n";
        }
    }

    /**
     * Update fields on an existing user profile.
     */
    public function updateUser(array $args): void
    {
        if (empty($args['userIdentifier'])) {
            throw new \InvalidArgumentException('Vemetric: updateUser(): "userIdentifier" is required.');
        }

        $payload = [
            'userIdentifier' => $args['userIdentifier'],
        ];
        if (!empty($args['userDisplayName'])) {
            $payload['displayName'] = $args['userDisplayName'];
        }
        if (!empty($args['userAvatarUrl'])) {
            $payload['avatarUrl'] = $args['userAvatarUrl'];
        }
        if (!empty($args['userData'])) {
            $payload['data'] = $args['userData'];
        }

        try {
            $this->post('/u', $payload);
        } catch (\Throwable $e) {
            echo "Vemetric: Error updating user: " . $e->getMessage() . " \n";
        }
    }

    private function post(string $path, array $payload): void
    {
        $res = $this->http->post($path, ['json' => $payload]);

        if ($res->getStatusCode() >= 300) {
            throw new \RuntimeException(
                "Vemetric API error {$res->getStatusCode()}: {$res->getBody()}"
            );
        }
    }
}