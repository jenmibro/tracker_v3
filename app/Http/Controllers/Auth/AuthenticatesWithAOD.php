<?php

namespace App\Http\Controllers\Auth;

use App\UserGroup;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait AuthenticatesWithAOD
{
    public $email;

    public $clanId;

    public $roles = [];

    /**
     * Authenticates with AOD Community Service
     *
     * @param $request
     * @return bool
     */
    private function validatesCredentials($request)
    {
        try {
            $results = DB::connection('aod_forums')
                ->select("CALL check_user(:username, :password)", [
                    'username' => $request->username,
                    'password' => md5($request->password)
                ]);
        } catch (Exception $exception) {
            return false;
        }

        if (!empty($results)) {
            $member = Arr::first($results);

            $this->setMemberAttributes($member);

            return ($member->valid);
        }

        return false;
    }

    /**
     * @param $member
     */
    protected function setMemberAttributes($member)
    {
        $this->clanId = $member->userid;

        $this->email = $member->email;

        $this->roles = UserGroup::whereIn('id', array_merge(
            array_map('intval', explode(',', $member->membergroupids)),
            [$member->usergroupid]
        ))->get();
    }
}
