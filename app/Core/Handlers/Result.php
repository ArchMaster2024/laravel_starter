<?php

namespace App\Core\Handlers;

use Exception;

readonly class Result {
    public function __construct(
        private bool $_isSuccess,
        private mixed $content = null
    ){}

    public function isSuccess() : bool
    {
        return $this->_isSuccess === true;
    }

    public function isFailure() : bool
    {
        return $this->_isSuccess === false;
    }

    public function isException() : bool
    {
        return $this->content instanceof Exception;
    }

    public function getContent() : mixed
    {
        return $this->content;
    }

    public static function Success(mixed $content = null) : Result
    {
        return new Result(true, $content);
    }

    public static function Failure(mixed $content = null) : Result
    {
        return new Result(false, $content);
    }
}
