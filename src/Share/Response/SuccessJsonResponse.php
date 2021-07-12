<?php
declare(strict_types=1);

namespace Ads\Share\Response;


class SuccessJsonResponse extends JsonResponse
{
    const SUCCESS_MSG = 'OK';

    public function __construct(array $data, string $msg = self::SUCCESS_MSG, int $code = self::HTTP_OK)
    {
        parent::__construct($msg, $code, $data);
    }
}