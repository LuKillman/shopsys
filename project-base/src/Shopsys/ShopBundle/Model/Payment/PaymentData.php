<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Payment;

use Shopsys\FrameworkBundle\Model\Payment\PaymentData as BasePaymentData;

/**
 * @property \Shopsys\ShopBundle\Model\Transport\Transport[] $transports
 */
class PaymentData extends BasePaymentData
{
    public function __construct()
    {
        parent::__construct();
    }
}
