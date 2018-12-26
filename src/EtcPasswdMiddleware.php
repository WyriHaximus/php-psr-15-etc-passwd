<?php declare(strict_types=1);

namespace WyriHaximus\Psr15\EtcPasswd;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

final class EtcPasswdMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $passwdContents = '';

    /**
     * @var string
     */
    private $shadowContents = '';

    public function __construct(iterable $users)
    {
        $this->passwdContents = \implode(
            \PHP_EOL,
            \iterator_to_array($this->createPasswdContents($users))
        );
        $this->shadowContents = \implode(
            \PHP_EOL,
            \iterator_to_array($this->createShadowContents($users))
        );
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getMethod() === 'GET' && $request->getUri()->getPath() === '/etc/passwd') {
            return $this->createResponse($this->passwdContents);
        }

        if ($request->getMethod() === 'GET' && $request->getUri()->getPath() === '/etc/shadow') {
            return $this->createResponse($this->shadowContents);
        }

        return $handler->handle($request);
    }

    private function createPasswdContents(iterable $users): iterable
    {
        foreach ($users as $user => $password) {
            yield $user . ':x:' . \crc32($user) . ':0:99999:7:::';
        }
    }

    private function createShadowContents(iterable $users): iterable
    {
        foreach ($users as $user => $password) {
            yield $user . ':$1$$' . \base64_encode(\md5($password)) . ':' . \crc32($user) . ':0:99999:7:::';
        }
    }

    private function createResponse(string $contents): ResponseInterface
    {
        $body = new Stream('php://temp', 'wb+');
        $body->write($contents);
        $body->rewind();

        return (new Response())->
            withStatus(200)->
            withHeader('Content-Type', 'text/plain')->
            withBody($body);
    }
}
