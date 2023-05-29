<?php

declare(strict_types=1);

namespace App\Email\Verify;

use App\Validator\Exception\ValidateRequiredException;
use App\Validator\Validate;

final class VerifyValidator extends Validate
{
    public array $requiredFields = [
        'subject'
    ];

    /**
     * @throws ValidateRequiredException
     */
    public function __construct(array $fields)
    {
        parent::__construct($fields);
        $this->validateRequired();
    }
}