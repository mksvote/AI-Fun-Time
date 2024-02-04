<?php

/**
 * @package StripeResponse
 * @author TechVillage <support@techvill.org>
 * @contributor Muhammad AR Zihad <[zihad.techvill@gmail.com]>
 * @created 06-02-2022
 */

namespace Modules\Stripe\Response;

use Modules\Gateway\Contracts\HasDataResponseInterface;
use Modules\Gateway\Response\Response;

class StripeResponse extends Response implements HasDataResponseInterface
{
    protected $response;
    private $data;

    /**
     * Initialization
     *
     * @return $this
     */
    public function __construct($data, $stripeResponse)
    {
        $this->data = $data;
        $this->response = $stripeResponse->jsonSerialize();
        $this->updateStatus();
        return $this;
    }

    /**
     * Get Raw Response
     *
     * @return string
     */
    public function getRawResponse(): string
    {
        return json_encode($this->response);
    }

    /**
     * Update Status
     *
     * @return void
     */
    protected function updateStatus()
    {
        if ($this->response['status'] == 'succeeded') {
            $this->setPaymentStatus('completed');
        } else {
            $this->setPaymentStatus('failed');
        }
    }

    /**
     * Get Response
     *
     * @return string
     */
    public function getResponse(): string
    {
        return json_encode($this->getSimpleResponse());
    }

    /**
     * Get Simple Response
     *
     * @return array
     */
    private function getSimpleResponse()
    {
        return [
            'amount' => $this->response['amount'] / 100,
            'amount_captured' => $this->response['amount_captured'] / 100,
            'currency' => $this->response['currency'],
            'code' => $this->data->code
        ];
    }

    /**
     * Get Gateway
     *
     * @return string
     */
    public function getGateway(): string
    {
        return 'Stripe';
    }

    /**
     * Set Payment Status
     *
     * @return void
     */
    protected function setPaymentStatus($status)
    {
        $this->status = $status;
    }
}