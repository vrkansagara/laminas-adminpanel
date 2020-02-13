<?php declare(strict_types=1);

namespace Application\Filter;

use Laminas\Filter\AbstractFilter;

// This filter class is designed for transforming an arbitrary phone number to
// the local or the international format.
class PhoneFilter extends AbstractFilter
{
    // Phone format constants.
    const PHONE_FORMAT_LOCAL = 'local'; // Local phone format
    const PHONE_FORMAT_INTL = 'intl';  // International phone format

    // Available filter options.
    protected $options = [
        'format' => self::PHONE_FORMAT_INTL
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

    // Returns phone format.
    public function getFormat()
    {
        return $this->format;
    }

    // Filters a phone number.
    public function filter($value)
    {
        if (! is_scalar($value)) {
            // Return non-scalar value unfiltered.
            return $value;
        }

        $value = (string)$value;

        if (strlen($value) == 0) {
            // Return empty value unfiltered.
            return $value;
        }

        // First, remove any non-digit character.
        $digits = preg_replace('#[^0-9]#', '', $value);

        $format = $this->options['format'];

        if ($format == self::PHONE_FORMAT_INTL) {
            // Pad with zeros if the number of digits is incorrect.
            $digits = str_pad($digits, 11, "0", STR_PAD_LEFT);

            // Add the braces, the spaces, and the dash.
            $phoneNumber = substr($digits, 0, 1) . ' (' .
                substr($digits, 1, 3) . ') ' .
                substr($digits, 4, 3) . '-' .
                substr($digits, 7, 4);
        } else { // self::PHONE_FORMAT_LOCAL
            // Pad with zeros if the number of digits is incorrect.
            $digits = str_pad($digits, 7, "0", STR_PAD_LEFT);

            // Add the dash.
            $phoneNumber = substr($digits, 0, 3) . '-' . substr($digits, 3, 4);
        }

        return $phoneNumber;
    }
}
