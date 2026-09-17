<?php

namespace App\Services\UpCustomerCommission;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\TblNode;
use App\Models\User;
use App\Models\TblNodeDownlineLogRef;
use App\Traits\Telegram;

class NodeService
{
    use Telegram;

    /**
     * Phiên bản Laravel của ReUpdActCustomer
     * Xếp user vào cây nhị phân
     * 
     * @param int $userId ID user trong bảng user
     * @return void
     */
    // Trong NodeService.php
    public function reUpdActCustomer(int $userId): bool
    {
        if ($userId == 0 || $userId == '') {
            $this->sendMesssageTelegram("❌ reUpdActCustomer FAILED: UserID = 0 hoặc rỗng");
            return false;
        }

        // Kiểm tra user đã có node chưa
        $existingNode = TblNode::where('UserID', $userId)
            ->orderBy('NodeID', 'desc')
            ->first();

        if ($existingNode) {
            // Đã có node rồi, coi như thành công
            return true;
        }

        // Lấy thông tin user
        $user = User::find($userId);
        if (!$user) {
            $this->sendMesssageTelegram("❌ reUpdActCustomer FAILED: Không tìm thấy user ID: {$userId}");
            return false;
        }

        // Kiểm tra node của referrer
        $referrerNode = TblNode::where('UserID', $user->ReferrerUserID)
            ->orderBy('NodeID', 'desc')
            ->first();

        if (!$referrerNode) {
            $this->sendMesssageTelegram("❌ reUpdActCustomer FAILED: Không thấy mã cha cần cắm. UserID: {$userId}, ReferrerUserID: {$user->ReferrerUserID}");
            return false;
        }

        // Bước 1: Chọn node cha phù hợp
        $treeNodeID = $this->chooseUpdActCustomerF1($user->ReferrerUserID, $user->Pos, $userId);

        // Lấy thông tin node được chọn
        $logNode = TblNode::find($treeNodeID);
        if (!$logNode) {
            $this->sendMesssageTelegram("❌ reUpdActCustomer FAILED: Không tìm thấy logNode. UserID: {$userId}, treeNodeID: {$treeNodeID}");
            return false;
        }

        // ... (giữ nguyên logic cũ) ...

        // Bước 2: Kiểm tra lại (sau khi update ReferrerUserID)
        $user = User::find($userId);
        $nodeInfo = TblNode::where('UserID', $user->ReferrerUserID)
            ->orderBy('NodeID', 'desc')
            ->first();

        if (!$nodeInfo) {
            $this->sendMesssageTelegram("❌ reUpdActCustomer FAILED: Không tìm thấy nodeInfo sau khi update ReferrerUserID. UserID: {$userId}, ReferrerUserID: {$user->ReferrerUserID}");
            return false;
        }

        // Nếu node cha đã đầy cả 2 nhánh, tìm node khác
        if ($nodeInfo->NodeIDLeft > 0 && $nodeInfo->NodeIDRight > 0) {
            $rNodeID = $this->chooseUpdActCustomerF1($userId, $user->Pos);
            $nodeInfo = TblNode::find($rNodeID);

            if (!$nodeInfo) {
                $this->sendMesssageTelegram("❌ reUpdActCustomer FAILED: Không tìm thấy nodeInfo sau khi tìm node khác. UserID: {$userId}, rNodeID: {$rNodeID}");
                return false;
            }

            // Nếu node mới cũng đầy, báo lỗi
            if ($nodeInfo->NodeIDLeft > 0 && $nodeInfo->NodeIDRight > 0) {
                $this->sendMesssageTelegram("❌ reUpdActCustomer FAILED: Full Leg upline. UserID: {$userId}, NodeID: {$rNodeID}");
                Log::warning("Full Leg upline for user: " . $userId);
                return false;
            }

            // Cập nhật ReferrerUserID lại
            User::where('UserID', $userId)->update([
                'ReferrerUserID' => $nodeInfo->UserID
            ]);
        }

        try {
            // Cập nhật user: Active, StatusMember, DateReg
            User::where('UserID', $userId)->update([
                'Active' => 'Y',
                'StatusMember' => 'A',
                'DateReg' => now()
            ]);

            // Tạo node mới cho user
            $newNode = TblNode::create([
                'LevelID' => 0,
                'UserID' => $userId,
                'DateCreate' => now(),
                'Active' => 'N',
            ]);

            $nodeID = $newNode->NodeID;

            // Cập nhật node cha: thêm node mới vào NodeIDLeft hoặc NodeIDRight
            $updateData = [];
            if ($user->Pos == 'L' && $nodeInfo->NodeIDLeft == 0) {
                $updateData['NodeIDLeft'] = $nodeID;
            } elseif ($user->Pos == 'R' && $nodeInfo->NodeIDRight == 0) {
                $updateData['NodeIDRight'] = $nodeID;
            } elseif ($nodeInfo->NodeIDLeft == 0) {
                $updateData['NodeIDLeft'] = $nodeID;
            } else {
                $updateData['NodeIDRight'] = $nodeID;
            }

            TblNode::where('NodeID', $nodeInfo->NodeID)->update($updateData);

            // Cập nhật ParentNodeID cho node mới
            TblNode::where('NodeID', $nodeID)->update([
                'ParentNodeID' => $nodeInfo->NodeID
            ]);

            // Xây dựng log downline
            $this->buildLogRef($nodeID, 1, $userId);

            // Gửi thông báo Telegram thành công
            $this->sendTelegramNotification($userId, $nodeID);

            return true;
        } catch (\Exception $e) {
            $this->sendMesssageTelegram("❌ reUpdActCustomer EXCEPTION: UserID: {$userId}, Error: " . $e->getMessage());
            Log::error("reUpdActCustomer exception for user {$userId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Chọn node cha phù hợp để xếp user mới vào cây
     * 
     * @param int $customerId UserID của referrer
     * @param string $pos Vị trí: 'L', 'R', 'A'
     * @param int $customerId2 UserID của user mới (để update Pos nếu cần)
     * @return int NodeID được chọn
     */
    protected function chooseUpdActCustomerF1(int $customerId, string $pos, int $customerId2 = 0): int
    {
        if ($customerId == 0 || $customerId == '') {
            return 0;
        }

        // Lấy node của referrer
        $f1NodeInfo = TblNode::where('UserID', $customerId)
            ->orderBy('NodeID', 'desc')
            ->first();

        if (!$f1NodeInfo) {
            // Nếu chưa có node, tìm referrer của referrer
            $f1UserInfo = User::find($customerId);
            if (!$f1UserInfo) {
                return 0;
            }

            return $this->chooseUpdActCustomerF1($f1UserInfo->ReferrerUserID, $f1UserInfo->Pos);
        }

        // Nếu node chưa có con nào
        if ($f1NodeInfo->NodeIDLeft == 0 && $f1NodeInfo->NodeIDRight == 0) {
            return $f1NodeInfo->NodeID;
        }

        // Nếu node đã có con
        if ($f1NodeInfo->NodeIDLeft > 0 || $f1NodeInfo->NodeIDRight > 0) {
            if ($pos == 'A') {
                // Auto: chọn nhánh ít node hơn
                if ($f1NodeInfo->TotalNodeLeft > $f1NodeInfo->TotalNodeRight) {
                    // Bên trái nhiều hơn, chọn bên trái (đi sâu vào nhánh trái)
                    if ($f1NodeInfo->NodeIDLeft > 0) {
                        return $this->getNodeConLeft($f1NodeInfo->NodeIDLeft);
                    } else {
                        return $f1NodeInfo->NodeID;
                    }
                } else {
                    // Bên phải nhiều hơn hoặc bằng, chọn bên phải và update Pos = 'R'
                    if ($f1NodeInfo->NodeIDRight > 0) {
                        $result = $this->getNodeConRight($f1NodeInfo->NodeIDRight);
                        // Update Pos = 'R' cho user nếu cần
                        if ($customerId2 > 0) {
                            User::where('UserID', $customerId2)->update(['Pos' => 'R']);
                        }
                        return $result;
                    } else {
                        if ($customerId2 > 0) {
                            User::where('UserID', $customerId2)->update(['Pos' => 'R']);
                        }
                        return $f1NodeInfo->NodeID;
                    }
                }
            } else {
                // Theo vị trí chỉ định
                if ($pos == 'R' && $f1NodeInfo->NodeIDRight == 0) {
                    return $f1NodeInfo->NodeID;
                } elseif ($pos == 'R' && $f1NodeInfo->NodeIDRight > 0) {
                    return $this->getNodeConRight($f1NodeInfo->NodeIDRight);
                } elseif ($pos == 'L' && $f1NodeInfo->NodeIDLeft == 0) {
                    return $f1NodeInfo->NodeID;
                } elseif ($pos == 'L' && $f1NodeInfo->NodeIDLeft > 0) {
                    return $this->getNodeConLeft($f1NodeInfo->NodeIDLeft);
                }
            }
        }

        return $f1NodeInfo->NodeID;
    }

    /**
     * Lấy node con cuối cùng bên trái
     * 
     * @param int $nodeID NodeID bắt đầu
     * @return int NodeID của node con cuối cùng bên trái
     */
    protected function getNodeConLeft(int $nodeID): int
    {
        if ($nodeID == 0) {
            return 0;
        }

        $node = TblNode::find($nodeID);
        if (!$node) {
            return 0;
        }

        if ($node->NodeIDLeft > 0) {
            $leftNode = TblNode::find($node->NodeIDLeft);
            if ($leftNode) {
                return $this->getNodeConLeft($node->NodeIDLeft);
            }
        }

        return $node->NodeID;
    }

    /**
     * Lấy node con cuối cùng bên phải
     * 
     * @param int $nodeID NodeID bắt đầu
     * @return int NodeID của node con cuối cùng bên phải
     */
    protected function getNodeConRight(int $nodeID): int
    {
        if ($nodeID == 0) {
            return 0;
        }

        $node = TblNode::find($nodeID);
        if (!$node) {
            return 0;
        }

        if ($node->NodeIDRight > 0) {
            $rightNode = TblNode::find($node->NodeIDRight);
            if ($rightNode) {
                return $this->getNodeConRight($node->NodeIDRight);
            }
        }

        return $node->NodeID;
    }

    /**
     * Xây dựng log downline và cập nhật TotalNodeLeft/TotalNodeRight
     * 
     * @param int $nodeID NodeID của node mới
     * @param int $indirectID Cấp độ gián tiếp
     * @param int $fUserID UserID của user mới
     * @return void
     */
    protected function buildLogRef(int $nodeID, int $indirectID = 1, int $fUserID = 0): void
    {
        if ($nodeID == 0 || $indirectID > 500) {
            return;
        }

        // Tìm node cha (node có NodeIDLeft hoặc NodeIDRight = $nodeID)
        $nodeInfo = TblNode::where('NodeIDLeft', $nodeID)->first();

        if ($nodeInfo) {
            // Kiểm tra UserID có tồn tại trong bảng user không
            $userExists = User::where('UserID', $nodeInfo->UserID)->exists();
            if (!$userExists) {
                Log::warning("buildLogRef: UserID {$nodeInfo->UserID} không tồn tại trong bảng user. NodeID: {$nodeInfo->NodeID}, FUserID: {$fUserID}");
                // Không insert log nếu user không tồn tại, nhưng vẫn đệ quy lên node cha
                $this->buildLogRef($nodeInfo->NodeID, $indirectID + 1, $fUserID);
                return;
            }

            // Kiểm tra log đã tồn tại chưa
            $log = TblNodeDownlineLogRef::where('UserID', $nodeInfo->UserID)
                ->where('FUserID', $fUserID)
                ->first();

            if (!$log) {
                // Cập nhật TotalNodeLeft
                TblNode::where('NodeID', $nodeInfo->NodeID)->increment('TotalNodeLeft');

                // Tạo log
                TblNodeDownlineLogRef::create([
                    'Type' => 'L',
                    'UserID' => $nodeInfo->UserID,
                    'FUserID' => $fUserID,
                    'IndirectID' => $indirectID,
                    'DateCreate' => now(),
                    'CNID' => 0,
                ]);
            }

            // Đệ quy lên node cha
            $this->buildLogRef($nodeInfo->NodeID, $indirectID + 1, $fUserID);
        } else {
            // Tìm node cha bên phải
            $nodeInfo = TblNode::where('NodeIDRight', $nodeID)->first();

            if ($nodeInfo) {
                // Kiểm tra UserID có tồn tại trong bảng user không
                $userExists = User::where('UserID', $nodeInfo->UserID)->exists();
                if (!$userExists) {
                    Log::warning("buildLogRef: UserID {$nodeInfo->UserID} không tồn tại trong bảng user. NodeID: {$nodeInfo->NodeID}, FUserID: {$fUserID}");
                    // Không insert log nếu user không tồn tại, nhưng vẫn đệ quy lên node cha
                    $this->buildLogRef($nodeInfo->NodeID, $indirectID + 1, $fUserID);
                    return;
                }

                // Kiểm tra log đã tồn tại chưa
                $log = TblNodeDownlineLogRef::where('UserID', $nodeInfo->UserID)
                    ->where('FUserID', $fUserID)
                    ->first();

                if (!$log) {
                    // Cập nhật TotalNodeRight
                    TblNode::where('NodeID', $nodeInfo->NodeID)->increment('TotalNodeRight');

                    // Tạo log
                    TblNodeDownlineLogRef::create([
                        'Type' => 'R',
                        'UserID' => $nodeInfo->UserID,
                        'FUserID' => $fUserID,
                        'IndirectID' => $indirectID,
                        'DateCreate' => now(),
                        'CNID' => 0,
                    ]);
                }

                // Đệ quy lên node cha
                $this->buildLogRef($nodeInfo->NodeID, $indirectID + 1, $fUserID);
            }
        }
    }

    /**
     * Gửi thông báo Telegram khi user được xếp vào cây
     * 
     * @param int $userId UserID của user mới
     * @param int $nodeID NodeID của node mới
     * @return void
     */
    protected function sendTelegramNotification(int $userId, int $nodeID): void
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                return;
            }

            $f1User = User::find($user->F1UserID);
            $refUser = User::find($user->ReferrerUserID);

            // Xác định vị trí (Left hoặc Right)
            $nodeTrenRight = TblNode::where('NodeIDRight', $nodeID)->first();
            $type = $nodeTrenRight ? 'Right' : 'Left';

            $message = "<b>Active Tree :" . ($user->TokenID ?? 'N/A') . ":</b>\n";
            $message .= "UserName :" . ($user->UserName ?? 'N/A') . "\n";
            $message .= "FullName :" . ($user->FullName ?? 'N/A') . "\n";
            $message .= "Bảo trợ :" . ($f1User->UserName ?? 'N/A') . "\n";
            $message .= "Chỉ định :" . $type . " | " . ($refUser->UserName ?? 'N/A') . "\n";
            $message .= "Date Join :" . now()->format('Y-m-d H:i:s') . "\n";

            $this->sendMesssageTelegram($message);
        } catch (\Exception $e) {
            Log::error('Error sending Telegram notification: ' . $e->getMessage());
        }
    }
}
