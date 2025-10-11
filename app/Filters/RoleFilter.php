<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session('isLoggedIn')) {
            return redirect()->to(site_url('login'))->with('error', 'Please log in to access this page.');
        }

        $requiredRole = $arguments[0] ?? null;
        $userRole = session('role');

        if ($requiredRole && $userRole !== $requiredRole) {
            return redirect()->to(site_url('dashboard'))->with('error', 'You do not have permission to access this page.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
