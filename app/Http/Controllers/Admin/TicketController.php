<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Ticket, User, TicketAttachments, TicketComment, Admin, TicketPriority};
use Illuminate\Support\Facades\{Auth, DB};
use App\DTOs\TicketDTO;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['admin', 'prioritys'])->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $users = User::active()->select('id', 'name')->get();
        $admins = Admin::select('id', 'name')->get();
        $priorities = TicketPriority::select('id', 'name')->get();

        return view('admin.tickets.create', compact('users', 'admins', 'priorities'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'user_id' => 'nullable|integer',
        ]);

        $ticketDTO = new TicketDTO($request);
        $ticket = Ticket::create($ticketDTO->toArray());

        if ($request->hasFile('images')) {
            $this->handleFileUploads($request, $ticket->id);
        }

        return redirect()->route('admin.tickets.index')->with('success', __('messages.ticket_created'));
    }

    public function edit(string $id)
    {
        $ticket = Ticket::with('attachments')->findOrFail($id);

        $admins = Admin::select('admins.id', 'admins.name')
            ->leftJoin('tickets', function ($join) use ($id) {
                $join->on('tickets.assigned_to', '=', 'admins.id')
                    ->where('tickets.id', '=', $id);
            })->get();

        $users = User::select('users.id', 'users.name')
            ->leftJoin('tickets', function ($join) use ($id) {
                $join->on('tickets.user_id', '=', 'users.id')
                    ->where('tickets.id', '=', $id);
            })->get();

        $priorities = TicketPriority::select('ticket_priorities.id', 'ticket_priorities.name')
            ->leftJoin('tickets', function ($join) use ($id) {
                $join->on('tickets.priority', '=', 'ticket_priorities.id')
                    ->where('tickets.id', '=', $id);
            })->get();

        return view('admin.tickets.edit', compact('ticket', 'admins', 'users', 'priorities'));
    }


    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'subject' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'status' => 'required|string',
            'priority' => 'nullable|string',
            'assigned_to' => 'nullable|integer',
            'user' => 'nullable|integer',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticketDTO = new TicketDTO($request);

        $ticket->update($ticketDTO->toArray());

        if ($request->hasFile('images')) {
            $this->handleFileUploads($request, $ticket->id);
        }

        if ($request->has('existing_attachments')) {
            $this->handleAttachmentUpdates($request, $ticket->id);
        }

        return redirect()->route('admin.tickets.index')->with('success',  __('messages.ticket_updated'));
    }

    public function comment(Request $request, string $id)
    {
        $ticketId = $id; // Define the ticketId
        $comments = TicketComment::select(
            'ticket_comments.id',
            'ticket_comments.comment',
            'ticket_comments.admin_id',
            'ticket_comments.user_id',
            'ticket_comments.created_at',
            DB::raw("COALESCE(admins.name, users.name) as commentor_name")
        )
        ->leftJoin('admins', 'admins.id', '=', 'ticket_comments.admin_id')
        ->leftJoin('users', 'users.id', '=', 'ticket_comments.user_id')
        ->where('ticket_comments.ticket_id', $ticketId)
        ->orderBy('ticket_comments.created_at', 'desc')
        ->paginate(5);

        $description = Ticket::where('id', $ticketId)->value('description');

        return view('admin.tickets.comment', compact('ticketId', 'comments', 'description'));
    }

    public function save(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'comment' => 'required|string',
        ]);

        TicketComment::create([
            'ticket_id' => $id,
            'admin_id' => Auth::guard('admin')->id(),
            'comment' => $validatedData['comment'],
        ]);

        return redirect()->back()->with('success', __('messages.comment_created'));
    }

    public function destroy(string $id)
    {
        $ticket = Ticket::with('attachments', 'comments')->findOrFail($id);

        foreach ($ticket->attachments as $attachment) {
            if (file_exists(public_path($attachment->file_path))) {
                unlink(public_path($attachment->file_path));
            }
            $attachment->delete();
        }

        $ticket->comments()->delete();
        $ticket->delete();

        return redirect()->route('admin.tickets.index')->with('success', __('messages.ticket_deleted'));
    }

    private function handleFileUploads(Request $request, int $ticketId)
    {
        foreach ($request->file('images') as $uploadedFile) {
            $name = time() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->move(public_path('uploads/tickets'), $name);

            TicketAttachments::create([
                'ticket_id' => $ticketId,
                'file_path' => "uploads/tickets/$name",
            ]);
        }
    }

    private function handleAttachmentUpdates(Request $request, int $ticketId)
    {
        $attachmentsToRemove = array_diff(
            $request->input('existing_attachments', []),
            $request->input('update_attachment', [])
        );

        foreach ($attachmentsToRemove as $attachmentId) {
            $attachment = TicketAttachments::find($attachmentId);
            if ($attachment) {
                if (file_exists(public_path($attachment->file_path))) {
                    unlink(public_path($attachment->file_path));
                }
                $attachment->delete();
            }
        }
    }
}
