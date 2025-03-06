<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Carbon;

/**
 * Class NoticeBoard
 *
 * @version February 18, 2020, 4:23 am UTC
 *
 * @property string $title
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeBoard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeBoard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeBoard query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeBoard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeBoard whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeBoard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeBoard whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\NoticeBoard whereUpdatedAt($value)
 *
 * @mixin Model
 *
 * @property int $id
 * @property int $is_default
 *
 * @method static \Illuminate\Database\Eloquent\Builder|NoticeBoard whereIsDefault($value)
 */
class NoticeBoard extends Model
{
    public $table = 'notice_boards';

    public $fillable = [
        'title',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
    ];

    public static $rules = [
        'title' => 'required|string',
    ];

    public function prepareNoticeboardData()
    {
        return [
            'id' => $this->id ?? __('messages.common.n/a'),
            'title' => $this->title ?? __('messages.common.n/a'),
            'description' => $this->description ?? __('messages.common.n/a'),
            'date' => isset($this->created_at) ? Carbon::parse($this->created_at)->format('d M, Y') : __('messages.common.n/a'),
            'time' => isset($this->created_at) ? \Carbon\Carbon::parse($this->created_at)->isoFormat('LT') : __('messages.common.n/a'),
        ];
    }
}
