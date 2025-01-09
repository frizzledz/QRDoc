<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Homepage extends BaseController
{
    public function index()
    {
        $data['title'] = 'Homepage';

        return view('homepage', $data);
    }
}
