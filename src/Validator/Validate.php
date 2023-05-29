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

    /**
     * @throws ValidateRequiredException
     */
    public function validateRequired(array $fields = []): void
    {
        $fields = $fields ?: $this->requiredFields;
        foreach ($fields as $requiredField) {
            if (is_array($requiredField)) {
                $this->validateRequired($requiredField);
            } else {
                $inArray = preg_match('/"'.preg_quote($requiredField, '/').'"/i' , json_encode($this->fields));
                if (!$inArray) {
                    throw new ValidateRequiredException("Field `$requiredField` has empty value or does not exists.");
                }
            }
        }
    }
}