<?php
namespace App\Services\Backend;

use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use App\Services\BaseService;
use App\Traits\ElasticMail;
use DB;
use Illuminate\Http\Request;

class TicketService extends BaseService
{
    use ElasticMail;

    public function getAll(Request $request)
    {
        $filters = $request->all();

        // perPage handle
        if (! empty($filters['perPage']) && $filters['perPage'] > 15) {
            $this->perPage = $filters['perPage'];
        }

        $query = Ticket::query();

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('id', 'DESC')->paginate($this->perPage);
    }

    public function store($request)
    {
        return Ticket::query()->create($request->validated());
    }

    public function update($request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $validated = $request->validated();

        $payload = [
            'email' => $validated['email'],
        ];

        if (! empty($request['reply'])) {
            try {
                $this->sendElasticEmail(
                    'Phản hồi tin liên hệ',
                    $ticket->email,
                    'emails.reply-ticket',
                    ['ticket' => $ticket]
                );
            } catch (\Throwable $e) {
                \Log::warning('Ticket reply email failed: ' . $e->getMessage(), ['ticket_id' => $ticket->getKey()]);
            }
            $payload['status'] = TicketStatusEnum::REPLIED;
        }

        return $ticket->update($payload);
    }

    public function delete($model)
    {
        try {
            DB::beginTransaction();
            $model->delete();
            DB::commit();

            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
        }
        return false;
    }

    public function groupByStatus()
    {
        $query = Ticket::query()->orderByDesc('id')->get();

        return $query->countBy('status');
    }
}
