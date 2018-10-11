<?php

namespace App\Http\Middleware;

use App\Managers\ClanManager;
use App\Models\Clan;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LoadClanData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        $result = null;
        $clanId = ClanManager::isClan() ? ClanManager::getClanId() : (int) getenv('CLAN_ID');

        if (!empty($clanId) && $clanId != -1) {
            $result = ClanManager::loadDataById($clanId);
        }
        // look for a potential clan tag anyway
        $clanTag = $this->getClanTag($request);

        $sub = ClanManager::getSubdomain($request->secure());

        // no clan subdomain
        if ($sub === false) {
            if (!empty($clanTag)) {
                $result = ClanManager::identifyTag($clanTag);
            }
        }
        if (! $result && $clanId != -1) {
            throw new AuthorizationException();
        }
        if ($result !== null && $clanTag) {
            // rebuild request
            $newUri = str_replace($request->segment(1), '', $request->path());

            $request->server->set('REQUEST_URI', $newUri);

            $sub = ClanManager::getSubdomain($request->secure());
            if ($sub) {
                return redirect($sub . $newUri);
            } else {
                $request = Request::createFrom($request);
            }
        }
        $this->loadClan(ClanManager::getClan(), $clanId);

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function getClanTag($request)
    {
        $tag = $request->segment(1);
        $result = false;

        if (preg_match('~^:([-a-z_]{3,5})$~i', $tag, $result)) {
            $result = $result[1];
        } else {
            $result = false;
        }
        return $result;
    }
    private function loadClan(Clan $clan = null, $clanId = -1)
    {
        if (! is_null($clan)) {
            $clanId = $clan->wargaming_id;
        }

        $template = (-1 !== $clanId) ? 'standard' : 'isteam';
        if (! is_null($clan) && $clan->template) {
            $template = $clan->template;
        }
        if (! defined('CLAN_ID')) {
            define('CLAN_ID', $clanId);
        }
        if (! defined('ISTEAM_TEMPLATE')) {
            define('ISTEAM_TEMPLATE', $template);
        }

        View::share('clanData', $clan);
    }
}
