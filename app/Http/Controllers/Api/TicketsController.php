<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketAttachments;
use App\Models\TicketHistory;
use App\Models\TicketComment;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::guard('sanctum')->user();
        $tickets = Ticket::where('user_id', $user->id)->get();
        return $this->successResponse($tickets, 'Tickets Fetched Successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'description' => 'required',
            'priority' => 'required|exists:ticket_priorities,id',
            'assigned_to' => 'required'
        ], [
            'subject.required' => 'Subject is required.',
            'description.required' => 'Description is required.',
            'priority.required' => 'Priority is required.',
            'assigned_to.required' => 'Assigned To is required.',
        ]);
        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }
        $user = Auth::guard('sanctum')->user();
        DB::beginTransaction();
        try {
            $attachments = array();
            $ticket_attachments = $request->file('ticket_attachments');
            if ($ticket_attachments) {
                foreach ($ticket_attachments as $attachment) {
                    $destinationPath = public_path('/uploads/tickets'); // upload path
                    $attachment_name = "/uploads/tickets/" . time() . '_' . str_replace(' ', '_', $attachment->getClientOriginalName());
                    $attachment->move($destinationPath, $attachment_name);
                    $attachments[] = $attachment_name;
                }
            }


            $ticket = Ticket::create([
                'subject' => $request->post('subject'),
                'description' => $request->post('description'),
                'status' => 'sent',
                'priority' => $request->post('priority'),
                'assigned_to' => $request->post('assigned_to'),
                'user_id' => $user->id
            ]);

            $ticket_history = TicketHistory::create([
                'ticket_id' => $ticket->id,
                'change_description' => 'New Ticket Added',
                'changed_by' => $user->id
            ]);


            foreach ($attachments as $attachment) {
                TicketAttachments::create([
                    'ticket_id' => $ticket->id,
                    'file_path' => $attachment
                ]);
            }
            // Commit transaction
            DB::commit();

            return $this->successResponse($ticket, 'Ticket Created Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket_details = Ticket::where('id', $id)->first();
        $ticket_details->attachments = TicketAttachments::where('ticket_id', $ticket_details->id)->pluck('file_path', 'id')->toArray();
        // $ticket_details->comments = TicketComment::select('')->join('','user','=','')->where('ticket_id',$ticket_details->id)->paginate(10);
        $ticket_details->comments = DB::table('ticket_comments')
            ->where('ticket_comments.ticket_id', $ticket_details->id)
            ->leftJoin('users', 'ticket_comments.user_id', '=', 'users.id')
            ->leftJoin('admins', 'ticket_comments.admin_id', '=', 'admins.id')
            ->select(
                'ticket_comments.*',
                DB::raw('COALESCE(users.name, admins.name) as commenter_name')
            )
            ->paginate(10);

        return $this->successResponse($ticket_details, 'Ticket Details Fetched Successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'description' => 'required',
            'priority' => 'required|exists:ticket_priorities,id',
            'assigned_to' => 'required|exists:admins,id'
        ], [
            'subject.required' => 'Subject is required.',
            'description.required' => 'Description is required.',
            'priority.required' => 'Priority is required.',
            'priority.exists' => 'Priority is invalid.',
            'assigned_to.required' => 'Assigned To is required.',
            'assigned_to.exists' => 'Assigned To is invalid.',
        ]);
        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }
        $user = Auth::guard('sanctum')->user();
        DB::beginTransaction();

        // Find the ticket by ID
        $ticket = Ticket::find($id);

        // Update the ticket with the validated data
        $ticket->subject = $request->post('subject');
        $ticket->description = $request->post('description');
        $ticket->priority = $request->post('priority');
        $ticket->assigned_to = $request->post('assigned_to');
        $ticket_updated = $ticket->save();

        $remove_attachments = $request->post('remove_attachments');
        if (is_array($remove_attachments)) {
            foreach ($remove_attachments as $attachment) {
                $attachment_path = TicketAttachments::where('id', $attachment)->value('file_path');
                // Remove the leading slash if it's there
                $filePath = ltrim($attachment_path, '/');

                // Construct the full file path in the public directory
                $fullPath = public_path($filePath);
                // Check if the file exists and delete it
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }

                $delete_attachments = TicketAttachments::where('id', $attachment)->where('ticket_id', $id)->delete();
            }
        }

        $attachments = array();
        $new_ticket_attachments = $request->file('new_ticket_attachments');
        if ($new_ticket_attachments) {
            foreach ($new_ticket_attachments as $attachment) {
                $destinationPath = public_path('/uploads/tickets'); // upload path
                $attachment_name = "/uploads/tickets/" . time() . '_' . str_replace(' ', '_', $attachment->getClientOriginalName());
                $attachment->move($destinationPath, $attachment_name);
                $attachments[] = $attachment_name;
            }
        }

        foreach ($attachments as $attachment) {
            TicketAttachments::create([
                'ticket_id' => $id,
                'file_path' => $attachment
            ]);
        }

        $ticket_history = TicketHistory::create([
            'ticket_id' => $id,
            'change_description' => 'Ticket #' . $id . ' Updated by ' . $user->name . ' (' . $user->id . ')',
            'changed_by' => $user->id
        ]);


        // Commit transaction
        DB::commit();

        return $this->successResponse($ticket, 'Ticket Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    /**
     * Post comment on Tickets.
     */
    public function postComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_id' => 'required|exists:tickets,id',
            'comment' => 'required',
        ], [
            'ticket_id.required' => 'Ticket Id is required.',
            'comment.required' => 'Comment is required.',
        ]);
        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }
        $user = Auth::guard('sanctum')->user();
        DB::beginTransaction();
        try {
            $post_comment = TicketComment::create([
                'ticket_id' => $request->post('ticket_id'),
                'comment' => $request->post('comment'),
                'user_id' => $user->id,
            ]);

            $ticket_history = TicketHistory::create([
                'ticket_id' => $request->post('ticket_id'),
                'change_description' => 'New Comment Added',
                'changed_by' => $user->id
            ]);


            // Commit transaction
            DB::commit();

            return $this->successResponse($post_comment, 'Ticket Comment Posted Successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

}
