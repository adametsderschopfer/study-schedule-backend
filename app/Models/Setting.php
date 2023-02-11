<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SettingItem;

class Setting extends Model
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

    public function settingItems()
    {
        return $this->hasMany(SettingItem::class);
    }

    public function giveSettingItems(array $settingItems): void
    {
        $i = 0;

        foreach ($settingItems as $item) {
            $item['setting_id'] = $this->id;
            $item['offset'] = $i;

            SettingItem::create($item);

            $i++;
        }
    }

    public function deleteSettingItems(): void
    {
        $this->settingItems()->delete();
    }
}
