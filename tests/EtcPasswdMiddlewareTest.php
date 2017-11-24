<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Psr15\EtcPasswd;

use Interop\Http\Server\RequestHandlerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use WyriHaximus\Psr15\EtcPasswd\EtcPasswdMiddleware;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

final class EtcPasswdMiddlewareTest extends TestCase
{
    public function provideUsers(): iterable
    {
        yield [
            [
                'root' => 'beer',
            ],
        ];

        yield [
            [
                'root' => 'beer',
                'beer' => 'root',
            ],
        ];
    }

    /**
     * @dataProvider provideUsers
     */
    public function testPasswd(array $users): void
    {
        $request = (new ServerRequest())->withMethod('GET')->withUri(new Uri('/etc/passwd'));
        $requestHandler = $this->prophesize(RequestHandlerInterface::class)->reveal();
        $etcPasswd = new EtcPasswdMiddleware($users);
        $response = $etcPasswd->process($request, $requestHandler);
        self::assertTrue($response->hasHeader('Content-Type'));
        self::assertSame('text/plain', $response->getHeaderLine('Content-Type'));
        $body = $response->getBody()->getContents();
        foreach (explode(PHP_EOL, $body) as $i => $line) {
            list($user, $password, $extras) = explode(':', $line, 3);
            self::assertSame('x', $password);
            self::assertSame(crc32($user) . ':0:99999:7:::', $extras);
        }
    }

    /**
     * @dataProvider provideUsers
     */
    public function testShadow(array $users): void
    {
        $request = (new ServerRequest())->withMethod('GET')->withUri(new Uri('/etc/shadow'));
        $requestHandler = $this->prophesize(RequestHandlerInterface::class)->reveal();
        $etcPasswd = new EtcPasswdMiddleware($users);
        $response = $etcPasswd->process($request, $requestHandler);
        self::assertTrue($response->hasHeader('Content-Type'));
        self::assertSame('text/plain', $response->getHeaderLine('Content-Type'));
        $body = $response->getBody()->getContents();
        foreach (explode(PHP_EOL, $body) as $i => $line) {
            list($user, $password, $extras) = explode(':', $line, 3);
            self::assertSame('$1$$' . base64_encode(md5($users[$user])), $password);
            self::assertSame(crc32($user) . ':0:99999:7:::', $extras);
        }
    }

    public function provideNonMatchingRequests(): iterable
    {
        yield [
            (new ServerRequest())->withMethod('POST')->withUri(new Uri('/etc/passwd')),
        ];

        yield [
            (new ServerRequest())->withMethod('PUT')->withUri(new Uri('/etc/passwd')),
        ];

        yield [
            (new ServerRequest())->withMethod('DELETE')->withUri(new Uri('/etc/passwd')),
        ];

        yield [
            (new ServerRequest())->withMethod('POST')->withUri(new Uri('/etc/shadow')),
        ];

        yield [
            (new ServerRequest())->withMethod('PUT')->withUri(new Uri('/etc/shadow')),
        ];

        yield [
            (new ServerRequest())->withMethod('DELETE')->withUri(new Uri('/etc/shadow')),
        ];

        yield [
            (new ServerRequest())->withMethod('GET')->withUri(new Uri('/etc/mordor')),
        ];

        yield [
            (new ServerRequest())->withMethod('GET')->withUri(new Uri('/etc/shire')),
        ];

        yield [
            (new ServerRequest())->withMethod('GET')->withUri(new Uri('/etc/hosts')),
        ];

        yield [
            (new ServerRequest())->withMethod('PATCH')->withUri(new Uri('/etc/azeroth')),
        ];
    }

    /**
     * @dataProvider provideNonMatchingRequests
     */
    public function testNonMatchingRequests(RequestInterface $request): void
    {
        $response = new Response();
        $etcPasswd = new EtcPasswdMiddleware([]);
        $requestHandler = $this->prophesize(RequestHandlerInterface::class);
        $requestHandler->handle($request)->shouldBeCalled()->willReturn($response);
        $returnedResponse = $etcPasswd->process($request, $requestHandler->reveal());
        self::assertSame($response, $returnedResponse);
    }
}
