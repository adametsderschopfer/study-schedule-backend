<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Timing;

class Mode extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id', 
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'account_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function timings()
    {
        return $this->hasMany(Timing::class);
    }

    public function giveTimings(array $timings): void
    {
        $i = 0;

        foreach ($timings as $timing) {
            $timing['mode_id'] = $this->id;
            $timing['offset'] = $i;

            Timing::create($timing);

            $i++;
        }
    }

    public function deleteTimings(): void
    {
        $this->timings()->delete();
    }
}
