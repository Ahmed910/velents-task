<?php

namespace App\DesignPatterns\Strategy\Payment;

class InvoiceRequest implements RequestDataInterface
{
    private int $invoiceValue;
    private string $customerName;
    private string $notificationOption;
    private string $callBackUrl;
    private string $errorUrl;

    public function __construct(int $invoiceValue, string $customerName, string $notificationOption, string $callBackUrl, string $errorUrl)
    {
       $this->invoiceValue = $invoiceValue;
       $this->customerName = $customerName;
       $this->notificationOption = $notificationOption;
       $this->callBackUrl = $callBackUrl;
       $this->errorUrl = $errorUrl;
    }

   public function getRequestData(): array
   {
      return [
        'InvoiceValue' => $this->invoiceValue,
        'CustomerName' => $this->customerName,
        'NotificationOption' => $this->notificationOption,
        'CallBackUrl' => $this->callBackUrl,
        'ErrorUrl' => $this->errorUrl,
      ];
   }
}