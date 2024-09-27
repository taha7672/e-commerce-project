<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Slider;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class sliderController extends Controller
{

     public function __construct()
    {
        $this->middleware('permission:slider,admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $slider = Slider::where('is_deleted',0)->get();
        return view('admin.slider.index',compact('slider'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
         return view('admin.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'sub_title' => 'required',
            'title' => 'required',
            'title_color' => 'required',
            'button_link' => 'required',
            'button_text' => 'required',
            'image'=> "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ];

        $validatedData =    $request->validate($rules);

        $this->createDir();

           if ($request->hasFile('image')) {
                $uploadedFile = $validatedData['image'];
                $name = time() . '_' . $uploadedFile->getClientOriginalName();

                $manager = new ImageManager(new Driver());
                $image = $manager->read(  $uploadedFile );
                $largeImage =  $image->scale(width: config('business.productImgSize.slider_img_size.large_img_width'), height:config('business.productImgSize.slider_img_size.large_img_height'));
                 $largeImage->save("uploads/slider/large_img/$name");
                  $validatedData['image'] = "uploads/slider/large_img/$name";

                 $mediumImage =  $image->scale(width: config('business.productImgSize.slider_img_size.medium_img_width'), height:config('business.productImgSize.slider_img_size.medium_img_height'));
                 $mediumImage->save("uploads/slider/medium_img/$name");
                  $validatedData['medium_image'] = "uploads/slider/medium_img/$name";

                 $smallImage =  $image->scale(width: config('business.productImgSize.slider_img_size.small_img_width'), height:config('business.productImgSize.slider_img_size.small_img_height'));
                 $smallImage->save("uploads/slider/small_img/$name");
                 $validatedData['small_image'] = "uploads/slider/small_img/$name";


            }
             $slider = Slider::create([
                'sub_title' => $validatedData['sub_title'],
                'title' => $validatedData['title'],
                'title_color' => $validatedData['title_color'],
                'link' => $validatedData['button_link'],
                'button_text' => $validatedData['button_text'],
                'image' => $validatedData['image'] ?? null,
                'small_image' =>  $validatedData['small_image'] ?? null,
                'medium_image' =>  $validatedData['medium_image'] ?? null,
                'created_at' => date('y-m-d H:i:s')
            ]);
            if($slider){
                return redirect()->route('admin.slider.index')->with('success',  __('messages.slider_created'));
            }else{
                 return redirect()->route('admin.slider.index')->with('error', 'Something went wrong');
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id){

        if( empty($id)){
                return redirect()->route('admin.slider.index');
        }
         $slider = Slider::where(['is_deleted'=>0,'id'=>$id])->first();
         if($slider){
             return view('admin.slider.edit',compact('slider'));
         }else{
             return redirect()->route('admin.slider.index')->with('error', __('messages.something_went_wrong'));
         }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $rules = [
            'sub_title' => 'required',
            'title' => 'required',
            'title_color' => 'required',
            'button_link' => 'required',
            'button_text' => 'required',
            'image' => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ];
        $validatedData =    $request->validate($rules);
         $slider = Slider::findOrFail($id);

         $this->createDir();

            if ($request->hasFile('image')) {
                if ($slider->image && file_exists(public_path($slider->image))) {
                    unlink(public_path($slider->image));
                }
                if ($slider->small_image && file_exists(public_path($slider->small_image))) {
                    unlink(public_path($slider->small_image));
                }
                 if ($slider->medium_image && file_exists(public_path($slider->medium_image))) {
                    unlink(public_path($slider->medium_image));
                }
                $uploadedFile = $validatedData['image'];
                $name = time() . '_' . $uploadedFile->getClientOriginalName();
                 $manager = new ImageManager(new Driver());
                $image = $manager->read(  $uploadedFile );
                 $largeImage =  $image->scale(width: config('business.productImgSize.slider_img_size.large_img_width'), height:config('business.productImgSize.slider_img_size.large_img_height'));
                 $largeImage->save("uploads/slider/large_img/$name");
                  $validatedData['image'] = "uploads/slider/large_img/$name";

                 $mediumImage =  $image->scale(width: config('business.productImgSize.slider_img_size.medium_img_width'), height:config('business.productImgSize.slider_img_size.medium_img_height'));
                 $mediumImage->save("uploads/slider/medium_img/$name");
                  $validatedData['medium_image'] = "uploads/slider/medium_img/$name";

                 $smallImage =  $image->scale(width: config('business.productImgSize.slider_img_size.small_img_width'), height:config('business.productImgSize.slider_img_size.small_img_height'));
                 $smallImage->save("uploads/slider/small_img/$name");
                 $validatedData['small_image'] = "uploads/slider/small_img/$name";
            }
             $slider->update([
                'sub_title' => $validatedData['sub_title'],
                'title' => $validatedData['title'],
                'title_color' => $validatedData['title_color'],
                'link' => $validatedData['button_link'],
                'button_text' => $validatedData['button_text'],
                'image' => $validatedData['image'] ?? $slider->image,
                'medium_image'=> $validatedData['medium_image'] ??  $slider->medium_image,
                'small_image'=> $validatedData['small_image'] ??  $slider->small_image,
            ]);
            return redirect()->route('admin.slider.index')->with('success', __('messages.slider_updated'));


    }

    /** php
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $Slider=Slider::find($id);
        $Slider->is_deleted=1;
        $Slider->update();
        return redirect()->back()->with('success', __('messages.slider_deleted'));
        //
    }

    public function createDir() {
        $dir = [
            "large_img",
            "medium_img",
            "small_img",
        ];

        foreach ($dir as $d) {
            $path = public_path("uploads/slider/{$d}");
            if ( !file_exists($path) ) {
                mkdir($path, 0777, true);
            }
        }
    }

}
