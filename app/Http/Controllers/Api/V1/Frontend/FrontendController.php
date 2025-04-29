<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Models\Menu;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Model;
use App\Models\Story;
use App\Models\Video;
use App\Models\Option;
use App\Models\HotMark;
use App\Models\Service;
use App\Models\TaxInfo;
use App\Models\Category;
use App\Models\FuelType;
use App\Models\Location;
use App\Models\Steering;
use App\Models\Community;
use App\Models\Condition;
use App\Models\ContactUs;
use App\Models\DriveType;
use App\Models\Guarantee;
use App\Models\Passenger;
use App\Models\TaxInfoItem;
use Illuminate\Http\Request;
use App\Models\HomePageSlider;
use App\Models\MenuCarGallery;
use App\Models\TransmissionType;
use App\Models\MenuCarGalleryItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\MenuResource;
use App\Http\Resources\Frontend\StoryResource;
use App\Http\Resources\Frontend\VideoResource;
use App\Http\Resources\Frontend\MenuCollection;
use App\Http\Resources\Frontend\ServiceResource;
use App\Http\Resources\Frontend\TaxInfoResource;
use App\Http\Resources\Frontend\CommunityResource;
use App\Http\Resources\Frontend\ContactUsResource;
use App\Http\Resources\Frontend\GuaranteeResource;
use App\Http\Resources\Frontend\BrandFilterResource;
use App\Http\Resources\Frontend\ColorFilterResource;
use App\Http\Resources\Frontend\CommunityCollection;
use App\Http\Resources\Frontend\ModelFilterResource;
use App\Http\Resources\Frontend\OptionFilterResource;
use App\Http\Resources\Frontend\TaxInfoItemsResource;
use App\Http\Resources\Frontend\HotMarkFilterResource;
use App\Http\Resources\Frontend\CategoryFilterResource;
use App\Http\Resources\Frontend\FuelTypeFilterResource;
use App\Http\Resources\Frontend\LocationFilterResource;
use App\Http\Resources\Frontend\MenuCarGalleryResource;
use App\Http\Resources\Frontend\SteeringFilterResource;
use App\Http\Resources\Frontend\TaxInfoDetailsResource;
use App\Http\Resources\Frontend\ConditionFilterResource;
use App\Http\Resources\Frontend\DriveTypeFilterResource;
use App\Http\Resources\Frontend\PassengerFilterResource;
use App\Http\Resources\Frontend\HomePage\SliderCollection;
use App\Http\Resources\Frontend\MenuCarGalleryItemsResource;
use App\Http\Resources\Frontend\TransmissionTypeFilterResource;

class FrontendController extends Controller
{

    public function getMenus(Request $request)
    {
        $menu = Menu::all();
        return new MenuCollection($menu);
    }

    public function getTaxInfo(Request $request) {
        $tax_info = TaxInfo::firstOrFail();
        return new TaxInfoResource($tax_info);
    }
    public function getTaxInfoItems(Request $request) {
        $data = TaxInfoItem::orderBy('id', 'desc')->get();
        return TaxInfoItemsResource::collection($data);
    }
    public function getTaxInfoItemByID(Request $request, TaxInfoItem $item) {
        return new TaxInfoItemsResource($item);
    }
    public function getCarGallery(Request $request) {
        $car_gallery = MenuCarGallery::firstOrFail();
        return new MenuCarGalleryResource($car_gallery);
    }

    public function getMenuCarGalleryItems(Request $request) {
        $perPage = $request->input('per_page', 10);
        $query = MenuCarGalleryItem::query();
        $data = $query->orderBy('id', 'desc')->paginate($perPage);
        return MenuCarGalleryItemsResource::collection($data);
    }
    public function getMenuCarGalleryItemByID(Request $request, MenuCarGalleryItem $item) {
        return new MenuCarGalleryItemsResource($item);
    }

    public function getTaxWithCarGallery(Request $request){
        $tax_info = TaxInfo::firstOrFail();
        $car_gallery = MenuCarGallery::firstOrFail();
        return response()->json([
            'data' => [
                $tax_info->label,
                $car_gallery->label,
            ]
        ]);
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

    public function getAgencyContact(Request $request)
    {
        $agency_contacts = DB::table('agency_contact')->get();
        $agency = [];
        if($agency_contacts) {
            foreach($agency_contacts as $agency_contact) {
                $agency[$agency_contact->type] = [
                    'title' => $agency_contact->title,
                    'name' => $agency_contact->name,
                    'phone' => $agency_contact->phone,
                    'telegram_link' => $agency_contact->telegram_link,
                    'facebook_link' => $agency_contact->facebook_link,
                    'whatapp_link' => $agency_contact->whatapp_link,
                    'avatar' => $agency_contact->avatar ? asset('storage/' . $agency_contact->avatar) : asset('assets/images/user/no-avatar.png')
                ];
            }
        }
        return response()->json([
            'data' => $agency,
        ]);
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
        $categories = Category::withCount('cars')->get();
        $conditions = Condition::withCount('cars')->get();
        $fuelTypes = FuelType::withCount('cars')->get();
        $transmissionTypes = TransmissionType::withCount('cars')->get();
        $driveTypes = DriveType::withCount('cars')->get();
        $steerings = Steering::withCount('cars')->get();
        $colors = Color::withCount('cars')->get();
        $passengers = Passenger::withCount('cars')->get();
        $locations = Location::withCount('cars')->get();
        $hot_marks = HotMark::withCount('cars')->get();
        $options = Option::withCount('cars')->get();
        $models_by_brand = [];
        foreach($models as $model){
            $models_by_brand[$model->brand->code][] = [
                'name' => $model->name,
                'code' => $model->code,
                'count' => $model->cars_count,
            ];
        }
        
        return response()->json([
            'data' => [
                'brands' => BrandFilterResource::collection($brands),
                'models' => ModelFilterResource::collection($models),
                'models_by_brand' => $models_by_brand,
                'categories' => CategoryFilterResource::collection($categories),
                'conditions' => ConditionFilterResource::collection($conditions),
                'fuelTypes' => FuelTypeFilterResource::collection($fuelTypes),
                'transmissionTypes' => TransmissionTypeFilterResource::collection($transmissionTypes),
                'driveTypes' => DriveTypeFilterResource::collection($driveTypes),
                'steerings' => SteeringFilterResource::collection($steerings),
                'colors' => ColorFilterResource::collection($colors),
                'passengers' => PassengerFilterResource::collection($passengers),
                'locations' => LocationFilterResource::collection($locations),
                'hot_marks' => HotMarkFilterResource::collection($hot_marks),
                'options' => OptionFilterResource::collection($options),
            ]
            
        ], 200);
    }
}
