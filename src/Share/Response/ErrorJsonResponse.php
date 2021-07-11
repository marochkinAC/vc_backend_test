<?php
declare(strict_types=1);

namespace Ads\Share\Response;


class ErrorJsonResponse extends JsonResponse
{
    public function __construct(string $msg, array $data, $code = self::HTTP_BAD_REQUEST)
    {
        parent::__construct($msg, $code, $data);
    }
}