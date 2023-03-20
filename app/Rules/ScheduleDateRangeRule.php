<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DateTime;

class ScheduleDateRangeRule implements Rule
{
    private const MAXIMUM_DAYS_PERIOD = 31;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $input = request()->only('date_start');
        if (isset($input['date_start'])) {
            $this->date_start = $input['date_start'];
        }
        $this->date_start = false;
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
        if (!$this->date_start) {
            return false;
        }
        $date_start = new DateTime($this->date_start);
        $date_end = new DateTime($value);
        $interval = $date_start->diff($date_end);
        $days = (int) $interval->format('%a');

        if ($days > self::MAXIMUM_DAYS_PERIOD) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Period cannot exceed 31 days');
    }
}
