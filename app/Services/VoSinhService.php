<?php

namespace App\Services;

use App\Models\VoSinh; // Đổi từ User thành VoSinh
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VoSinhService
{
    /**
     * Get all vo sinh with pagination
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(Request $request)
    {
        $query = VoSinh::with('capDai'); // Load relationship với cap_dai

        // Search by name, ma_hoi_vien, email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ho_va_ten', 'like', '%' . $search . '%')
                  ->orWhere('ma_hoi_vien', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('active_status', $request->status);
        }

        // Filter by cap_dai_id
        if ($request->filled('cap_dai_id')) {
            $query->where('cap_dai_id', $request->cap_dai_id);
        }

        return $query->orderBy('id', 'desc')->paginate(20);
    }

    /**
     * Group users by status
     *
     * @return \Illuminate\Support\Collection
     */
    public function groupByStatus()
    {
        return VoSinh::select('active_status', DB::raw('count(*) as total'))
            ->groupBy('active_status')
            ->pluck('total', 'active_status');
    }

    /**
     * Store new vo sinh
     *
     * @param Request $request
     * @return VoSinh
     */
    public function store(Request $request)
    {
        $data = $request->only((new VoSinh)->getFillable());

        if (empty($data['password'])) {
            $data['password'] = '123@LV23';
        }
        $data['password'] = Hash::make($data['password']);

        if (array_key_exists('active_status', $data)) {
            $data['active_status'] = filter_var($data['active_status'], FILTER_VALIDATE_BOOLEAN);
        }

        return VoSinh::create($data);
    }

    /**
     * Update vo sinh
     *
     * @param Request $request
     * @param int $id
     * @return VoSinh
     */
    public function update(Request $request, $id)
    {
        $voSinh = VoSinh::findOrFail($id);
        $payload = $request->only((new VoSinh)->getFillable());

        if (! empty($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        } else {
            unset($payload['password']);
        }

        if (array_key_exists('active_status', $payload)) {
            $payload['active_status'] = filter_var($payload['active_status'], FILTER_VALIDATE_BOOLEAN);
        }

        $voSinh->update($payload);

        return $voSinh;
    }

    /**
     * Delete vo sinh
     *
     * @param VoSinh $voSinh
     * @return bool
     */
    public function delete(VoSinh $voSinh)
    {
        return $voSinh->delete();
    }

    /**
     * Send KYC
     *
     * @param int $id
     * @return void
     */
    public function sendKyc($id)
    {
        // TODO: Implement send KYC logic
    }

    /**
     * Get KYC list
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getKYCList(Request $request)
    {
        // TODO: Implement KYC list logic
        return VoSinh::whereNotNull('kyc_status')->paginate(20);
    }

    /**
     * Save KYC detail
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function saveKycDetail(Request $request, $id)
    {
        // TODO: Implement save KYC detail logic
    }

    /**
     * Get inactive users
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getInactiveUsers(Request $request)
    {
        return VoSinh::where('active_status', false)
            ->orderBy('id', 'desc')
            ->paginate(20);
    }
}