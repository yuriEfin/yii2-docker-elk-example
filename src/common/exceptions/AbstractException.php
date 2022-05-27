<?php

namespace common\exceptions;

use common\exceptions\interfaces\ExceptionStackInterface;
use Exception;
use Throwable;
use Yii;

abstract class AbstractException extends Exception implements ExceptionStackInterface
{
    protected string $category = 'app';
    
    public function getCategory(): string
    {
        return $this->category;
    }
    
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(Yii::t($this->getCategory(), $message), $code, $previous);
    }
}