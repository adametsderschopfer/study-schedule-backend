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

    private function scheduleFilter(array $input)
    {
        $selectDate = strtotime($input['date']);
        $day = date('N', $selectDate);
        $date = date('Y-m-d', $selectDate);
        
        $filter = Schedule::where('account_id', $this->accountService->getId())
                ->where('repeat_start', '<=', $date)
                ->where('repeat_end', '>=', $date)
                ->where('day_of_week', $day)
                ->orderBy('shedule_setting_item_order', 'asc')
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

    private function getByDate(array $input)
    {
        $filteredSchedules = $this->scheduleFilter($input);
        $schedules = $filteredSchedules->get();

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

    public function getCountByYear(string $year, array $input): array
    {
        $year_start = date('Y-m-d', strtotime('First day of January ' . $year));
        $year_end = date('Y-m-d', strtotime('First day of January ' . $year . ' +1 year'));

        $period = new DatePeriod(
            new DateTime($year_start),
            new DateInterval('P1D'),
            new DateTime($year_end)
        );

        $days = Array();

        foreach ($period as $date) {
            $day = (string) $date->format('Y-m-d');
            $input['date'] = $day;
            $count = $this->scheduleFilter($input)->count();
            $days[$day] = $count;
        }

        return $days;
    }
}
