<?php

declare(strict_types=1);


namespace App\Domain\Model\Shop\Order\Factory;

use App\Domain\Model\Shop\Order\Entity\Order;
use App\Domain\Model\Shop\Order\Entity\OrderCreated;
use DateTimeImmutable;

/**
 * @author Dmitry S
 */
class OrderFactory
{
    public function create(): Order
    {
        $order = new Order();
        $order
            ->setOrderCreated(OrderCreated::create());
        return $order;
    }
}