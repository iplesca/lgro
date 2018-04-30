<?php

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Roles
        Bouncer::role()->create([
            'name' => 'demo',
            'title' => 'Demo account',
        ]);
        Bouncer::role()->create([
            'name' => 'guest',
            'title' => 'Guest account',
        ]);
        Bouncer::role()->create([
            'name' => 'member',
            'title' => 'Member',
        ]);
        Bouncer::role()->create([
            'name' => 'officer',
            'title' => 'Officer',
        ]);
        Bouncer::role()->create([
            'name' => 'recruiter',
            'title' => 'Recruitment Officer',
        ]);
        Bouncer::role()->create([
            'name' => 'combat',
            'title' => 'Combat Officer',
        ]);
        Bouncer::role()->create([
            'name' => 'personnel',
            'title' => 'Personnel Officer',
        ]);
        Bouncer::role()->create([
            'name' => 'executive',
            'title' => 'Executive Commander',
        ]);
        Bouncer::role()->create([
            'name' => 'admin',
            'title' => 'Commander',
        ]);
        Bouncer::role()->create([
            'name' => 'superadmin',
            'title' => 'Super Admin',
        ]);
        /**
         * Abilities
         */
        /** General */
        Bouncer::ability()->create([
            'name' => 'login',
            'title' => 'Login',
        ]);
        Bouncer::ability()->create([
            'name' => 'banned',
            'title' => 'Banned',
        ]);
        Bouncer::ability()->create([
            'name' => 'view',
            'title' => 'View',
        ]);
        Bouncer::ability()->create([
            'name' => 'list',
            'title' => 'List',
        ]);
        Bouncer::ability()->create([
            'name' => 'access',
            'title' => 'Access',
        ]);
        Bouncer::ability()->create([
            'name' => 'create',
            'title' => 'Create',
        ]);
        Bouncer::ability()->create([
            'name' => 'modify',
            'title' => 'Modify',
        ]);
        Bouncer::ability()->create([
            'name' => 'update',
            'title' => 'Update',
        ]);
        Bouncer::ability()->create([
            'name' => 'delete',
            'title' => 'Delete',
        ]);
        Bouncer::ability()->create([
            'name' => 'send',
            'title' => 'Send',
        ]);
        Bouncer::ability()->create([
            'name' => 'take',
            'title' => 'Take',
        ]);
        /** Specific */
//        Bouncer::ability()->create([
//            'name' => 'view-member',
//            'title' => 'View clan info',
//        ]);
        /*
        Bouncer::ability()->create([
            'name' => 'remove-view',
            'title' => 'Remove view',
        ]);
        Bouncer::ability()->create([
            'name' => 'remove-list',
            'title' => 'Remove list',
        ]);
        Bouncer::ability()->create([
            'name' => 'remove-access',
            'title' => 'Remove access',
        ]);
        Bouncer::ability()->create([
            'name' => 'remove-create',
            'title' => 'Remove create',
        ]);
        Bouncer::ability()->create([
            'name' => 'remove-modify',
            'title' => 'Remove modify',
        ]);
        Bouncer::ability()->create([
            'name' => 'remove-update',
            'title' => 'Remove update',
        ]);
        Bouncer::ability()->create([
            'name' => 'remove-delete',
            'title' => 'Remove delete',
        ]);
        Bouncer::ability()->create([
            'name' => 'remove-send',
            'title' => 'Remove send',
        ]);
        Bouncer::ability()->create([
            'name' => 'remove-take',
            'title' => 'Remove take',
        ]);
        */
        // Login
        Bouncer::allow('guest')->to('login');
        Bouncer::allow('member')->to('login');

//        // Member
        Bouncer::forbid('guest')->to(['access'], \App\Models\Member::class);
        Bouncer::allow('member')->toOwn(\App\Models\Member::class);
        Bouncer::allow('member')->to(['access', 'view'], \App\Models\Member::class);
//
//        // MemberPrivate
//        Bouncer::allow('member')->toOwn(MemberPrivate::class);
//
//        // Tickets
//        Bouncer::allow('member')->toOwn(Tickets::class);
//        Bouncer::allow('executive')->to(['list'], Tickets::class);
//
//        // SuperAdmin
//        Bouncer::allow('superadmin')->everything();
    }
}
/*
General:
login - can login into the app
banned - can't login or do anything

All purpose abilities:
view - on rendering content
list - on viewing a list
access - on loading content
create - on creating content
modify - on updating own content
update - on updating any content
delete - on deleting content (including soft deletes)
send - send action
take - take ownership

remove - delete one ability
    > remove-view - retract view
    > remove-access - retract access
    > remove-create - retract create
    > remove-modify - retract modify
    > remove-update - retract update
    > remove-delete - retract delete

Models:
User :: all-data user profile
    * Stats - user statistics
    * Garage - user tank list
    * Private - user private data
    * Tank - user specific tank

Clan :: all-data clan info
    * Info page
    * Members
    * GeneralStats - general statistics

Tickets :: communication messages

*/
/*
Roles:
0. demo
1. guest
    -- login
    Clan > Info
        -- view

    Clan > Members
        -- list
        -- view
    Clan > GeneralStats
        -- view
    User
        !- access
2. member (reservist, private, recruit)
    -- login
    Clan > Info
        -- view

    Clan > Members
        -- list
        -- view
    Clan > GeneralStats
        -- view
    User
        -- access
        -- list
        -- view
    User > Private
        -- owner
        !- access
    Tickets
        -- owner
        -- create
        -- send

3. officer (junior_officer, recruitment_officer, combat_officer, personnel_officer, quartermaster)
    2.1. recruiter (recruitment_officer)
    2.2. combat (combat_officer)
    2.3. personnel (personnel_officer)
4. executive (executive_officer)
    Tickets
        -- owner
        -- list
5. commander (commander)
    Tickets
        -- owner
        -- list

reservist
private
combat_officer
executive_officer
junior_officer
recruitment_officer
commander
recruit
intelligence_officer

















 */