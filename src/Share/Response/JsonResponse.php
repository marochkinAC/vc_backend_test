<?php
declare(strict_types=1);

namespace Ads\Share\Response;


use Symfony\Component\HttpFoundation\Response;

class JsonResponse extends Response
{

    protected string $msg = '';
    protected int $code = 0;
    protected array $data = [];

    public function __construct(string $msg, int $code, array $data, int $status = 200)
    {
        parent::__construct();
        $this->headers->set('Content-Type', 'application/json');
        $this->setStatusCode($status);
        $this->setContent(json_encode([
            'message' => $msg,
            'code' => $code,
            'data' => $data,
        ]));
    }
}