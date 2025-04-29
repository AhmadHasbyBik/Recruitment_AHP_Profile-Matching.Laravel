<?php
// app/Models/CriteriaStatus.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CriteriaStatus extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function criterias(): HasMany
    {
        return $this->hasMany(Criteria::class);
    }

    public static function getDropdownOptions()
    {
        return self::all()->pluck('name', 'id')->toArray();
    }
}