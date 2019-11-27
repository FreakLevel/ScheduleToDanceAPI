<?php

namespace App\Rules;

use App\Appointment;
use App\Http\Resources\AppointmentResource;
use Illuminate\Contracts\Validation\Rule;

class FreeAppointment implements Rule
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
        $schedule = date_create_from_format('Y-m-d H:i', $value);
        $before = $schedule->modify('-1 hours');
        $after = $schedule->modify('+1 hours');
        $appointments = AppointmentResource::collection(Appointment::
            where([
                ['schedule', '>=', $before],
                ['schedule', '<=', $after]
            ])->get());
        return count($appointments->collection->all()) === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Appointment bumps into another.';
    }
}
