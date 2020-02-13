<?php

declare(strict_types=1);

namespace Application\Validator;

use Laminas\Validator\AbstractValidator;

// This validator class is designed for checking a phone number for
// conformance to the local or to the international format.
class PhoneValidator extends AbstractValidator
{
    // Phone format constants.
    const PHONE_FORMAT_LOCAL = 'local'; // Local phone format.
    const PHONE_FORMAT_INTL  = 'intl';  // International phone format.

    // Available validator options.
    protected $options = [
        'format' => self::PHONE_FORMAT_INTL
    ];

    // Validation failure message IDs.
    const NOT_SCALAR  = 'notScalar';
    const INVALID_FORMAT_INTL  = 'invalidFormatIntl';
    const INVALID_FORMAT_LOCAL = 'invalidFormatLocal';

    // Validation failure messages.
    protected $messageTemplates = [
        self::NOT_SCALAR  => "The phone number must be a scalar value",
        self::INVALID_FORMAT_INTL => "The phone number must be in international format",
        self::INVALID_FORMAT_LOCAL => "The phone number must be in local format",
    ];

    // Constructor.
    public function __construct($options = null)
    {
        // Set filter options (if provided).
        if (is_array($options)) {
            if (isset($options['format'])) {
                $this->setFormat($options['format']);
            }
        }

        // Call the parent class constructor.
        parent::__construct($options);
    }

    // Sets phone format.
    public function setFormat($format)
    {
        // Check input argument.
        if (
            $format != self::PHONE_FORMAT_LOCAL &&
            $format != self::PHONE_FORMAT_INTL
        ) {
            throw new \Exception('Invalid format argument passed.');
        }

        $this->options['format'] = $format;
    }

    // Validates a phone number.
    public function isValid($value)
    {
        if (! is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false; // Phone number must be a scalar.
        }

        // Convert the value to string.
        $value = (string)$value;

        $format = $this->options['format'];

        // Determine the correct length and pattern of the phone number,
        // depending on the format.
        if ($format == self::PHONE_FORMAT_INTL) {
            $correctLength = 16;
            $pattern = '/^\d \(\d{3}\) \d{3}-\d{4}$/';
        } else { // self::PHONE_FORMAT_LOCAL
            $correctLength = 8;
            $pattern = '/^\d{3}-\d{4}$/';
        }

        // First check phone number length
        $isValid = false;
        if (strlen($value) == $correctLength) {
            // Check if the value matches the pattern.
            if (preg_match($pattern, $value)) {
                $isValid = true;
            }
        }

        // If there was an error, set error message.
        if (! $isValid) {
            if ($format == self::PHONE_FORMAT_INTL) {
                $this->error(self::INVALID_FORMAT_INTL);
            } else {
                $this->error(self::INVALID_FORMAT_LOCAL);
            }
        }

        // Return validation result.
        return $isValid;
    }
}
