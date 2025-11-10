<?php
require __DIR__ . '/../vendor/autoload.php';

use Vemetric\Vemetric;

$vemetric = new Vemetric([
    'token' => 'o1rySsGlUtFCyflo',
    'host' => 'http://localhost:4004',
]);

// Track an event
$vemetric->trackEvent('SignupCompleted', [
    'userIdentifier' => 'dmmIrnzUzVMJD03tjCiHXTEEgX6xIPJm',
    'eventData'      => ['plan' => 'Pro'],
    'userData'       => ['setOnce' => ['signupSource' => 'landing-page']],
]);

// Update user later
$vemetric->updateUser([
    'userIdentifier' => 'dmmIrnzUzVMJD03tjCiHXTEEgX6xIPJm',
    'userAvatarUrl'  => 'https://pbs.twimg.com/profile_images/1623645336349601792/d03RX2V3_400x400.jpg',
    'userData'       => ['set' => ['plan' => 'Business']],
]);

echo "✅ Requests sent\n";