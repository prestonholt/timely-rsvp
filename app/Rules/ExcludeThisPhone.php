<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExcludeThisPhone implements Rule
{
    protected $number;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = preg_replace('/\D+/', '', $value);
        return $this->number != $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot invite yourself.';
    }
}
