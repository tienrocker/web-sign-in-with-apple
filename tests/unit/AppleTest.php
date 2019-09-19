<?php declare(strict_types=1);

use Apple\Apple;
use Firebase\JWT\ExpiredException;
use PHPUnit\Framework\TestCase;

class AppleTest extends TestCase
{
    public function testExpiredException()
    {
        $this->expectException(ExpiredException::class);
        Apple::verify_base64('ZXlKcmFXUWlPaUpCU1VSUFVFc3hJaXdpWVd4bklqb2lVbE15TlRZaWZRLmV5SnBjM01pT2lKb2RIUndjem92TDJGd2NHeGxhV1F1WVhCd2JHVXVZMjl0SWl3aVlYVmtJam9pWkdWMkxtTnNaM1F1WW1SdWF5SXNJbVY0Y0NJNk1UVTJPRGcyTlRBd055d2lhV0YwSWpveE5UWTRPRFkwTkRBM0xDSnpkV0lpT2lJd01ERXhPVGt1TnpSaFlUZ3pOV1F3TWpVeE5HUmhPRGd3TW1JMFpHTXdPRGsxT1dZeFl6a3VNVEExT1NJc0ltTmZhR0Z6YUNJNklrWTRjVlJ2TW5oTE1sSlhlbGhqTFRZdGJFdEdOR2NpTENKbGJXRnBiQ0k2SWpOaWNuTjFORGR4ZDNkQWNISnBkbUYwWlhKbGJHRjVMbUZ3Y0d4bGFXUXVZMjl0SWl3aVpXMWhhV3hmZG1WeWFXWnBaV1FpT2lKMGNuVmxJaXdpWVhWMGFGOTBhVzFsSWpveE5UWTRPRFkwTkRBM2ZRLkYzc2tIQjFFUlZ3R2pGbVJVUXlwaFB6SFQ2bnZmMmxtc2o1WWE5dW5iNkw1eFdNeWdBY29OU0tGQk41R0ZFUEJsaHZSdndMX01Cc19LaXBraEcyRTN6dE5mOEszSlBKRExFdnFZMU5Tc0hYZnRCWm5lVEhROFE5TllKVlN0Q2dRdzFKd1ZSekpCRzg5RDY0OURYbTVkQW0yR3VaejlJY1pWbWJqLUlHeFdacU9UWmVNMXVnX1l2N1RYejhNZlBwM1dkQmFsMW5nSENlNGNEdk93eTlhZDhzYzZ0QVJzUFV0SHVVb2ZVeTNaQzNIcnFlOGVRa29laU5yMHJKeWRGM3lsSG1zSzQtME1XY2kyODU5aUJRM01UaFpjRDd6VkU2NGRYZm1fU0Y5UHdNX2hWTTlFWHFtVHFTd2tIOG9MTzdoemh5cG9qNF83UzRyeUh3Z0hkVHk5Zw==');
    }

    public function testCreateClientSecret()
    {
        include_once __DIR__ . '/../../example/config.php';
        $client_secret = Apple::create_client_secret();
        $this->assertEquals('ES256', $client_secret->getHeader('alg'));
    }
}