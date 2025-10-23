<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $isLoggedIn = (bool) $session->get('isLoggedIn');
        $role = $session->get('role') ?? 'student';
        $path = trim($request->getUri()->getPath(), '/');

        // If not logged in, bounce to login and remember where to return
        if (! $isLoggedIn) {
            $session->set('redirect_url', current_url());
            return redirect()->to(site_url('login'))->with('error', 'Please log in first.');
        }

        // Admin can access any /admin route
        if (str_starts_with($path, 'admin')) {
            if ($role === 'admin') {
                return; // allow
            }
            return redirect()->to(site_url('announcements'))
                ->with('error', 'Access Denied: Insufficient Permissions');
        }

        // Teacher can access only /teacher routes
        if (str_starts_with($path, 'teacher')) {
            if ($role === 'teacher') {
                return; // allow
            }
            return redirect()->to(site_url('announcements'))
                ->with('error', 'Access Denied: Insufficient Permissions');
        }

        // Student is restricted to /student and /announcements (handled if applied there)
        if (str_starts_with($path, 'student')) {
            if ($role === 'student') {
                return; // allow
            }
            return redirect()->to(site_url('announcements'))
                ->with('error', 'Access Denied: Insufficient Permissions');
        }

        // If filter is applied elsewhere, allow announcements for students only
        if ($path === 'announcements') {
            return; // open route for all logged roles per requirement
        }

        // Default allow
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}
