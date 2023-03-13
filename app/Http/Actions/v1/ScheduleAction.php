<?php

namespace App\Http\Actions\v1;

use App\Services\AccountService;
use App\Models\Schedule;
use DatePeriod;
use DateTime;
use DateInterval;

class ScheduleAction
{
    private const WEEK_NEXT = 'next';

    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

    public function get(array $input)
    {
        if (isset($input['date'])) {
            return $this->getByDate($input);
        }

        if (isset($input['week'])) {
            return $this->getByWeek($input);
        }

        abort(404);
    }

    private function getByDate(array $input)
    {
        $selectDate = strtotime($input['date']);
        $day = date('N', $selectDate);
        $date = date('Y-m-d', $selectDate);
        
        $schedules = Schedule::where('account_id', $this->accountService->getId())
                ->where('repeat_start', '<=', $date)
                ->where('repeat_end', '>=', $date)
                ->where('day_of_week', $day)
                ->orderBy('shedule_setting_item_order', 'asc')
                ->with('department')
                ->with('schedule_setting')
                ->with('subject')
                ->with('teacher');

        $week = date('W', $selectDate);
        if ($week % 2 === 0) {
            $schedules->where('repeatability', '!=', Schedule::REPEATABILITIES['ODD']);
        } else {
            $schedules->where('repeatability', '!=', Schedule::REPEATABILITIES['EVEN']);
        }

        if (isset($input['group_id'])) {
            $schedules->where('group_id', $input['group_id']);
        }

        if (isset($input['teacher_id'])) {
            $schedules->where('teacher_id', $input['teacher_id']);
        }

        $schedules = $schedules->get();

        foreach ($schedules as $schedule) {
            $schedule['schedule_setting_item'] = $schedule->schedule_setting_item();
        }

        return $schedules;
    }


    private function getByWeek(array $input)
    {
        if ($input['week'] == self::WEEK_NEXT) {
            $week_start = date('Y-m-d', strtotime('Next monday'));
            $week_end = date('Y-m-d', strtotime('Next monday +7 days'));
        } else {
            $week_start = date('Y-m-d', strtotime('Monday this week'));
            $week_end = date('Y-m-d', strtotime('Monday this week +7 days'));
        }

        $period = new DatePeriod(
            new DateTime($week_start),
            new DateInterval('P1D'),
            new DateTime($week_end)
        );
        
        $dates = array();
        foreach ($period as $date) {
            $day = (string) $date->format('Y-m-d');
            $input['date'] = $day;
            $schedules = $this->getByDate($input)->toArray();
            $dates[$day] = !empty($schedules) ? $schedules : null;
        }
        
        return $dates;
    }
}
