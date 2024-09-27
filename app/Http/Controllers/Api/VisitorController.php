<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VisitorTrack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    public function addVisitor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'browser' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        try {
            $visitor = new VisitorTrack();
            $visitor->ip = $request->ip();
            $visitor->browser = $request->browser;
            $visitor->latitude = $request->latitude;
            $visitor->longitude = $request->longitude;
            $visitor->save();

            if ($visitor) {
                return $this->successResponse($visitor, 'Visitor added successfully');
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to add visitor'
                ], 500);
            }
        } catch (\Throwable $th) {
            return $this->serverException($th);
        }
    }
}
