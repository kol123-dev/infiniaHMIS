<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestimonialRequest;
use App\Models\Testimonial;
use App\Repositories\TestimonialRepository;
use Exception;

class TestimonialController extends AppBaseController
{
    /**
     * @var testimonialRepository
     */
    private $testimonialRepository;

    public function __construct(TestimonialRepository $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }

    public function index()
    {
        return view('testimonials.index');
    }

    public function store(TestimonialRequest $request)
    {
        try {
            $input = $request->all();
            $this->testimonialRepository->store($input);

            return $this->sendSuccess(__('messages.testimonials').' '.__('messages.common.saved_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function edit(Testimonial $testimonial)
    {
        return $this->sendResponse($testimonial, 'Testimonial retrieved successfully.');
    }

    public function update(Testimonial $testimonial, TestimonialRequest $request)
    {
        try {
            $this->testimonialRepository->updateTestimonial($request->all(), $testimonial->id);

            return $this->sendSuccess(__('messages.testimonials').' '.__('messages.common.updated_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function show(Testimonial $testimonial)
    {
        return $this->sendResponse($testimonial, 'Testimonial retrieved successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        try {
            $this->testimonialRepository->deleteTestimonial($testimonial);

            return $this->sendSuccess(__('messages.testimonials').' '.__('messages.common.deleted_successfully'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
