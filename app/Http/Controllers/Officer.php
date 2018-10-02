<?php
namespace App\Http\Controllers;

class Officer extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recruitment()
    {
        return $this->useView('officer-recruitment');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function combat()
    {
        return $this->useView('officer-combat');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clanWars()
    {
        return $this->useView('officer-clanwars');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function command()
    {
        return $this->useView('officer-command');
    }
}
