<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use App\Models\Reunion;
use App\Models\Settings;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Intervention\Image\ImageManagerStatic as Image;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $distribution_list = FamilyMember::orderby('lastname', 'asc')->orderby('address', 'asc')->get();
        $duplicates_check = FamilyMember::checkDuplicates();
        $duplicates = $duplicates_check->isNotEmpty() ? $duplicates_check : null;
        $reunions = Reunion::orderby('reunion_year', 'desc')->get();
        $states = State::all();
        $carbonDate = Carbon::now()->subYear();

        return response()->view('admin.settings.index', compact('distribution_list', 'duplicates', 'reunions', 'states', 'carbonDate'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Settings $settings
     * @return Response
     */
    public function edit(Settings $settings)
    {
        $settings = Settings::find(1);

        return response()->view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $counter = 0;

        if($request->hasFile('photo')) {
            foreach($request->file('photo') as $newImage) {
                // Count how many images already saved for every iteration
                $carouselCount = CarouselImage::all()->count();

                // Check to see if upload is an image
                if($newImage->guessExtension() == 'jpeg' || $newImage->guessExtension() == 'png' || $newImage->guessExtension() == 'gif' || $newImage->guessExtension() == 'webp' || $newImage->guessExtension() == 'jpg') {

                    $addImage = new CarouselImage();
                    // Check to see if images is too large
                    if($newImage->getError() == 1) {
                        $fileName = $request->file('photo')[0]->getClientOriginalName();
                        $error .= "<li class='errorItem'>The file " . $fileName . " is too large and could not be uploaded</li>";
                    } elseif($newImage->getError() == 0) {
                        // Check to see if images is about 25MB
                        // If it is then resize it
                        if($newImage->getClientSize() < 25000000) {
                            $image = Image::make($newImage->getRealPath())->orientate();
                            $path = $newImage->store('public/images');

                            // prevent possible upsizing
                            // Create a larger version of the image
                            // and save to large image folder
                            $image->resize(1600, null, function ($constraint) {
                                $constraint->aspectRatio();
                                // $constraint->upsize();
                            });

                            if($image->save(storage_path('app/'. $path))) {}

                            $addImage->path = str_ireplace('public', 'storage', $path);
                            $addImage->height = $image->height();
                            $addImage->width = $image->width();

                            if($carouselCount < 10) {

                                if($addImage->save()) {
                                    $counter++;
                                }

                            }

                        } else {
                            // Resize the image before storing. Will need to hash the filename first
                            $path = $newImage->store('public/images');
                            $image = Image::make($newImage)->orientate()->resize(1500, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });

                            $image->save(storage_path('app/'. $path));
                            $addImage->property_id = $showSeason->id;

                            if($carouselCount < 10) {

                                if($addImage->save()) {
                                    $counter++;
                                }

                            }
                        }
                    } else {
                        $error .= "<li class='errorItem'>The file " . $fileName . " may be corrupt and could not be uploaded</li>";
                    }
                } else {
                    $error .= "<li class='errorItem'>The file " . $fileName . " may be corrupt and could not be uploaded</li>";
                }
            }

            return redirect()->back()->with('status', 'Images added successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
