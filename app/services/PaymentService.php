<?php

class PaymentService
{
    public function __construct(private Repository $repo, private array $config)
    {
    }

    public function createManualOrder(int $userId, int $planId): int
    {
        $plan = $this->repo->fetchOne('SELECT * FROM plans WHERE id = :id', ['id' => $planId]);
        if (!$plan) {
            throw new RuntimeException('Plan bulunamadı.');
        }
        return $this->repo->createOrder($userId, $planId, (float)$plan['price'], 'manual');
    }

    public function markPaidByAdmin(int $orderId, int $adminId): bool
    {
        $result = $this->repo->markOrderPaid($orderId, 'MANUAL-' . $orderId);
        if ($result) {
            $this->repo->logAudit($adminId, 'payment.manual_approved', ['order_id' => $orderId]);
        }
        return $result;
    }

    // Adapter skeleton for future iyzico integration (terminal-free compatible)
    public function iyzicoBuildCheckoutPayload(array $order): array
    {
        return [
            'conversationId' => 'order_' . $order['id'],
            'price' => (string)$order['amount'],
            'paidPrice' => (string)$order['amount'],
            'currency' => 'TRY',
            'basketId' => 'PLAN_' . $order['plan_id'],
            'paymentGroup' => 'PRODUCT',
        ];
    }
}
