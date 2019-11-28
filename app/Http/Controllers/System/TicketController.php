<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function getTicket()
    {
        $user = Session('user');
        $ticket = DB::table('ticket')->join('ticket_subject', 'ticket_subject_id', 'ticket_Subject')
            ->where('ticket_User', $user->User_ID)
            ->where('ticket_ReplyID', 0)
            ->orderByDesc('ticket_ID')->get();
        $subject = DB::table('ticket_subject')->get();
        // dd($ticket);
        return view('System.Ticket.Ticket', compact('ticket', 'subject'));
    }

    public function postTicket(Request $req)
    {
        if (!$req->content || !$req->subject) {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Miss data']);
        }
        $user = Session('user');
        if ($req->subject) {
            $subjectID = $req->subject;
        }
        $replyID = 0;
        if ($req->replyID != 0) {
            $replyID = $req->replyID;
            $subject = DB::table('ticket')->where('ticket_ID', $replyID)->select('ticket_Subject')->first();
            $subjectID = $subject->ticket_Subject;
        }

        $addArray = array(
            'ticket_User' => $user->User_ID,
            'ticket_Time' => date('Y-m-d H:i:s'),
            'ticket_Subject' => $subjectID,
            'ticket_Content' => $req->content,
            'ticket_Status' => 0,
            'ticket_ReplyID' => $replyID
        );

        $data = DB::table('ticket')->insert([$addArray]);

        $id = DB::getPdo()->lastInsertId();

        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Please waiting support reply!']);
    }

    public function getTicketDetail($id){

        $ticket =   DB::table('ticket')->join('users', 'User_ID', 'ticket_User')
        ->join('ticket_subject', 'ticket_subject_id', 'ticket_Subject')
        ->where('ticket_ID', $id)
        ->orWhere('ticket_ReplyID', $id)
        ->orderBy('ticket_ID')
        ->get();
		return view('System.Ticket.Ticket-Detail' , compact('ticket'));
	}

    public function destroyTicket($id)
    {
        $changeStatus = DB::table('ticket')
            ->where('ticket_ID', $id)
            ->update(['ticket_Status' => 0]);
        if ($changeStatus) {
            return redirect()->back();
        }
    }

    public function getTicketAdmin(){
        $ticket = DB::table('ticket')->join('users', 'User_ID', 'ticket_User')
        ->join('ticket_subject','ticket.ticket_Subject','ticket_subject.ticket_subject_id')
        ->where('ticket_ReplyID', 0)
        ->orderByDesc('ticket_ID')
        ->paginate(15);
        // dd($ticket);
        return view('System.Ticket.Admin-Ticket', compact('ticket'));
    }
    
    public function getStatusTicketAdmin($id){
		$update = array(
			'ticket_Status'=> -1,
		);
		$data = DB::table('ticket')->where('ticket_ID', $id)->update($update);
		return redirect()->back()->with(['flash_level'=>'success','flash_message'=>'Hide Success!']);
        
    }
}
