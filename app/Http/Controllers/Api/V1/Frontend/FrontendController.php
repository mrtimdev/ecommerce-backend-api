<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Menu;
use App\Models\Brand;
use App\Models\Model;
use App\Models\Story;
use App\Models\Video;
use App\Models\Service;
use App\Models\Community;
use App\Models\ContactUs;
use App\Models\Guarantee;
use Illuminate\Http\Request;
use App\Models\HomePageSlider;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\MenuResource;
use App\Http\Resources\Frontend\StoryResource;
use App\Http\Resources\Frontend\VideoResource;
use App\Http\Resources\Frontend\MenuCollection;
use App\Http\Resources\Frontend\ServiceResource;
use App\Http\Resources\Frontend\CommunityResource;
use App\Http\Resources\Frontend\ContactUsResource;
use App\Http\Resources\Frontend\GuaranteeResource;
use App\Http\Resources\Frontend\BrandFilterResource;
use App\Http\Resources\Frontend\CommunityCollection;
use App\Http\Resources\Frontend\ModelFilterResource;
use App\Http\Resources\Frontend\HomePage\SliderCollection;

class FrontendController extends Controller
{

    public function getMenus(Request $request)
    {
        $menu = Menu::all();
        return new MenuCollection($menu);
    }

    public function getHomePageSliders(Request $request)
    {
        $sortField = $request->input('sort_field', 'id');
        $sortOrder = $request->input('sort_order', 'asc');

        if (!in_array($sortField, ['id', 'title', 'is_active'])) {
            return response()->json(['error' => 'Invalid sort field, available fields: [id, title, is_active]'], 400);
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return response()->json(['error' => 'Invalid sort order'], 400);
        }
        $isActive = $request->input('is_active');
        $query = HomePageSlider::query();

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        $sliders = $query->orderBy($sortField, $sortOrder)->get();

        return new SliderCollection($sliders);
    }

    public function getContactUs(Request $request)
    {
        $contactus = ContactUs::firstOrFail();
        return new ContactUsResource($contactus);
    }

    public function getVideos(Request $request)
    {
        $video = Video::all();
        return VideoResource::collection($video);
    }
    public function getVideoById(Request $request, $id)
    {
        $video = Video::where('id', $id)->first();
        if($video) {
            return new VideoResource($video);
        }
        return response()->json([
            'data' => null,
            'message' => 'Item not found.',
        ], 404);
    }
    # stories
    public function getStories(Request $request)
    {
        $story = Story::all();
        return StoryResource::collection($story);
    }
    public function getStoryById(Request $request, $id)
    {
        $story = Story::where('id', $id)->first();
        if($story) {
            return new StoryResource($story);
        }
        return response()->json([
            'data' => null,
            'message' => 'Item not found.',
        ], 404);
    }

    #services
    public function getServices(Request $request)
    {
        $service = Service::first();
        return new ServiceResource($service);
    }
    #community
    public function getCommunities(Request $request)
    {
        $community = Community::first();
        return new CommunityResource($community);
    }

    #guaraMenus
    public function getGuarantees(Request $request)
    {
        $guarantee = Guarantee::first();
        return new GuaranteeResource($guarantee);
    }

    # menu filter 
    public function getMenuFilters(Request $request)
    {
        $brands = Brand::withCount('cars')->get();
        $models = Model::withCount('cars')->get();

        return response()->json([
            'data' => [
                'brands' => BrandFilterResource::collection($brands),
                'models' => ModelFilterResource::collection($models),
            ]
            
        ], 200);
    }
}
