<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class WorkingHours implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $date = date_create($value);
        $weekDay = $date->format('w');
        $hour = $date->format('G');
        $workingDay = $weekDay > 0 && $weekDay < 6;
        $workingHour = $hour > 8 && $hour < 18;
        return $workingDay && $workingHour;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Schedule out of working hours';
    }
}
