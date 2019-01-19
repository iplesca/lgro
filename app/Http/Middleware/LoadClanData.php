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
        $badCall = false;
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
        $a = $request->getHost();
        if ($clanId !== -1) {
            $result = ClanManager::loadDataById($clanId);

            // preserve found clan
            if ($result) {
                $clan = ClanManager::getClan();
            }
        }

        // look for a potential clan tag anyway
        $urlClanTag = $this->getClanTag($request);

        // are we on a subdomain?
        if ($isSubdomain) {
            // yes, is there a clan tag provided?
            if (!empty($urlClanTag)) {
                // yes, does it match the subdomain clan?
                if (strtolower($urlClanTag) === strtolower($clan->tag)) {
                    // yes, do them a favour and strip the tag
                    $stripTag = true;
                } else {
                    // nope, bad call
                    $badCall = true;
                }
            }
        } else {
            // no subdomain, is there a clan tag?
            if (!empty($urlClanTag)) {
                // yes, is it a valid tag?
                if (ClanManager::identifyTag($urlClanTag)) {
                    // yes, does the clan have a subdomain?
                    if (! is_null(ClanManager::getClan()->subdomain)) {
                        // yes, lets redirect to that subdomain and strip the tag
                        $redirect = true;
                        $stripTag = true;
                    }
                    // else - no subdomain, do nothing
                } else {
                    // no, clan tag bad and no subdomain, fail
                    $badCall = true;
                }
            }
        }

        // is it a bad call?
        if ($badCall) {
            throw new AuthorizationException();
        }

        // habemus clan, what to do
        $newUri = $request->getRequestUri();
        if ($stripTag) {
            $newUri = str_replace($request->segment(1), '', $request->getRequestUri());
        }

        if ($isSubdomain) {
            $sub = ClanManager::getSubdomain($request->secure());
            return redirect($sub . $newUri);
        } else {
            $request->server->set('REQUEST_URI', $newUri);
            $request = Request::createFrom($request);
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
