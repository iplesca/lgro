<?php

namespace App\Http\Middleware;

use App\Managers\ClanManager;
use App\Models\Clan;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $isSubdomain = false;
        $stripTag = false;
        $redirect = false;
        $result = false;
        $clanId = -1;
        $clan = false;

        // is there a clan from a previous redirect?
//        if (ClanManager::isClan()) {
//            $clanId = ClanManager::getClanId();
//        } else {
            // ... or are we on a subdomain
            $envClanId = (int) getenv('CLAN_ID');
            if ($envClanId !== $clanId) {
                $clanId = $envClanId;
                $isSubdomain = true;
            }
//        }

        if ($clanId !== -1) {
            $result = ClanManager::loadDataById($clanId);

            // preserve found clan
            if ($result) {
                $clan = ClanManager::getClan();
            }
        }

        // look for a potential clan tag anyway
        $urlClanTag = $this->getClanTag($request);

        if ($isSubdomain) {
            if (!empty($urlClanTag)) {
                if (strtolower($urlClanTag) === strtolower($clan->tag)) {
                    $stripTag = true;
                } else {
                    $badCall = true;
                }
            }
        } else {
            if (!empty($urlClanTag)) {
                if (ClanManager::identifyTag($urlClanTag)) {
                    if (! is_null(ClanManager::getClan()->subdomain)) {
                        $redirect = true;
                        $stripTag = true;
                    }
                } else {
                    $badCall = true;
                }
            }
        }

        // is there a clan tag and no subdomain?
        if (! $isSubdomain && !empty($urlClanTag)) {
            $result = ClanManager::identifyTag($urlClanTag);
        }

        // is there a clan id but nothing was found as clan?
        if (! $result && $clanId != -1) {
            // then it is an unauthorized access
            throw new AuthorizationException();
        }

        // if there is a clan found and a clan tag
        // rebuild request, either redirect to subdomain or strip the clan tag from the URL
        if ($result !== null && $urlClanTag) {
            $newUri = str_replace($request->segment(1), '', $request->getRequestUri());

            if ($newUri == '/') {
                $newUri = '/:' . strtolower($urlClanTag);
            }
            $request->server->set('REQUEST_URI', $newUri);

            $sub = ClanManager::getSubdomain($request->secure());
            if ($sub) {
                return redirect($sub . $newUri);
            } else {
                $request = Request::createFrom($request);
            }
        }

        // set constants for the clan
        $this->setConstants(ClanManager::getClan(), $clanId);

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
    private function setConstants(Clan $foundClan = null, $foundClanId = -1)
    {
        // set default template
        $template = (-1 !== $foundClanId) ? 'standard' : 'isteam';

        if (! is_null($foundClan) && $foundClan->template) {
            // use clan template
            $template = $foundClan->template;
        }

        if (! defined('CLAN_ID')) {
            define('CLAN_ID', $foundClanId);
        }
        if (! defined('ISTEAM_TEMPLATE')) {
            define('ISTEAM_TEMPLATE', $template);
        }

        View::share('clanData', $foundClan);
    }
}
