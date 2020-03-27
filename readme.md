#Unisender One API PHP client

## Installation

```shell script
composer require codexsoft/unisender-one-api-php-client 
```

## Usage

```php
require_once __DIR__.'/vendor/autoload.php';

$client = new \CodexSoft\UnisenderOneApiSdk\Client('YOUR_USERNAME', '<YOUR_API_KEY>');
$balance = $client->getBalance();

$client->sendEmail([
    'body' => [
        'html' => '<h1>Hello world</h1>',
        'plaintext' => 'Hello world',
    ],
    'subject' => 'Example subject',
    'from_email' => 'noreply@example.com',
    'from_name' => 'John Doe',
    'reply_to' => 'reply@example.com',
    'track_links' => 1,
    'track_read' => 1,
    'headers' => [
        'X-ReplyTo' => 'reply@example.com',
    ],
    'recipients' => [
        [
            'email' => 'jane@example.com',
            'substitutions' => [],
            'metadata' => [],
        ]
    ],
    'metadata' => [],
    'attachments' => [],
    'inline_attachments' => [],
    'options' => [],
]);
```

For more information see https://www.unisender.com/en/features/unione/
