<?php

namespace App\Http\Controllers\System;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class MemberController extends Controller
{
    public function getMembersList()
    {
        $userID = session('user')->User_ID;
        $membersList = $this->getMembers($userID);
        return view('System.Members.Members-List', compact('membersList'));
    }

    public function getMembersTree(Request $request)
    {
        if (!$request->userID) {
            $userID = session('user')->User_ID;
        }
        $usersTreeList = [];
        $user = User::Where('User_Tree', 'like', "$userID")
            ->orWhere('User_Tree', 'like', "%$userID")
            ->select('User_ID as id', 'User_Parent as pid','User_Email as email' )
            ->first()->toArray();
        $investmentAmount = User::join('investment', 'User_ID', 'investment.investment_User')
            ->select('investment.investment_Amount')
            ->where('investment_Status', 1)
            ->where('User_ID', $user['id'])
            ->sum('investment.investment_Amount');
        $user['investmentAmount'] = $investmentAmount + 0;
        $user['level'] = 'Parent';
        $user['img'] = "dist/img/user.png";
        array_push($usersTreeList, $user);
        $this->getUsersTreeList($userID, $usersTreeList);
        return view('System.Members.Members-Tree', compact('usersTreeList'));
    }


    public function getUsersTreeList($userID, &$usersTreeList, $level = 0)
    {
        ++$level;
        if ($level == 4){
            return 0;
        }
        $children = User::Where('User_Parent', $userID)
            ->select('User_ID as id', 'User_Parent as pid', 'User_Email as email')
            ->get();
        if ($children) {
            foreach ($children as $child) {
                $investmentAmount = User::join('investment', 'User_ID', 'investment.investment_User')
                    ->select('investment.investment_Amount')
                    ->where('User_ID', $child->id)
                    ->where('investment_Status', 1)
                    ->sum('investment.investment_Amount');
                $child->investmentAmount = $investmentAmount + 0;
                $child->level = "F$level";
                $child->img = "dist/img/user.png";
                array_push($usersTreeList, $child->toArray());
                $this->getUsersTreeList($child->id, $usersTreeList, $level );
            }
        }
    }

    public function getMembers($userID, $level = 0) {
        ++$level;
        $membersList = User::where('User_Parent', $userID)
            ->select('User_ID', 'User_Email', 'User_RegisteredDatetime', 'User_Parent')
            ->get();
        if ($membersList) {
            foreach ($membersList as $user) {
                $investmentAmount = User::join('investment', 'User_ID', 'investment.investment_User')
                    ->select('investment.investment_Amount')
                    ->where('investment_Status', 1)
                    ->where('User_ID', $user->User_ID)
                    ->sum('investment.investment_Amount');
                $user->investmentAmount = number_format($investmentAmount,2);
                $investmentUSD = User::join('investment', 'User_ID', 'investment.investment_User')
                    ->select('investment.investment_Amount')
                    ->where('investment_Status', 1)
                    ->where('User_ID', $user->User_ID)
                    ->sum(DB::raw('investment_Amount * investment_Rate'));
                $user->investmentUSD = number_format($investmentUSD,2);
                $user->User_F = $level;
                $user->children = $this->getMembers($user->User_ID, $level);
            }
        }

        return $membersList->toArray();
    }



}
