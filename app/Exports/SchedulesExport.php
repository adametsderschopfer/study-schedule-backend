<?php

namespace App\Exports;

use App\Models\Schedule;
use App\Models\Account;
use App\Services\AccountService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use App\Http\Actions\v1\ScheduleAction;
use App\Http\Requests\ScheduleGetRequest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SchedulesExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $schedules;

    private const UNIVERSITY_HEADINGS = [
        'Дата',
        'Время',
        'Расписание',
        'Корпус', 
        'Аудитория',
        'Факультет',
        'Кафедра',
        'Предмет', 
        'Группа', 
        'Преподаватель'
    ];

    private const COLLEGE_HEADINGS = [
        'Дата',
        'Время',
        'Расписание',
        'Аудитория',
        'Кафедра',
        'Предмет', 
        'Группа', 
        'Преподаватель'
    ];

    private const SCHOOL_HEADINGS = [
        'Дата',
        'Время',
        'Расписание',
        'Кабинет',
        'Предмет', 
        'Группа', 
        'Преподаватель'
    ];

    public function headings(): array
    {
        switch ($this->accountService->getType()) {
            case Account::TYPES['COLLEGE'] :
                return self::COLLEGE_HEADINGS;
                break;
            case Account::TYPES['SCHOOL'] :
                return self::SCHOOL_HEADINGS;
                break;
            default:
                return self::UNIVERSITY_HEADINGS;
        }
    }

    public function registerEvents(): array
    {
        $mergedRows = [];

        return [
            AfterSheet::class => function(AfterSheet $event) use (&$mergedRows) {
                $cellRange = 'A1:W1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12)->setBold(true);
                // Начало части кода, созданного с помощью неросети
                $schedules = $this->array();
                $rowCount = count($schedules);
    
                for ($i = 0; $i < $rowCount; $i++) {
                    if (isset($mergedRows[$i])) {
                        continue;
                    }
    
                    $currentDate = $schedules[$i][0];
                    $startRow = $i + 2;
                    $endRow = $i + 2;
    
                    for ($j = $i + 1; $j < $rowCount; $j++) {
                        if ($schedules[$j][0] === $currentDate) {
                            $endRow++;
                            $mergedRows[$j] = true;
                        } else {
                            break;
                        }
                    }
    
                    if ($startRow !== $endRow) {
                        $event->sheet->getDelegate()->mergeCells("A{$startRow}:A{$endRow}");
                    }
                }
            },
            // Конец части кода, созданного с помощью неросети
        ];
    }

    public function __construct(
        Collection $schedules, 
        Collection $repeatabilities,
        AccountService $accountService
    ) {
        $this->schedules = $schedules;
        $this->repeatabilities = $repeatabilities;
        $this->accountService = $accountService;
    }

    public function array(): array
    {
        $repeatabilityResult = [];
        foreach ($this->repeatabilities as $repeatability) {
            $repeatabilityResult[$repeatability->date][] = $repeatability->id;
        }
        $repeatabilityResult = collect($repeatabilityResult)->sortKeys();

        foreach ($repeatabilityResult as $date => $ids) {
            $table[] = $this->getSchedulesByDate($date, $ids);
        }

        $schedules = collect($table)->collapse()->toArray();

        return $schedules;
    }

    private function getscheduleData(array $schedule): array
    {
        if ($this->accountService->getType() == Account::TYPES['COLLEGE']) {
            return [
                $schedule['date'] ? date("d.m.Y", strtotime($schedule['date'])) : '',
                $schedule['schedule_setting_item'] ? $schedule['schedule_setting_item'][0]['time_start']->format('H:i') . ' - ' . $schedule['schedule_setting_item'][0]['time_end']->format('H:i') : '',
                $schedule['schedule_setting_id'] ? $schedule['schedule_setting']['name'] : '',
                $schedule['building_classroom_id'] ? $schedule['building_classroom']['name'] : '',
                $schedule['department'] && $schedule['department']['faculty'] ? $schedule['department']['faculty']['name'] : '',
                $schedule['subject_id'] ? $schedule['subject']['name'] : '',
                $schedule['group_id'] ? $schedule['group']['name'] . ' ' . $schedule['group']['letter'] : '',
                $schedule['teacher_id'] ? $schedule['teacher']['full_name'] : '',
            ];
        }

        if ($this->accountService->getType() == Account::TYPES['SCHOOL']) {
            return [
                $schedule['date'] ? date("d.m.Y", strtotime($schedule['date'])) : '',
                $schedule['schedule_setting_item'] ? $schedule['schedule_setting_item'][0]['time_start']->format('H:i') . ' - ' . $schedule['schedule_setting_item'][0]['time_end']->format('H:i') : '',
                $schedule['schedule_setting_id'] ? $schedule['schedule_setting']['name'] : '',
                $schedule['building_classroom_id'] ? $schedule['building_classroom']['name'] : '',
                $schedule['subject_id'] ? $schedule['subject']['name'] : '',
                $schedule['group_id'] ? $schedule['group']['name'] . ' ' . $schedule['group']['letter'] : '',
                $schedule['teacher_id'] ? $schedule['teacher']['full_name'] : '',
            ];
        }

        return [
            $schedule['date'] ? date("d.m.Y", strtotime($schedule['date'])) : '',
            $schedule['schedule_setting_item'] ? $schedule['schedule_setting_item'][0]['time_start']->format('H:i') . ' - ' . $schedule['schedule_setting_item'][0]['time_end']->format('H:i') : '',
            $schedule['schedule_setting_id'] ? $schedule['schedule_setting']['name'] : '',
            $schedule['building_id'] ? $schedule['building']['name'] : '',
            $schedule['building_classroom_id'] ? $schedule['building_classroom']['name'] : '',
            $schedule['department_id'] ? $schedule['department']['name'] : '',
            $schedule['department'] && $schedule['department']['faculty'] ? $schedule['department']['faculty']['name'] : '',
            $schedule['subject_id'] ? $schedule['subject']['name'] : '',
            $schedule['group_id'] ? $schedule['group']['name'] . ' ' . $schedule['group']['letter'] : '',
            $schedule['teacher_id'] ? $schedule['teacher']['full_name'] : '',
        ];
    }

    private function getSchedulesByDate(string $date, array $ids) {
        $schedulesResult = [];

        $schedulesCollection = collect($this->schedules)->whereIn('id', $ids);

        foreach ($schedulesCollection as $schedule) {
            $schedule['date'] = $date;
            $schedulesResult[] = $this->getscheduleData($schedule);
        }

        return $schedulesResult;
    }
}
