<?php

namespace App\Http\Actions\v1;

use App\Services\AccountService;
use App\Models\Schedule;
use App\DTO\ScheduleRepeatability;
use DatePeriod;
use DateTime;
use DateInterval;

class ScheduleAction
{
    private const SCHEDULES_DEFAULT_LIMIT = 20;

    public function __construct(AccountService $accountService) {
        $this->accountService = $accountService;
    }

    public function get(array $input) 
    {
        $schedules = $this->scheduleFilter($input);
        $schedules = $schedules->with('department')
                ->with('schedule_setting')
                ->with('subject')
                ->with('group')
                ->with('teacher')
                ->with('building')
                ->with('building_classroom')
                ->paginate(self::SCHEDULES_DEFAULT_LIMIT);

        $schedulesReatabilities = $this->getRepeatabilities($schedules->toArray()['data'], $input['date_start'], $input['date_end']);

        return [
            'data' => $schedules, 
            'includes' => [
                'repeatabilities' => $schedulesReatabilities
            ]
        ];
    }

    private function scheduleFilter(array $input)
    {
        $dateStart = date('Y-m-d', strtotime($input['date_start']));
        $dateEnd = date('Y-m-d', strtotime($input['date_end']));

        $filter = Schedule::where('account_id', $this->accountService->getId())
            ->where('repeat_start', '<=', $dateEnd)
            ->where('repeat_end', '>=', $dateStart)
            ->orderBy('repeat_start', 'asc')
            ->orderBy('day_of_week', 'asc')
            ->orderBy('schedule_setting_item_order', 'asc');

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

    private function getRepeatabilities($schedulesList, string $dateStart, string $dateEnd): array
    {
        $dateStart = date('Y-m-d', strtotime($dateStart));
        $dateEnd = date('Y-m-d', strtotime($dateEnd));
        $result = Array();

        foreach ($schedulesList as $schedule) {
            $interval = $this->getInterval($schedule['repeatability']);
            $currentDay = date('Y-m-d', strtotime($dateStart . ' last Sunday +' . $schedule['day_of_week'] . ' days'));
            while ($currentDay > $dateStart && $currentDay < $dateEnd) {
                if ($this->forThisWeek($currentDay, $schedule['repeatability'])) {
                    $result[] = new ScheduleRepeatability(
                        $schedule['id'],
                        $currentDay
                    );
                    if ($interval == null) {
                        break;
                    }
                }
                $currentDay = date('Y-m-d', strtotime($currentDay . $interval));
            }
        }

        return $result;
    }

    private function getInterval(int $repeatability): ?string
    {
        switch ($repeatability) {
            case Schedule::REPEATABILITIES['EVERY']:
                $interval = ' + 1 week';
                break;
            case Schedule::REPEATABILITIES['EVEN']:
            case Schedule::REPEATABILITIES['ODD']:
                $interval = ' + 2 weeks';
                break;
            default:
                $interval = null;
        }

        return $interval;
    }

    private function forThisWeek(string $date, int $repeatability): bool
    {
        $week = date('W', strtotime($date));
        if (
            ($week % 2 === 0 && $repeatability == Schedule::REPEATABILITIES['ODD']) || 
            ($week % 2 !== 0 && $repeatability == Schedule::REPEATABILITIES['EVEN'])
        ) {
            return false;
        }
        return true;
    }
}
