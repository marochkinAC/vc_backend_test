<?php


namespace Ads\Share\ParamFetcher;


use Ads\Share\ParamFetcher\Exception\RequiredParamNotFound;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ParamFetcher
 * Класс для извлечения параметров запроса, позволяет валидировать наличие параметров в запросе
 * @package Ads\Share\ParamFetcher
 */
class ParamFetcher
{
    private InputBag $inputBag;

    /**
     * ParamFetcher constructor.
     * @param InputBag $inputBag
     */
    public function __construct(InputBag $inputBag)
    {
        $this->inputBag = $inputBag;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws RequiredParamNotFound
     */
    public function getRequiredString(string $name): string
    {
        $param = $this->inputBag->get($name);
        return $this->checkRequiredParam($param, $name);
    }

    /**
     * @throws RequiredParamNotFound
     */
    private function checkRequiredParam($param, $name)
    {
        if (!$param) {
            throw new RequiredParamNotFound($name, 'Required param ' . $name . ' not found');
        }
        return $param;
    }

    /**
     * @param string $name
     * @return int
     * @throws RequiredParamNotFound
     */
    public function getRequiredInt(string $name): int
    {
        $param = $this->inputBag->getInt($name);
        return $this->checkRequiredParam($param, $name);
    }

    /**
     * @param string $name
     * @return float
     * @throws RequiredParamNotFound
     */
    public function getRequiredFloat(string $name): float
    {
        $param = (float)$this->inputBag->getDigits($name);
        return $this->checkRequiredParam($param, $name);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getOptionalString(string $name): ?string
    {
        return $this->inputBag->get($name);
    }

    /**
     * @param string $name
     * @return int|null
     */
    public function getOptionalInt(string $name): ?int
    {
        return $this->inputBag->getInt($name);
    }

    /**
     * @param string $name
     * @return float|null
     */
    public function getOptionalFloat(string $name): ?float
    {
        return (float)$this->inputBag->getDigits($name);
    }

    /**
     * @param Request $request
     * @return static
     */
    public static function fromRequestGet(Request $request): self
    {
        return new self($request->query);
    }

    /**
     * @param Request $request
     * @return static
     */
    public static function fromRequestPost(Request $request): self
    {
        return new self($request->request);
    }
}