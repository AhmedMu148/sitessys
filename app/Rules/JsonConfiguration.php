<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class JsonConfiguration implements ValidationRule
{
    protected string $type;
    protected array $schema;

    public function __construct(string $type, array $schema)
    {
        $this->type = $type;
        $this->schema = $schema;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_array($value)) {
            $fail("The {$attribute} must be a valid JSON object.");
            return;
        }

        $this->validateAgainstSchema($value, $this->schema, $attribute, $fail);
    }

    /**
     * Validate value against schema recursively
     */
    protected function validateAgainstSchema(array $data, array $schema, string $attribute, Closure $fail): void
    {
        foreach ($schema as $key => $rules) {
            if (is_array($rules)) {
                // Nested validation
                if (isset($data[$key]) && is_array($data[$key])) {
                    $this->validateAgainstSchema($data[$key], $rules, "{$attribute}.{$key}", $fail);
                } elseif ($this->isRequired($rules)) {
                    $fail("The {$attribute}.{$key} field is required.");
                }
            } else {
                // Direct validation rules
                if ($this->isRequired($rules) && !isset($data[$key])) {
                    $fail("The {$attribute}.{$key} field is required.");
                    continue;
                }

                if (isset($data[$key])) {
                    $this->validateField($data[$key], $rules, "{$attribute}.{$key}", $fail);
                }
            }
        }
    }

    /**
     * Check if field is required
     */
    protected function isRequired($rules): bool
    {
        if (is_string($rules)) {
            return str_contains($rules, 'required');
        }
        return false;
    }

    /**
     * Validate individual field
     */
    protected function validateField($value, string $rules, string $attribute, Closure $fail): void
    {
        $ruleArray = explode('|', $rules);

        foreach ($ruleArray as $rule) {
            if (str_starts_with($rule, 'string')) {
                if (!is_string($value)) {
                    $fail("The {$attribute} must be a string.");
                }
            } elseif (str_starts_with($rule, 'integer')) {
                if (!is_integer($value)) {
                    $fail("The {$attribute} must be an integer.");
                }
            } elseif (str_starts_with($rule, 'boolean')) {
                if (!is_bool($value)) {
                    $fail("The {$attribute} must be a boolean.");
                }
            } elseif (str_starts_with($rule, 'array')) {
                if (!is_array($value)) {
                    $fail("The {$attribute} must be an array.");
                }
            } elseif (str_starts_with($rule, 'max:')) {
                $max = (int) substr($rule, 4);
                if (is_string($value) && strlen($value) > $max) {
                    $fail("The {$attribute} may not be greater than {$max} characters.");
                } elseif (is_array($value) && count($value) > $max) {
                    $fail("The {$attribute} may not have more than {$max} items.");
                }
            } elseif (str_starts_with($rule, 'min:')) {
                $min = (int) substr($rule, 4);
                if (is_string($value) && strlen($value) < $min) {
                    $fail("The {$attribute} must be at least {$min} characters.");
                } elseif (is_array($value) && count($value) < $min) {
                    $fail("The {$attribute} must have at least {$min} items.");
                } elseif (is_integer($value) && $value < $min) {
                    $fail("The {$attribute} must be at least {$min}.");
                }
            } elseif (str_starts_with($rule, 'size:')) {
                $size = (int) substr($rule, 5);
                if (is_string($value) && strlen($value) !== $size) {
                    $fail("The {$attribute} must be exactly {$size} characters.");
                }
            } elseif (str_starts_with($rule, 'regex:')) {
                $pattern = substr($rule, 6);
                if (is_string($value) && !preg_match($pattern, $value)) {
                    $fail("The {$attribute} format is invalid.");
                }
            } elseif (str_starts_with($rule, 'in:')) {
                $values = explode(',', substr($rule, 3));
                if (!in_array($value, $values)) {
                    $fail("The selected {$attribute} is invalid.");
                }
            }
        }
    }
}
