<?php
namespace App\Administration;

/**
 * This file is part of the isteam project.
 *
 * Date: 07/01/18 23:06
 * @author ionut
 */
use App\Models\Tank;
use App\Wn8;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Models\Clan;
use App\Models\Member;

class PlayerActions extends Base
{
    /**
     * leeway for the scripts to finish
     */
    const AFTER_MIN = 5;
    const PAGE_SIZE = 100;
    protected $pageSize;
    protected $extra = [];

    public function init($pageSize = false)
    {
        $this->pageSize = self::PAGE_SIZE;
        if ($pageSize) {
            $this->pageSize = $pageSize;
        }
    }
    /**
     * Refresh access tokens of all members for all clans
     */
    public function checkAllTokens()
    {
        Log::info('[cron][check tokens] running');

        $clans = Clan::all();
        foreach ($clans as $clan) {
            $this->checkTokens($clan);
        }
    }

    /**
     * Refresh the access token for all members of a clan
     * @param Clan $clan
     */
    public function checkTokens(Clan $clan)
    {
        $today = Carbon::today();
        $offset = Carbon::tomorrow();
        $updated = 0;
        // get members with active WG tokens that are about to expire by tomorrow
        $members = Member::has('user')->with('user')
            ->where('clan_id', $clan->id)
            ->whereHas('user', function (Builder $query) use ($today, $offset) {
                $query->where('wot_token_expire', '>=', $today);
                $query->where('wot_token_expire', '<=', $offset);
            })
            ->get();
        foreach ($members as $member) {
            $updated += $this->refreshToken($member) ? 1 : 0;
        }
        Log::info('[cron][check tokens] Clan: '.$clan->name . '. Expiring: '. count($members).
            '. Updated: ' . $updated);
    }

    /**
     * Refresh the access token for a member
     * @param Member $member
     * @return bool
     */
    public function refreshToken(Member $member)
    {
        $token = $this->getPlayerToken($member);
        $data = $this->api->tanks()->getPlayerNewToken($token);
        if (false !== $data) {
            $member->user->updateAccess($data);
        }
        return true;
    }

    /**
     * Update the statistics of a member
     * @param Member $member
     * @return bool
     */
    public function updateStats(Member $member)
    {
        $token = $this->getPlayerToken($member);

        $data = $this->api->tanks()->getPlayerData($member->wargaming_id, $token);
        $member->updateStats($data, $token ? true : false);

        return true;
    }

    /**
     * Returns either a user WarGaming access token (if valid) or false otherwise
     * @param Member $member
     * @return bool|string
     */
    private function getPlayerToken(Member $member)
    {
        $token = false;
        if (!is_null($member->user) && $member->user->isValidWotToken(self::AFTER_MIN)) {
            $token = $member->user->wot_token;
        }
        return $token;
    }
    public function getTankStats(Member $member, $tankIds = [], $extraParams = [])
    {
        $result = [];
        if (!empty($tankIds)) {
            foreach (collect($tankIds)->chunk($this->pageSize) as $tankIds) {
                $result += $this->api->tanks()->getPlayerTankStats(
                    $member->wargaming_id,
                    $this->getPlayerToken($member),
                    $tankIds,
                    $this->extra,
                    $extraParams
                );
            }
        } else {
            $result = $this->api->tanks()->getPlayerTankStats(
                $member->wargaming_id,
                $this->getPlayerToken($member),
                [],
                $this->extra,
                $extraParams
            );
        }
        return $result;
    }
    /**
     * Updates stats for a list of tank-ids for the given member.
     * @param Member $member
     * @param array $tankWargamingIds
     */
    public function updateTankListStats(Member $member, $tankWargamingIds)
    {
        $tanksData = $this->getTankStats($member, $tankWargamingIds);
        foreach ($tanksData as $tankData) {
            Tank::updateStats($member, $tankData, $this->extra);
        }
    }
    /**
     * Updates all tank stats for the given member (filtered by internal $extraParams)
     * @param Member $member
     * @return boolean
     */
    public function updateTankStats(Member $member)
    {
        // get only tanks in garage
        $extraParams = [
            'in_garage' => 1
        ];

        $tanksData = $this->getTankStats($member, [], $extraParams);
        foreach ($tanksData as $tankData) {
            Tank::updateStats($member, $tankData, $this->extra);
        }

        return true;
    }
    /**
     * Fetches ALL tanks for a given member
     * @param Member $member
     * @return array
     */
    public function getTanks(Member $member)
    {
        $token = $this->getPlayerToken($member);
        $tanks = $this->api->tanks()->getPlayerTanks($member->wargaming_id, $token);

        return $tanks;
    }

    /**
     * Reinstates a new/old member to a clan
     * @param array $memberData WarGaming member data
     * @param Clan $clan
     * @return Member
     */
    public function reinstateMember(array $memberData, Clan $clan)
    {
        // check for account and previous membership
        $result = Member::reinstate($memberData, $clan);
        /**
         * @var Member $member
         */
        $member = $result['member'];

        if ($result['isNew']) {
            $tanks = $member->createTanks($this->getTanks($member));
            $this->updateTankListStats($member, $tanks);
        }

        $this->updateStats($member);

        return $member;
    }
    public function updateWn8(Member $member)
    {
        /**
         * @var Wn8 $wn8
         */
        $wn8 = App::make(Wn8::class);
        $wn8->createSet('last30');
        $wn8->resetAll();
        /**
         * @var Tank[] $tanks
         */
        $tanks = $member->tanks()->get();
        if (is_null($tanks)) {
            // @todo report no tanks
            return false;
        }
        foreach ($tanks as $tank) {
            $tankStats = $tank->currentWn8Stat();

            $statDiff['battles'] = 0;
            // calculate WN8 30 days
            $stats = $tank->stats30DaysAgo();
            if (!is_null($stats)) {
                $statDiff = $this->getStatDiff($stats, $tankStats);
            }
            if (0 != $statDiff['battles']) {
//                $statDiff = $tank->currentWn8Stat();

                $tank->wn8_30 = $wn8->tankAndPlayer(
                    $tank->wargaming_id,
                    $statDiff['damage_dealt'],
                    $statDiff['spotted'],
                    $statDiff['frags'],
                    $statDiff['dropped_capture_points'],
                    $statDiff['wins'],
                    $statDiff['battles'],
                    'last30'
                );
            }
            $wn8->addTankData(
                $tank->wargaming_id,
                $tankStats['damage_dealt'],
                $tankStats['spotted'],
                $tankStats['frags'],
                $tankStats['dropped_capture_points'],
                $tankStats['wins'],
                $tankStats['battles']
            );
        }
        $member->wn8_30 = $wn8->player('last30');
        $member->wn8 = $wn8->player();
        $member->save();
        $wn8->resetAll();
    }

    /**
     * Search (and updates) any new tanks
     * @param Member $member
     * @return bool
     */
    public function updateNewTanks(Member $member)
    {
        $allTanks = $this->getTanks($member);
        $tankIds = [];

        foreach ($allTanks as $tank) {
            if (is_null($member->getTankByWargamingId($tank['tank_id']))) {
                $tankIds[] = $tank['tank_id'];
            }
        }

        if (!empty($tankIds)) {
            $this->updateTankListStats($member, $tankIds);
            return true;
        }
        return false;
    }

    /**
     * @param $new
     * @param $old
     * @return array
     */
    private function getStatDiff($old, $new)
    {
        return [
            'damage_dealt' => $old['damage_dealt'] - $new['damage_dealt'],
            'spotted' => $old['spotted'] - $new['spotted'],
            'frags' => $old['frags'] - $new['frags'],
            'dropped_capture_points' => $old['dropped_capture_points'] - $new['dropped_capture_points'],
            'wins' => $old['wins'] - $new['wins'],
            'battles' => $old['battles'] - $new['battles'],
        ];
    }
}
