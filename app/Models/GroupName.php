<?php

namespace App\Models;

use App\Enums\CustomPivotTableNames;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\GroupName
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Group[] $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AcademicPeriod[] $periods
 * @property-read int|null $periods_count
 * @method static \Illuminate\Database\Eloquent\Builder|GroupName newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupName newQuery()
 * @method static \Illuminate\Database\Query\Builder|GroupName onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupName query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupName whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupName whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupName whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupName whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupName whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|GroupName withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GroupName withoutTrashed()
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class GroupName extends Model
{
    use HasFactory, SoftDeletes;

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function periods():HasManyThrough
    {
        return $this->hasManyThrough(AcademicPeriod::class,Group::class);
    }
}