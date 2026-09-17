<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\TelegramCallbackLog;
use App\Models\Product;
use App\Models\TransPdRequirement;
use App\Models\TblPvUser;
use App\Models\TblNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Traits\Telegram;

class TelegramWebhookController extends Controller
{
    use Telegram;

    /**
     * Xử lý webhook từ Telegram - xử lý trực tiếp không cần cronjob
     */
    public function handleCallback(Request $request)
    {
        $data = $request->all();

        // Xử lý message (khi user gõ command)
        if (isset($data['message']) && isset($data['message']['text'])) {
            $text = $data['message']['text'];
            $chatId = $data['message']['chat']['id'];
            $messageId = $data['message']['message_id'];
            $userId = $data['message']['from']['id'] ?? null;
            $username = $data['message']['from']['username'] ?? null;

            // Loại bỏ bot mention nếu có (format: @botname hoặc @botname )
            // Ví dụ: "@odivacaffee_bot /approve_order_3" -> "/approve_order_3"
            // Hoặc: "@odivacaffee_bot/approve_order_3" -> "/approve_order_3"
            $text = preg_replace('/^@[^\s\/]+\s*/', '', $text);

            // Log để debug
            if ($data['message']['text'] !== $text) {
                Log::info('Bot mention removed', [
                    'original' => $data['message']['text'],
                    'cleaned' => $text
                ]);
            }

            // Xử lý command approve_order
            if (preg_match('/^\/approve_order_(\d+)$/', $text, $matches)) {
                $orderId = $matches[1];
                return $this->processApproval('approve', $orderId, $chatId, $messageId, $userId, $username);
            }

            // Xử lý command reject_order
            if (preg_match('/^\/reject_order_(\d+)$/', $text, $matches)) {
                $orderId = $matches[1];
                return $this->processApproval('reject', $orderId, $chatId, $messageId, $userId, $username);
            }
        }

        // Xử lý callback_query (nếu vẫn dùng callback button)
        if (isset($data['callback_query'])) {
            $callbackQuery = $data['callback_query'];
            $callbackData = $callbackQuery['data'];
            $messageId = $callbackQuery['message']['message_id'];
            $chatId = $callbackQuery['message']['chat']['id'];
            $callbackQueryId = $callbackQuery['id'];
            $userId = $callbackQuery['from']['id'] ?? null;
            $username = $callbackQuery['from']['username'] ?? null;

            // Answer callback query trước
            $this->answerCallbackQuery($callbackQueryId);

            // Xử lý cả trường hợp có dấu / và không có
            $callbackData = ltrim($callbackData, '/'); // Bỏ dấu / nếu có

            if (strpos($callbackData, 'approve_order_') === 0) {
                $orderId = str_replace('approve_order_', '', $callbackData);
                return $this->processApproval('approve', $orderId, $chatId, $messageId, $userId, $username);
            } elseif (strpos($callbackData, 'reject_order_') === 0) {
                $orderId = str_replace('reject_order_', '', $callbackData);
                return $this->processApproval('reject', $orderId, $chatId, $messageId, $userId, $username);
            }
        }
        return response()->json(['ok' => true]);
    }

    /**
     * Xử lý duyệt/hủy trực tiếp
     */
    private function processApproval($action, $orderId, $chatId, $messageId, $userId, $username)
    {
        try {

            DB::beginTransaction();

            $order = Order::with('user')->find($orderId);
            if (!$order) {
                $this->sendMesssageTelegram("❌ Không tìm thấy lên hàng nạp tiền ID: {$orderId}");
                DB::rollBack();
                return response()->json(['ok' => false, 'error' => 'Order not found']);
            }

            $userName = optional($order->user)->UserName ?? optional($order->user)->Email ?? 'N/A';
            $orderCode = $order->OrderCode;
            $currentStatus = $order->Status;

            // Kiểm tra trạng thái hiện tại của order
            if ($action === 'approve') {
                if ($currentStatus === 'Y' || $currentStatus === 'C') {
                    $message = "❌ <b>LỆNH ĐÃ ĐƯỢC XỬ LÝ - Không thể xử lý lại</b>\n\n"
                        . "Mã đơn: <code>{$orderCode}</code>\n"
                        . "User: {$userName}\n"
                        . "Số tiền: " . number_format($order->TotalAmount, 0, ',', '.') . " VNĐ\n"
                        . "Không thể xử lý lại";

                    $this->sendMesssageTelegram($message);
                    DB::rollBack();
                    return response()->json(['ok' => true, 'message' => 'Cannot approve already approved or cancelled order']);
                }

                // Duyệt order
                $order->update(['Status' => 'Y']);

                // Lấy thông tin product để cộng tiền vào ví
                $product = Product::find($order->ProductID);

                if ($product) {
                    // Cộng tiền vào ví THANHVIEN (AmountPont)
                    if ($product->AmountPont > 0) {
                        TransPdRequirement::create([
                            'UserID' => $order->UserID,
                            'FUserID' => 0,
                            'TransactionID' => 0,
                            'Currency' => 'THANHVIEN',
                            'Image' => '',
                            'VIP' => 'BUYPACKAGE',
                            'AmountTransfer' => $product->AmountPont,
                            'Amount' => $product->AmountPont,
                            'SerialCode' => $orderId,
                            'RequestDate' => now(),
                            'DateMaintain' => now(),
                            'RequestStatus' => 'Y',
                            'Status' => 'Y',
                            'Note' => "Nạp từ đơn hàng: {$orderCode} - mua gói nạp tiêu dùng: {$product->Name}",
                            'IP' => $order->IPAddress ?? '',
                            'isMove' => 0,
                            'STSCNID' => 0,
                        ]);
                    }

                    // Cộng tiền vào ví ODICAFFEE (AmountCF)
                    if ($product->AmountCF > 0) {
                        TransPdRequirement::create([
                            'UserID' => $order->UserID,
                            'FUserID' => 0,
                            'TransactionID' => 0,
                            'Currency' => 'ODICAFFEE',
                            'Image' => '',
                            'VIP' => 'BUYPACKAGE',
                            'AmountTransfer' => $product->AmountCF,
                            'Amount' => $product->AmountCF,
                            'SerialCode' => $orderId,
                            'RequestDate' => now(),
                            'DateMaintain' => now(),
                            'RequestStatus' => 'Y',
                            'Status' => 'Y',
                            'Note' => "Tặng từ đơn hàng: {$orderCode} - mua gói nạp tiêu dùng: {$product->Name}",
                            'IP' => $order->IPAddress ?? '',
                            'isMove' => 0,
                            'STSCNID' => 0,
                        ]);
                    }

                    // Lấy NodeID từ bảng tbl_node dựa vào UserID
                    $node = TblNode::where('UserID', $order->UserID)->first();

                    if ($node) {
                        TblPvUser::create([
                            'NodeID' => $node->NodeID,
                            'PV' => $product->AmountPont ?? $product->Amount ?? 0,
                            'PVpayBack' => 0,
                            'PVProfit' => 0,
                            'Profit' => 0,
                            'Period' => 18,
                            'OrderID' => $order->OrderID,
                            'LevelID' => 0,
                            'DateCreate' => now(),
                            'MaintainDateCreate' => now(),
                            'MaintainDateCreateVIP' => null,
                            'MaintainDateCreateVIPTest' => null,
                            'Currency' => '0',
                            'Symbol' => 'USDT',
                            'Status' => 'N',
                            'Maintain' => 'N',
                            'isPH' => 0,
                            'IsApp' => 'N',
                            'IsCC' => 'N',
                            'isQL' => 0,
                            'isShared' => 0,
                            'ProductID' => $product->ProductID,
                            'MaintainLeader' => 'N',
                        ]);
                    }
                }

                $message = "✅ <b>ĐÃ DUYỆT LÊN HÀNG NẠP TIỀN</b>\n\n"
                    . "Mã đơn: <code>{$orderCode}</code>\n"
                    . "User: {$userName}\n"
                    . "Số tiền: " . number_format($order->TotalAmount, 0, ',', '.') . " VNĐ\n"
                    . "Trạng thái: <b>ĐÃ DUYỆT</b>\n"
                    . "Thời gian: " . now()->format('d/m/Y H:i:s');
            } elseif ($action === 'reject') {

                if ($currentStatus === 'Y' || $currentStatus === 'C') {
                    $message = "❌ <b>LỆNH ĐÃ ĐƯỢC XỬ LÝ - Không thể xử lý lại</b>\n\n"
                        . "Mã đơn: <code>{$orderCode}</code>\n"
                        . "User: {$userName}\n"
                        . "Số tiền: " . number_format($order->TotalAmount, 0, ',', '.') . " VNĐ\n"
                        . "Không thể hủy đơn đã được duyệt hoặc đã bị hủy";

                    $this->sendMesssageTelegram($message);
                    DB::rollBack();
                    return response()->json(['ok' => true, 'message' => 'Cannot reject already approved or cancelled order']);
                }

                // Hủy order
                $order->update([
                    'Note' => ($order->Note ?? '') . ' [ĐÃ HỦY]',
                    'Status' => 'C'
                ]);

                $message = "❌ <b>ĐÃ HỦY LÊN HÀNG NẠP TIỀN</b>\n\n"
                    . "Mã đơn: <code>{$orderCode}</code>\n"
                    . "User: {$userName}\n"
                    . "Thời gian: " . now()->format('d/m/Y H:i:s');
            }

            DB::commit();

            // Cập nhật message trong Telegram
            $this->sendMesssageTelegram($message);

            // Lưu vào log để tracking
            $this->saveToLog($action, $orderId, $chatId, $messageId, $userId, $username, "/{$action}_order_{$orderId}");

            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Process approval error', [
                'action' => $action,
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->sendMesssageTelegram("❌ Lỗi xử lý: " . $e->getMessage());
            return response()->json(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Lưu vào bảng log để tracking
     */
    private function saveToLog($action, $orderId, $chatId, $messageId, $userId, $username, $command)
    {
        try {
            Log::info('Attempting to save to log', [
                'action' => $action,
                'order_id' => $orderId,
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'user_id' => $userId,
                'username' => $username,
                'command' => $command,
            ]);

            $log = TelegramCallbackLog::create([
                'callback_data' => $command,
                'order_id' => $orderId,
                'action' => $action,
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'callback_query_id' => 0,
                'user_id' => $userId,
                'username' => $username,
                'status' => 'completed', // Đã xử lý xong
                'processed_at' => now(),
                'created_at' => now(),
            ]);

            Log::info('Log saved successfully', [
                'log_id' => $log->id,
                'order_id' => $orderId,
                'action' => $action,
            ]);

            return $log;
        } catch (\Exception $e) {
            // Log lỗi chi tiết
            Log::error('Save to log error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'action' => $action,
                'order_id' => $orderId,
                'chat_id' => $chatId,
                'message_id' => $messageId,
            ]);
            // Không throw để không ảnh hưởng đến flow chính
            return null;
        }
    }

    /**
     * Answer callback query
     */
    private function answerCallbackQuery($callbackQueryId)
    {
        $token = config('services.telegram.token');
        $url = "https://api.telegram.org/bot{$token}/answerCallbackQuery";

        try {
            Http::post($url, [
                'callback_query_id' => $callbackQueryId
            ]);
        } catch (\Exception $e) {
            Log::error('Answer callback query error: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật message trong Telegram
     */
    private function editTelegramMessage($chatId, $messageId, $text)
    {
        // Bỏ qua nếu chat_id không hợp lệ
        if (empty($chatId) || $chatId < 0) {
            Log::warning('Invalid chat_id for edit message', ['chat_id' => $chatId]);
            return;
        }

        $token = config('services.telegram.token');
        $url = "https://api.telegram.org/bot{$token}/editMessageText";

        try {
            $response = Http::post($url, [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
                'parse_mode' => 'HTML'
            ]);

            if (!$response->successful()) {
                $responseData = $response->json();
                // Nếu không edit được, gửi message mới
                if (isset($responseData['error_code']) && in_array($responseData['error_code'], [400, 404])) {
                    Log::warning('Cannot edit message, sending new message', [
                        'chat_id' => $chatId,
                        'message_id' => $messageId,
                        'error' => $responseData['description'] ?? 'Unknown error',
                    ]);
                    $this->sendMesssageTelegram($text);
                }
            } else {
                Log::info('Message edited successfully', [
                    'chat_id' => $chatId,
                    'message_id' => $messageId,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Edit telegram message error: ' . $e->getMessage());
            // Fallback: gửi message mới
            $this->sendMesssageTelegram($text);
        }
    }
}
