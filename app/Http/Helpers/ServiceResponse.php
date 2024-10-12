<?php

/**
 * Service Response
 *
 * @author Mohamed Alansary
 */
namespace App\Http\Helpers;

use App\Enums\StatusCodes;
use Illuminate\Http\JsonResponse;

class ServiceResponse
{
    private string $message;
    private bool $status;
    private mixed $data;
    private int $status_code;

    public function __construct(string $message, bool $status, mixed $data, ?int $status_code = null)
    {
        $this->message = $message;
        $this->status = $status;
        $this->data = $data;
        $this->status_code = $status_code ?? JsonResponse::HTTP_OK;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    public function setStatusCode(int $status_code): void
    {
        $this->status_code = $status_code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getStatusCode(): int
    {
        return $this->status_code;
    }

    public function getRepr(): array
    {
       return [
           'message' => $this->getMessage(),
           'status' => $this->getStatus(),
           'data' => $this->getData()
       ];
    }
}
