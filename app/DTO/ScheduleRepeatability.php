<?php

namespace App\DTO;

class ScheduleRepeatability
{
    public int $id;
    public string $date;

    public function __construct(
        $id,
        $date
    )
    {
        $this->id = $id;
        $this->date = $date;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date
        ];
    }

    public function setData(array $data)
    {
        $this->id = $data['id'];
        $this->date = $data['date'];
    }

    public function getData(): self
    {
        return $this;
    }
}