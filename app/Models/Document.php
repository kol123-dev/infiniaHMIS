<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Document
 *
 * @property int $id
 * @property string $title
 * @property int $document_type_id
 * @property int $patient_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|Media[] $media
 * @property-read int|null $media_count
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newQuery()
 * @method static Builder|Document onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Document query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereDocumentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUpdatedAt($value)
 * @method static Builder|Document withTrashed()
 * @method static Builder|Document withoutTrashed()
 *
 * @mixin Model
 *
 * @property-read \App\Models\DocumentType $documentType
 * @property-read mixed $document_url
 * @property-read \App\Models\Patient $patient
 * @property int $uploaded_by
 * @property string|null $notes
 * @property int $is_default
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUploadedBy($value)
 */
class Document extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $table = 'documents';

    public const PATH = 'documents';

    public $fillable = [
        'title',
        'document_type_id',
        'patient_id',
        'uploaded_by',
        'notes',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'document_type_id' => 'integer',
        'patient_id' => 'integer',
        'uploaded_by' => 'integer',
        //        'updated_at'       => 'timestamps',
        'notes' => 'string',
    ];

    public static $rules = [
        'title' => 'required|string',
        'document_type_id' => 'required|integer',
        'patient_id' => 'required|integer',
    ];

    protected $appends = ['document_url'];

    public function getDocumentUrlAttribute()
    {
        $media = $this->media->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return '';
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function prepareDocument()
    {
        return [
            'id' => $this->id,
            'title' => $this->title ?? __('messages.common.n/a'),
            'document_type_id' => $this->document_type_id ?? __('messages.common.n/a'),
            'patient_id' => $this->patient_id ?? __('messages.common.n/a'),
            'uploaded_by' => $this->uploaded_by ?? __('messages.common.n/a'),
            'notes' => $this->notes ?? __('messages.common.n/a'),
            'is_default' => $this->is_default ?? __('messages.common.n/a'),
            'document_url' => $this->document_url ?? __('messages.common.n/a'),
        ];
    }
}
