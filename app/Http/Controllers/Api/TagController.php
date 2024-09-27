<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function tagList(Request $request)
    {
        try { 
            $tags = Tag::where('is_deleted', 0)->get();
    
            if ($tags->isNotEmpty()) {
                $tags->transform(function ($tag) {
                    $tag->image = url($tag->image);
                    return $tag;
                });
    
                return $this->successResponse($tags, 'Tag list fetched successfully');
            } else {
                return $this->successResponse([], 'No record found');
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the tags',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
