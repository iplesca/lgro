<?php

namespace App\Http\Middleware;

use Silber\Bouncer\BouncerFacade as Bouncer;

use Closure;

class ScopeBouncer
{
    const GLOBAL_TENANT = -1;
    /**
     * The Bouncer instance.
     *
     * @var \Silber\Bouncer\Bouncer
     */
    protected $bouncer;

    /**
     * Constructor.
     *
     * @param \Silber\Bouncer\Bouncer  $bouncer
     */
    public function __construct(Bouncer $bouncer)
    {
        $this->bouncer = $bouncer;
    }

    /**
     * Set the proper Bouncer scope for the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $tenantId = self::GLOBAL_TENANT;
        $user = $request->user();

        if (!is_null($user)) {
            $tenantId = $request->user()->membership->clan_id;
        }

        Bouncer::scope()->to($tenantId)->onlyRelations();

        return $next($request);
    }
}
