<?php

namespace App\Repositories;

use App\Models\Testimonial;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class TestimonialRepository
 */
class TestimonialRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'description',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Testimonial::class;
    }

    public function store($input)
    {
        try {
            $testimonial = $this->create($input);
            
            if (! empty($input['profile'])) {
                $fileExtension = getFileName('Testimonial', $input['profile']);
                $testimonial->addMedia($input['profile'])->usingFileName($fileExtension)->toMediaCollection(Testimonial::PATH,
                    config('app.media_disc'));
            }

            return true;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function updateTestimonial($input, $testimonialId)
    {
        try {
            $testimonial = $this->update($input, $testimonialId);
            if (! empty($input['profile'])) {
                $testimonial->clearMediaCollection(Testimonial::PATH);
                $fileExtension = getFileName('Testimonial', $input['profile']);
                $testimonial->addMedia($input['profile'])->usingFileName($fileExtension)->toMediaCollection(Testimonial::PATH,
                    config('app.media_disc'));
            }
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function deleteTestimonial($testimonial)
    {
        try {
            $testimonial->clearMediaCollection(Testimonial::PATH);
            $this->delete($testimonial->id);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
