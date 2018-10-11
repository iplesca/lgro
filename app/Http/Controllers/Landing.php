<?php
namespace App\Http\Controllers;

class Landing extends Controller
{
    public function index()
    {
        $this->refreshWgCsrf();
        return $this->useView('landing');
    }
}

