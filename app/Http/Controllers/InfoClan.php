<?php
namespace App\Http\Controllers;

class InfoClan extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome()
    {
        return $this->useView('info-welcome');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rules()
    {
        return $this->useView('info-rules');
    }
}
