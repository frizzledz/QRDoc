<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthCookie implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {

        if (!session()->auth and isset($_COOKIE['auth'])) {

            // load helper
            helper('aeshash');

            // decode cookie
            $hash = aeshash('dec', $_COOKIE['auth'], config('Encryption')->key);

            // validate hash
            if (!$hash) {
                // hash invalid > remove cookie
                setcookie('auth', 0, -1, '/');
            } else {
                $read = json_decode($hash, true);
                session()->set('auth', $read);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
