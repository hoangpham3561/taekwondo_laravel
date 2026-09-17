<?php

namespace App\Http\Controllers\Backend;

use App\Enums\TicketStatusEnum;
use App\Http\Requests\Backend\Ticket\CreateTicketRequest;
use App\Http\Requests\Backend\Ticket\UpdateTicketRequest;
use App\Models\Ticket;
use App\Services\Backend\TicketService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketController extends BaseController
{
    protected $ticketService;
    protected $ticketPrefix;

    public function __construct(TicketService $ticketService)
    {
        parent::__construct();
        $this->ticketService = $ticketService;
        $this->ticketPrefix = config('core.routes.tickets.prefix');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $tickets = $this->ticketService->getAll($request);

        $groupByStatus = $this->ticketService->groupByStatus();

        return view('pages.backend.ticket.index', [
            'tickets' => $tickets,
            'groupByStatus' => $groupByStatus
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        // return create view
        return view('pages.backend.ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(CreateTicketRequest $request)
    {
        $this->ticketService->store($request);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $data = Ticket::findOrFail($id);

        if ($data->status === TicketStatusEnum::NEW) {
            $data->forceFill(['status' => TicketStatusEnum::READ])->save();
            $data->refresh();
        }

        return view('pages.backend.ticket.edit', [
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTicketRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateTicketRequest $request, $id)
    {
        $data = $this->ticketService->update($request, $id);

        return redirect()->route($this->adminPrefix . "." . $this->ticketPrefix . ".edit", ['ticket' => $id])->with('success', 'Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ticket $ticket
     * @return RedirectResponse
     */
    public function destroy(Ticket $ticket)
    {
        $this->ticketService->delete($ticket);

        return redirect()->route($this->adminPrefix . "." . $this->ticketPrefix . ".index")->with('success',' Delete thành công');
    }
}
