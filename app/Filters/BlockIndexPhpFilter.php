<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class BlockIndexPhpFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $path = ltrim($request->getUri()->getPath(), '/');
        if (stripos($path, 'index.php') === 0) {
            return service('response')->setStatusCode(404)->setBody('Not Found');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
