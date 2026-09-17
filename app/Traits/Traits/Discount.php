<?php

namespace App\Traits;

use App\Models\TblNode;

trait Discount
{
    /**
     * Lấy phần trăm giảm giá theo MucID hoặc LevelID
     * Ưu tiên giảm giá cao nhất giữa MucID và LevelID
     * 
     * @param int|null $mucId
     * @param int|null $levelId
     * @return float Phần trăm giảm giá (0-100)
     */
    public function getDiscountPercent(?int $mucId = null, ?int $levelId = null): float
    {
        // Bảng mapping MucID -> Phần trăm giảm giá
        $mucDiscountMap = [
            1 => 20,  // MucID 1: 20%
            2 => 25,  // MucID 2: 25%
            3 => 30,  // MucID 3: 30%
            4 => 35,  // MucID 4: 35%
            5 => 40,  // MucID 5: 40%
        ];

        // Bảng mapping LevelID -> Phần trăm giảm giá
        $levelDiscountMap = [
            1 => 30,  // LevelID 1: 30%
            2 => 35,  // LevelID 2: 35%
            3 => 40,  // LevelID 3: 40%
        ];

        $mucDiscount = 0;
        $levelDiscount = 0;

        // Lấy giảm giá từ MucID
        if ($mucId > 0 && isset($mucDiscountMap[$mucId])) {
            $mucDiscount = $mucDiscountMap[$mucId];
        }

        // Lấy giảm giá từ LevelID
        if ($levelId > 0 && isset($levelDiscountMap[$levelId])) {
            $levelDiscount = $levelDiscountMap[$levelId];
        }

        // Trả về giảm giá cao nhất
        return max($mucDiscount, $levelDiscount);
    }

    /**
     * Tính giá sau giảm giá
     * 
     * @param float $originalPrice Giá gốc
     * @param int|null $mucId MucID
     * @param int|null $levelId LevelID
     * @return array
     */
    public function calculateDiscount(float $originalPrice, ?int $mucId = null, ?int $levelId = null): array
    {
        $discountPercent = $this->getDiscountPercent($mucId, $levelId);
        $discountAmount = ($originalPrice * $discountPercent) / 100;
        $finalPrice = $originalPrice - $discountAmount;

        return [
            'discount_percent' => $discountPercent,
            'discount_amount' => round($discountAmount, 2),
            'final_price' => round($finalPrice, 2),
            'original_price' => $originalPrice,
        ];
    }

    /**
     * Tính giá sau giảm giá theo UserID
     * Tự động lấy MucID và LevelID từ node
     * 
     * @param float $originalPrice Giá gốc
     * @param int $userId UserID
     * @return array
     */
    public function calculateDiscountByUserID(float $originalPrice, int $userId): array
    {
        $node = TblNode::where('UserID', $userId)->first();

        return $this->calculateDiscount(
            $originalPrice,
            $node->MucID ?? null,
            $node->LevelID ?? null
        );
    }
}
