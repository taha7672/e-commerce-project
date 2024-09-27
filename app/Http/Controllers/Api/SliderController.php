<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function index()
    {
        $user = Auth::guard('sanctum')->user();
        $sliders = Slider::where('is_deleted',0)->get();
        if ($sliders->isNotEmpty()) {
            // Transform the collection to add full URLs for the images
            $sliders->transform(function ($slider) {
                $slider->image = url($slider->image); // Full URL for main image
                $slider->small_image = url($slider->small_image); // Full URL for small image
                $slider->medium_image = url($slider->medium_image); // Full URL for medium image
                return $slider;
            });

            // Return a successful response with the transformed sliders
            return $this->successResponse($sliders, 'Sliders fetched successfully.');
        } else {
            // Return a response indicating no records found
            return $this->successResponse([], 'No sliders found.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Post comment on Tickets.
     */
    
    public function postComment(Request $request)
    {
    }

}
