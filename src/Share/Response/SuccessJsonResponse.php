<?php
declare(strict_types=1);

namespace Ads\Share\Response;


class SuccessJsonResponse extends JsonResponse
{
    const SUCCESS_MSG = 'OK';

    public function __construct(array $data, $msg = self::SUCCESS_MSG, $code = self::HTTP_OK)
    {
        parent::__construct($msg, $code, $data);
    }
}