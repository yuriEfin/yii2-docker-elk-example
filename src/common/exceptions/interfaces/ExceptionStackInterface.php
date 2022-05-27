<?php

namespace common\exceptions\interfaces;

interface ExceptionStackInterface
{
    public function getCategory(): string;
}