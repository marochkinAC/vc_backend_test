<?php


namespace Ads\Share\ParamFetcher;


use Ads\Share\ParamFetcher\Exception\NotCorrectTypeParam;
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
     * @return string
     * @throws RequiredParamNotFound
     * @throws NotCorrectTypeParam
     */
    public function getRequiredString(string $name): string
    {
        $param = $this->checkRequiredParam($this->inputBag->get($name), $name);
        if (is_string($param)) {
            return $param;
        }
        throw new NotCorrectTypeParam('Param `' . $name . '` have invalid type');
    }

    /**
     * @return mixed
     * @throws RequiredParamNotFound
     */
    private function checkRequiredParam($param, $name)
    {
        if (!isset($param)) {
            throw new RequiredParamNotFound($name, 'Required param `' . $name . '` not found');
        }
        return $param;
    }

    /**
     * @param string $name
     * @return int
     * @throws RequiredParamNotFound
     * @throws NotCorrectTypeParam
     */
    public function getRequiredInt(string $name): int
    {
        $param = $this->checkRequiredParam($this->inputBag->get($name), $name);
        if (is_numeric($param)) {
            return (int)$param;
        }
        throw new NotCorrectTypeParam('Param `' . $name . '` have invalid type');
    }

    /**
     * @param string $name
     * @return float
     * @throws RequiredParamNotFound
     * @throws NotCorrectTypeParam
     */
    public function getRequiredFloat(string $name): float
    {
        $param = $this->checkRequiredParam($this->inputBag->get($name), $name);
        if (is_numeric($param)) {
            return (float)$param;
        }
        throw new NotCorrectTypeParam('Param `' . $name . '` have invalid type');
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getOptionalString(string $name): ?string
    {
        $result = $this->inputBag->get($name);
        if (is_null($result)) {
            return null;
        }
        return (string)$result;
    }

    /**
     * @param string $name
     * @return int|null
     */
    public function getOptionalInt(string $name): ?int
    {
        $result = $this->inputBag->get($name);
        if (is_numeric($result)) {
            return (int)$result;
        }
        return null;
    }

    /**
     * @param string $name
     * @return float|null
     */
    public function getOptionalFloat(string $name): ?float
    {
        $result = $this->inputBag->get($name);
        if (is_numeric($result)) {
            return (float)$result;
        }
        return null;
    }

    /**
     * @param Request $request
     * @return self
     */
    public static function fromRequestGet(Request $request): self
    {
        return new self($request->query);
    }

    /**
     * @param Request $request
     * @return self
     */
    public static function fromRequestPost(Request $request): self
    {
        return new self($request->request);
    }
}