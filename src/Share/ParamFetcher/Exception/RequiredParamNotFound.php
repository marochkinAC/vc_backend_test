<?php


namespace Ads\Share\ParamFetcher\Exception;


use Throwable;

class RequiredParamNotFound extends ParamFetcherException
{
    private string $param;

    public function __construct(string $param, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->param = $param;
        parent::__construct($message, $code, $previous);
    }


    /**
     * @return string
     */
    public function getParam(): string
    {
        return $this->param;
    }

}