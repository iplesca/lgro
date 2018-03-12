<?php
namespace App\Http\Controllers;


use App\Models\Member as MemberModel;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Profile extends Controller
{
    /**
     * @param integer $memberId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($memberId)
    {
        if (empty($memberId) || !is_numeric($memberId)) {
            $member = Auth::user()->member;
        } else {
            $member = Member::with('user')->find($memberId);
        }

        return $this->useView('profile', [
            'user' => $member->user,
            'member' => $member
        ]);
    }
    /**
     * @param integer $memberId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tanks($memberId = null)
    {
        $typeOrder = array_flip(['LT', 'MT', 'HT', 'TD', 'SPG']);
        $member = null;
        if (! is_null($memberId)) {
            $member = MemberModel::find($memberId);
        }

        if (is_null($member)) {
            return redirect('/');
        }
        $tanks = $member->ownTanks(true)->get();
        $result = [];
        foreach ($tanks as $tank) {
            if (is_null($tank->details)) {
                continue;
            }
            $temp['id'] = $tank['wargaming_id'];
            $temp['tier'] = $tank->details['tier'];
            $temp['type'] = $tank->details['type'];
            $temp['type_sort'] = $typeOrder[$tank->details['type']];
            $temp['name'] = $tank['details']['name'];
            $temp['wn8'] = $tank['wn8'];
            $temp['battles'] = $tank['battles'];
            $temp['wins'] = $tank['wins'];
            $temp['losses'] = $tank['losses'];
            $temp['max_kills'] = $tank['max_frags'];
            $temp['premium'] = $tank['details']['premium'];
            $temp['win_percent'] = $tank['wins'] ?
                number_format(($tank['wins'] / $tank['battles']) * 100, 2)
                :   0;
            $temp['mastery'] = 'false' == $tank['mastery']? '' : $tank['mastery'];
            $result[] = $temp;
        }

        return $this->useView('profile-tanks', [
            'member' => $member,
            'tanks' => $result,
//            'keys' => array_keys((array)$tanks->first()->toArray())
        ]);
    }
}

