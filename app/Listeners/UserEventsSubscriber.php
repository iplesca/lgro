<?php
/**
 * This file is part of the isteam project.
 *
 * Date: 04/10/18 07:48
 * @author ionut
 */

namespace App\Listeners;

class UserEventsSubscriber
{
    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
    {
        $a = 1;
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event) {}

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventsSubscriber@onUserLogin'
        );

//        $events->listen(
//            'Illuminate\Auth\Events\Logout',
//            'App\Listeners\UserEventSubscriber@onUserLogout'
//        );
    }
}
