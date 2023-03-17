<?php

namespace App\Http\Actions\v1;

use App\Services\AccountService;
use App\Models\Schedule;
use DatePeriod;
use DateTime;
use DateInterval;

class ScheduleAction
{
    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

    public function get(array $input)
    {
        $dateStart = date('Y-m-d', strtotime($input['date_start']));
        $dateEnd = date('Y-m-d', strtotime($input['date_end'] . ' +1 day'));

        $period = new DatePeriod(
            new DateTime($dateStart),
            new DateInterval('P1D'),
            new DateTime($dateEnd)
        );

        $dates = Array();

        foreach ($period as $date) {
            $day = (string) $date->format('Y-m-d');
            $input['date'] = $day;
            $schedules = $this->getByDate($input)->toArray();
            if (!empty($schedules)) {
                $dates[$day] = $schedules;
            }
        }

        return $dates;
    }

    private function getByDate(array $input)
    {
        $filteredSchedules = $this->scheduleFilter($input);
        $schedules = $filteredSchedules->get();

        foreach ($schedules as $schedule) {
            $schedule['schedule_setting_item'] = $schedule->schedule_setting_item();
        }

        return $schedules;
    }

    private function scheduleFilter(array $input)
    {
        $selectDate = strtotime($input['date']);
        $day = date('N', $selectDate);
        $date = date('Y-m-d', $selectDate);
        
        $filter = Schedule::where('account_id', $this->accountService->getId())
                ->orderBy('shedule_setting_item_order', 'asc')
                ->where('repeat_start', '<=', $date)
                ->where('repeat_end', '>=', $date)
                ->where('day_of_week', $day)
                ->with('department')
                ->with('schedule_setting')
                ->with('subject')
                ->with('group')
                ->with('teacher')
                ->with('building')
                ->with('building_classroom');

        $week = date('W', $selectDate);
        if ($week % 2 === 0) {
            $filter->where('repeatability', '!=', Schedule::REPEATABILITIES['ODD']);
        } else {
            $filter->where('repeatability', '!=', Schedule::REPEATABILITIES['EVEN']);
        }

        if (isset($input['group_id'])) {
            $filter->where('group_id', $input['group_id']);
        }

        if (isset($input['teacher_id'])) {
            $filter->where('teacher_id', $input['teacher_id']);
        }

        if (isset($input['building_id']) && !isset($input['building_classroom_id'])) {
            $filter->where('building_id', $input['building_id']);
        }

        if (isset($input['building_classroom_id'])) {
            $filter->where('building_classroom_id', $input['building_classroom_id']);
        }

        return $filter;
    }
}
