<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Competition
 *
 * @mixin \Eloquent
 */
	class Competition extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tank
 *
 * @property int $id
 * @property int $account_id
 * @property int $wargaming_id
 * @property string $updated
 * @property int $in_garage
 * @property int|null $wn8
 * @property int|null $wn8_30
 * @property string $mastery
 * @property int $max_xp
 * @property int $max_frags
 * @property int $battles
 * @property int $wins
 * @property int $losses
 * @property int $dropped_capture_points
 * @property int $capture_points
 * @property int $xp
 * @property int $frags
 * @property int $damage_dealt
 * @property int $spotted
 * @property int $battles_on_stunning_vehicles
 * @property int $survived_battles
 * @property int $hits_percents
 * @property int $draws
 * @property int $damage_received
 * @property int $stun_number
 * @property int $stun_assisted_damage
 * @property int $shots
 * @property int $hits
 * @property int $battle_avg_xp
 * @property-read \App\Models\Account $account
 * @method static Tank whereAccountId($value)
 * @method static Tank whereBattleAvgXp($value)
 * @method static Tank whereBattles($value)
 * @method static Tank whereBattlesOnStunningVehicles($value)
 * @method static Tank whereCapturePoints($value)
 * @method static Tank whereDamageDealt($value)
 * @method static Tank whereDamageReceived($value)
 * @method static Tank whereDraws($value)
 * @method static Tank whereDroppedCapturePoints($value)
 * @method static Tank whereFrags($value)
 * @method static Tank whereHits($value)
 * @method static Tank whereHitsPercents($value)
 * @method static Tank whereId($value)
 * @method static Tank whereInGarage($value)
 * @method static Tank whereLosses($value)
 * @method static Tank whereMastery($value)
 * @method static Tank whereMaxFrags($value)
 * @method static Tank whereMaxXp($value)
 * @method static Tank whereShots($value)
 * @method static Tank whereSpotted($value)
 * @method static Tank whereStunAssistedDamage($value)
 * @method static Tank whereStunNumber($value)
 * @method static Tank whereSurvivedBattles($value)
 * @method static Tank whereUpdated($value)
 * @method static Tank whereWargamingId($value)
 * @method static Tank whereWins($value)
 * @method static Tank whereWn8($value)
 * @method static Tank whereWn830($value)
 * @method static Tank whereXp($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TankStat[] $stats
 * @property-read \App\Models\TankDefinition $details
 */
	class Tank extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 * Class User
 *
 * @package App\Models
 * @property int $id
 * @property int|null $member_id
 * @property int $first
 * @property int $wargaming_id
 * @property string $nickname
 * @property string|null $name
 * @property string|null $email
 * @property string|null $password
 * @property string $wot_language
 * @property string $wot_token
 * @property string|null $wot_token_expire
 * @property string|null $wot_created_at
 * @property string|null $wot_updated_at
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Silber\Bouncer\Database\Ability[] $abilities
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\Member $membership
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Silber\Bouncer\Database\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TankDefinition[] $tanks
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIs($role)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsAll($role)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsNot($role)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWargamingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWotCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWotLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWotToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWotTokenExpire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereWotUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TankDefinition
 *
 * @property int $wargaming_id
 * @property string $nation
 * @property int $tier
 * @property string $type
 * @property string $name
 * @property string $name_short
 * @property string $name_uri
 * @property string $premium
 * @property string $image
 * @property string $image_small
 * @property string $image_contour
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereImageContour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereImageSmall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereNameShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereNameUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereNation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition wherePremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereTier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TankDefinition whereWargamingId($value)
 * @mixin \Eloquent
 */
	class TankDefinition extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Clan
 *
 * @property int $id
 * @property int $wargaming_id
 * @property string $name
 * @property string $tag
 * @property string $description
 * @property string $status
 * @property string $emblem32
 * @property string $emblem64
 * @property string $emblem195
 * @property string $color
 * @property string $motto
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Member[] $members
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereEmblem195($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereEmblem32($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereEmblem64($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereMotto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Clan whereWargamingId($value)
 * @mixin \Eloquent
 */
	class Clan extends \Eloquent {}
}

namespace App\Models\Api{
/**
 * This file is part of the isteam project.
 * 
 * Date: 15/01/18 07:31
 *
 * @author ionut
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Member[] $members
 */
	class ClanQuery extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Member
 *
 * @property int $id
 * @property int $first
 * @property int $clan_id
 * @property int|null $user_id
 * @property int $wargaming_id
 * @property int|null $wn8
 * @property int|null $wn8_30
 * @property string $nickname
 * @property string $role
 * @property string $granted
 * @property string|null $joined
 * @property int|null $score
 * @property int|null $premium
 * @property string|null $premium_expire
 * @property int|null $credits
 * @property int|null $gold
 * @property int|null $bonds
 * @property int|null $free_xp
 * @property int|null $ban_time
 * @property string|null $ban_info
 * @property int|null $phone_link
 * @property int|null $battle_time
 * @property string|null $logout
 * @property array $stats
 * @property-read \App\Models\Clan $clan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereBanInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereBanTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereBattleTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereBonds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereClanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereFreeXp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereGold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereGranted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereJoined($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereLogout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member wherePhoneLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member wherePremium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member wherePremiumExpire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereWargamingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereWn8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereWn830($value)
 * @mixin \Eloquent
 * @property int|null $account_id
 * @property int $online
 * @property int $hibernate
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Account $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TankStat[] $tankStats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tank[] $tanks
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereHibernate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Member whereUpdatedAt($value)
 */
	class Member extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AccountsHistory
 *
 * @property int $id
 * @property int $wargaming_id
 * @property int $clan_wargaming_id
 * @property string $reason
 * @property string $nickname
 * @property string $role
 * @property string|null $joined
 * @property string|null $left
 * @property int $days
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereClanWargamingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereJoined($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereWargamingId($value)
 * @mixin \Eloquent
 * @property int $account_id
 * @property-read \App\Models\Account $account
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AccountsHistory whereAccountId($value)
 */
	class AccountsHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TankStat
 *
 * @property int $id
 * @property int $account_id
 * @property int $wargaming_id
 * @property string $type
 * @property int|null $wn8
 * @property int|null $wn8_30
 * @property string $mastery
 * @property int $battles
 * @property int $wins
 * @property int $losses
 * @property int $dropped_capture_points
 * @property int $capture_points
 * @property int $xp
 * @property int $frags
 * @property int $damage_dealt
 * @property int $spotted
 * @property int $battles_on_stunning_vehicles
 * @property int $survived_battles
 * @property int $hits_percents
 * @property int $draws
 * @property int $damage_received
 * @property int $stun_number
 * @property int $stun_assisted_damage
 * @property int $shots
 * @property int $hits
 * @property int $battle_avg_xp
 * @property string $date
 * @property-read \App\Models\Account $account
 * @method static TankStat whereAccountId($value)
 * @method static TankStat whereBattleAvgXp($value)
 * @method static TankStat whereBattles($value)
 * @method static TankStat whereBattlesOnStunningVehicles($value)
 * @method static TankStat whereCapturePoints($value)
 * @method static TankStat whereDamageDealt($value)
 * @method static TankStat whereDamageReceived($value)
 * @method static TankStat whereDate($value)
 * @method static TankStat whereDraws($value)
 * @method static TankStat whereDroppedCapturePoints($value)
 * @method static TankStat whereFrags($value)
 * @method static TankStat whereHits($value)
 * @method static TankStat whereHitsPercents($value)
 * @method static TankStat whereId($value)
 * @method static TankStat whereLosses($value)
 * @method static TankStat whereMastery($value)
 * @method static TankStat whereShots($value)
 * @method static TankStat whereSpotted($value)
 * @method static TankStat whereStunAssistedDamage($value)
 * @method static TankStat whereStunNumber($value)
 * @method static TankStat whereSurvivedBattles($value)
 * @method static TankStat whereType($value)
 * @method static TankStat whereWargamingId($value)
 * @method static TankStat whereWins($value)
 * @method static TankStat whereWn8($value)
 * @method static TankStat whereWn830($value)
 * @method static TankStat whereXp($value)
 * @mixin \Eloquent
 */
	class TankStat extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Account
 *
 * @property int $id
 * @property int|null $member_id
 * @property int|null $user_id
 * @property int $wargaming_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AccountsHistory[] $history
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Account whereWargamingId($value)
 * @mixin \Eloquent
 */
	class Account extends \Eloquent {}
}

namespace App\Competition{
/**
 * This file is part of the isteam project.
 * 
 * Date: 29/12/17 10:14
 *
 * @author ionut
 * @property-read \App\Competition\MatchData $match
 * @mixin \Eloquent
 */
	class Game extends \Eloquent {}
}

namespace App\Competition{
/**
 * App\Competition\MatchData
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Competition\Game[] $games
 */
	class MatchData extends \Eloquent {}
}

