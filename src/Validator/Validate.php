<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validator\Exception\ValidateRequiredException;

abstract class Validate
{
    private array $fields = [];
    protected array $requiredFields = [];

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function validateRequired(): void
    {
        foreach ($this->requiredFields as $requiredField) {
            $inArray = preg_match('/"'.preg_quote($requiredField, '/').'"/i' , json_encode($this->fields));
            if (!$inArray) {
                throw new ValidateRequiredException("Field `$requiredField` has empty value or does not exists.");
            }
        }
    }
}