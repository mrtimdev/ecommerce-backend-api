<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Menu;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Model;
use App\Models\Story;
use App\Models\Video;
use App\Models\Country;
use App\Models\Service;
use App\Models\Category;
use App\Models\FuelType;
use App\Models\Location;
use App\Models\DriveType;
use App\Models\Passenger;
use App\Models\Steering;
use App\Models\Community;
use App\Models\ContactUs;
use App\Models\Guarantee;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use App\Models\CommunityItem;
use App\Models\GuaranteeItem;
use App\Models\HomePageSlider;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FrontendController extends Controller
{
    public function sliderIndex()
    {
        $sliders = HomePageSlider::all();

        return Inertia::render('Admin/Frontend/Pages/Sliders/Index', [
            'sliders' => $sliders,
        ]);
    }
    public function sliderStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'required|image',
            'is_active' => 'required|boolean'
        ]);

        // Handle file upload
        $imagePath = $request->file('image_path')->store('homepagesliders', 'public');

        HomePageSlider::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('frontend.page.sliders.index');
    }

    public function sliderUpdate(Request $request, HomePageSlider $slider)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image', 
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('image_path')) {
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }
            $imagePath = $request->file('image_path')->store('homepagesliders', 'public');
        } else {
            $imagePath = $slider->image_path;
        }

        $slider->update([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('frontend.page.sliders.index')
            ->with('success', 'Slider updated successfully.');
    }

    public function deleteSelectedSliders(Request $request)
    {
        $ids = $request->input('ids');
        $sliders = HomePageSlider::whereIn('id', $ids)->get();
        // return response()->json(['sliders' => $sliders], 404);
        foreach ($sliders as $slider) {
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }
        }
        HomePageSlider::whereIn('id', $ids)->delete();
        return redirect()->route('frontend.page.sliders.index');
        
    }

    public function getSliders(Request $request)
    {
        if ($request->ajax()) {
            $data = HomePageSlider::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function contactUsIndex()
    {
        $contactus = ContactUs::firstOrFail();
        return Inertia::render('Admin/Frontend/Pages/ContactUs/Index', [
            'contactus' => $contactus,
        ]);
    }

    public function storeContactUs(Request $request)
    {
        $request->validate([
            'phone_number'      => 'nullable|string|max:15',
            'telegram'          => 'nullable|string|max:50',
            'telegram_channel'  => 'nullable|url',
            'facebook_page'     => 'nullable|url',
            'youtube'           => 'nullable|url',
            'tiktok'            => 'nullable|url',
            'address'            => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $contactus = ContactUs::first();
        if ($request->hasFile('image_path')) {
            Storage::disk('public')->delete($contactus->image_path);     
            $contactus->image_path = $request->file('image_path')->store('contactus', 'public');
        }
        
        $contactus->update([
            'phone_number'      => $request->phone_number,
            'telegram'          => $request->telegram,
            'telegram_channel'  => $request->telegram_channel,
            'facebook_page'     => $request->facebook_page,
            'youtube'           => $request->youtube,
            'tiktok'            => $request->tiktok,
            'address'            => $request->address,
        ]);
        return redirect()->route('frontend.page.contactus.index');
    }

    # videos stocks
    public function videoIndex()
    {
        $videos = Video::all();

        return Inertia::render('Admin/Frontend/Pages/Videos/Index', [
            'videos' => $videos,
        ]);
    }
    public function videoStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_link' => 'nullable|string|url',
            'image_path' => 'nullable|image',
            'is_active' => 'required|boolean'
        ]);

        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('videos', 'public');
        }

        Video::create([
            'title' => $request->title,
            'youtube_link' => $request->youtube_link,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('frontend.page.videos.index');
    }

    public function videoUpdate(Request $request, Video $video)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_link' => 'nullable|string|url',
            'image_path' => 'nullable|image', 
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('image_path')) {
            if ($video->image_path) {
                Storage::disk('public')->delete($video->image_path);
            }
            $imagePath = $request->file('image_path')->store('videos', 'public');
        } else {
            $imagePath = $video->image_path;
        }

        $video->update([
            'title' => $request->title,
            'youtube_link' => $request->youtube_link,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('frontend.page.videos.index')
            ->with('success', 'Video updated successfully.');
    }

    public function deleteSelectedVideos(Request $request)
    {
        $ids = $request->input('ids');
        $videos = Video::whereIn('id', $ids)->get();
        foreach ($videos as $video) {
            if ($video->image_path) {
                Storage::disk('public')->delete($video->image_path);
            }
        }
        Video::whereIn('id', $ids)->delete();
        return redirect()->route('frontend.page.videos.index');
        
    }

    public function getVideos(Request $request)
    {
        if ($request->ajax()) {
            $data = Video::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    # stories
    
    public function storyIndex()
    {
        $stories = Story::all();

        return Inertia::render('Admin/Frontend/Pages/Stories/Index', [
            'stories' => $stories,
        ]);
    }
    public function storyStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'country_id' => 'nullable',
            'youtube_link' => 'nullable|string|url',
            'image_path' => 'required|image',
            'is_active' => 'required|boolean'
        ]);

        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('stories', 'public');
        }

        Story::create([
            'title' => $request->title,
            'youtube_link' => $request->youtube_link,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
            'description' => $request->description,
            'country_id' => $request->country_id,
        ]);

        return redirect()->route('frontend.page.stories.index');
    }

    public function storyUpdate(Request $request, Story $story)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'country_id' => 'nullable',
            'youtube_link' => 'nullable|string|url',
            'image_path' => 'required|image',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image_path')) {
            if ($story->image_path) {
                Storage::disk('public')->delete($story->image_path);
            }
            $imagePath = $request->file('image_path')->store('stories', 'public');
        } else {
            $imagePath = $story->image_path;
        }

        $story->update([
            'title' => $request->title,
            'youtube_link' => $request->youtube_link,
            'image_path' => $imagePath,
            'is_active' => $request->is_active,
            'description' => $request->description,
            'country_id' => $request->country_id,
        ]);

        return redirect()->route('frontend.page.stories.index')
            ->with('success', 'Story updated successfully.');
    }

    public function deleteSelectedStories(Request $request)
    {
        $ids = $request->input('ids');
        $stories = Story::whereIn('id', $ids)->get();
        foreach ($stories as $story) {
            if ($story->image_path) {
                Storage::disk('public')->delete($story->image_path);
            }
        }
        Story::whereIn('id', $ids)->delete();
        return redirect()->route('frontend.page.stories.index');
        
    }

    public function getStories(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Story::with(['country'])->select([
                'id',
                'title',
                'description',
                'youtube_link',
                'facebook_link',
                'image_path',
                'is_active',
                'view',
                'like',
                'country_id',
            ]))
            ->addIndexColumn()
            ->make(true);
        }
    }


    # Service
    
    public function serviceIndex()
    {
        $service = Service::first();

        return Inertia::render('Admin/Frontend/Pages/Services/Index', [
            'service' => $service,
        ]);
    }
    public function serviceStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'image_path' => 'nullable|image',
        ]);

        $service = Service::first();
        if ($request->hasFile('image_path')) {
            if($service?->image_path) {
                Storage::disk('public')->delete($service->image_path);  
            }
              
            $service->image_path = $request->file('image_path')->store('services', 'public');
        }

        $service->update(
            [
                'title' => $request->title,
            ]
        );

        return redirect()->route('frontend.page.services.index');
    }

    
    public function serviceItemStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:service_items,title',
            'description' => 'nullable|string',
        ]);

        ServiceItem::create(
            [
                'title' => $request->title,
                'description' => $request->description,
                'service_id' => $request->service_id,
            ]
        );

        return redirect()->route('frontend.page.services.index');
    }
    public function serviceItemUpdate(Request $request, ServiceItem $serviceItem)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:191',
                Rule::unique('service_items', 'title')->ignore($serviceItem->id), 
            ],
            'description' => 'nullable|string',
        ]);

        $serviceItem->update(
            [
                'title' => $request->title,
                'description' => $request->description,
            ]
        );

        return redirect()->route('frontend.page.services.index');
    }

    public function getListServiceItems(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(ServiceItem::select([
                'id',
                'title',
                'description',
            ]))
            ->addIndexColumn()
            ->make(true);
        }
    }
    public function deleteSelectedServiceItems(Request $request)
    {
        $ids = $request->input('ids');
        ServiceItem::whereIn('id', $ids)->delete();
        return redirect()->route('frontend.page.services.index');
        
    }




    # guarantees
        
    public function guaranteeIndex()
    {
        $guarantee = Guarantee::first();

        return Inertia::render('Admin/Frontend/Pages/Guarantees/Index', [
            'guarantee' => $guarantee,
        ]);
    }
    public function guaranteeStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'image_path' => 'nullable|image',
        ]);

        $guarantee = Guarantee::first();
        if ($request->hasFile('image_path')) {
            if($guarantee?->image_path) {
                Storage::disk('public')->delete($guarantee->image_path);  
            }
            
            $guarantee->image_path = $request->file('image_path')->store('guarantees', 'public');
        }

        $guarantee->update(
            [
                'title' => $request->title,
            ]
        );

        return redirect()->route('frontend.page.guarantees.index');
    }


    public function guaranteeItemStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:guarantee_items,title',
            'description' => 'nullable|string',
        ]);

        GuaranteeItem::create(
            [
                'title' => $request->title,
                'description' => $request->description,
                'guarantee_id' => $request->guarantee_id,
            ]
        );

        return redirect()->route('frontend.page.guarantees.index');
    }
    public function guaranteeItemUpdate(Request $request, GuaranteeItem $guaranteeItem)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:191',
                Rule::unique('guarantee_items', 'title')->ignore($guaranteeItem->id), 
            ],
            'description' => 'nullable|string',
        ]);

        $guaranteeItem->update(
            [
                'title' => $request->title,
                'description' => $request->description,
            ]
        );

        return redirect()->route('frontend.page.guarantees.index');
    }

    public function getListGuaranteeItems(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(GuaranteeItem::select([
                'id',
                'title',
                'description',
            ]))
            ->addIndexColumn()
            ->make(true);
        }
    }
    public function deleteSelectedGuaranteeItems(Request $request)
    {
        $ids = $request->input('ids');
        GuaranteeItem::whereIn('id', $ids)->delete();
        return redirect()->route('frontend.page.guarantees.index');
        
    }


    # community
        
    public function communityIndex()
    {
        $community = Community::first();

        return Inertia::render('Admin/Frontend/Pages/Communities/Index', [
            'community' => $community,
        ]);
    }
    public function communityStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'button_label' => 'nullable|string',
            'button_url' => 'nullable|url',
            'description' => 'nullable|string',
        ]);

        $community = Community::first();
        $community->update($validated);

        return redirect()->route('frontend.page.communities.index');
    }


    public function communityItemStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:community_items,title',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'link' => 'nullable|url',
        ]);
        $image_path = null;
        if ($request->hasFile('image_path')) {
            $image_path = $request->file('image_path')->store('communities', 'public');
        }

        CommunityItem::create(
            [
                'title' => $request->title,
                'link' => $request->link,
                'description' => $request->description,
                'image_path' => $image_path,
                'community_id' => $request->community_id,
            ]
        );

        return redirect()->route('frontend.page.communities.index');
    }
    public function communityItemUpdate(Request $request, CommunityItem $communityItem)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:191',
                Rule::unique('community_items', 'title')->ignore($communityItem->id), 
            ],
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
        ]);

        if ($request->hasFile('image_path')) {
            if($communityItem?->image_path) {
                Storage::disk('public')->delete($communityItem->image_path);  
            }
            $communityItem->image_path = $request->file('image_path')->store('communities', 'public');
        }

        $communityItem->update(
            [
                'title' => $request->title,
                'link' => $request->link,
                'description' => $request->description,
                'community_id' => $request->community_id,
            ]
        );

        return redirect()->route('frontend.page.communities.index');
    }

    public function getListCommunityItems(Request $request)
    {
        if ($request->ajax()) {
            $data = CommunityItem::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function deleteSelectedCommunityItems(Request $request)
    {
        $ids = $request->input('ids');
        $communityItem = CommunityItem::whereIn('id', $ids)->get();
        if ($communityItem->isEmpty()) {
            return redirect()->route('frontend.page.communities.index', ['message' => 'No communities found with the provided IDs']);
        }
        foreach ($communityItem as $communityItem) {
            if ($communityItem->image_path) {
                Storage::disk('public')->delete($communityItem->image_path);
            }
        }
        CommunityItem::whereIn('id', $ids)->delete();
        return redirect()->route('frontend.page.communities.index');
        
    }


    #Menu
    public function menuIndex()
    {
        return Inertia::render('Admin/Frontend/Pages/Menus/Index', [
            'brands' => Brand::all(),
            'models' => Model::all(),
            'categories' => Category::all(),
            'fuel_types' => FuelType::all(),
            'steerings' => Steering::all(),
            'locations' => Location::all(),
            'drive_types' => DriveType::all(),
            'passengers' => Passenger::all(),
        ]);
    }
    public function menustore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|regex:/^[^\s]+$/|max:100|unique:menus,code',
            'name' => 'required|string|max:100|unique:menus,name',
            'brands' => 'nullable|array',
            'brands.*.id' => 'nullable|integer|exists:brands,id',
            'categories' => 'nullable|array',
            'categories.*.id' => 'nullable|integer|exists:categories,id',
            'models' => 'nullable|array',
            'models.*.id' => 'nullable|integer|exists:models,id',
            'fuel_types' => 'nullable|array',
            'fuel_types.*.id' => 'nullable|integer|exists:fuel_types,id',
            'steerings' => 'nullable|array',
            'steerings.*.id' => 'nullable|integer|exists:steerings,id',
            'locations' => 'nullable|array',
            'locations.*.id' => 'nullable|integer|exists:countries,id',
            'drive_types' => 'nullable|array',
            'drive_types.*.id' => 'nullable|integer|exists:drive_types,id',
            'passengers' => 'nullable|array',
            'passengers.*.id' => 'nullable|integer|exists:passengers,id',
        ]);

        // Create the menu
        $menu = Menu::create($request->only('code', 'name'));

        // Attach related models
        if ($request->has('brands')) {
            $menu->brands()->attach(collect($request->input('brands'))->pluck('id'));
        }
        if ($request->has('models')) {
            $menu->models()->attach(collect($request->input('models'))->pluck('id'));
        }
        if ($request->has('categories')) {
            $menu->categories()->attach(collect($request->input('categories'))->pluck('id'));
        }
        if ($request->has('fuel_types')) {
            $menu->fuel_types()->attach(collect($request->input('fuel_types'))->pluck('id'));
        }
        if ($request->has('steerings')) {
            $menu->steerings()->attach(collect($request->input('steerings'))->pluck('id'));
        }
        if ($request->has('locations')) {
            $menu->locations()->attach(collect($request->input('locations'))->pluck('id'));
        }
        if ($request->has('drive_types')) {
            $menu->drive_types()->attach(collect($request->input('drive_types'))->pluck('id'));
        }
        if ($request->has('passengers')) {
            $menu->passengers()->attach(collect($request->input('passengers'))->pluck('id'));
        }

        return redirect()->route('frontend.page.menus.index');
    }

    public function menuUpdate(Request $request, Menu $menu)
    {
        // Validate the incoming request
        $request->validate([
            'code' => 'required|string|regex:/^[^\s]+$/|max:100|unique:menus,code,' . $menu->id,
            'name' => 'required|string|max:100|unique:menus,name,' . $menu->id,
            'brands' => 'nullable|array',
            'brands.*.id' => 'nullable|integer|exists:brands,id',
            'categories' => 'nullable|array',
            'categories.*.id' => 'nullable|integer|exists:categories,id',
            'models' => 'nullable|array',
            'models.*.id' => 'nullable|integer|exists:models,id',
            'fuel_types' => 'nullable|array',
            'fuel_types.*.id' => 'nullable|integer|exists:fuel_types,id',
            'steerings' => 'nullable|array',
            'steerings.*.id' => 'nullable|integer|exists:steerings,id',
            'locations' => 'nullable|array',
            'locations.*.id' => 'nullable|integer|exists:countries,id',
            'drive_types' => 'nullable|array',
            'drive_types.*.id' => 'nullable|integer|exists:drive_types,id',
            'passengers' => 'nullable|array',
            'passengers.*.id' => 'nullable|integer|exists:passengers,id',
        ]);

        $menu->update($request->only('code', 'name'));

        // Sync related models (replace old relationships with the new ones)
        $menu->brands()->sync(collect($request->input('brands'))->pluck('id'));
        $menu->models()->sync(collect($request->input('models'))->pluck('id'));
        $menu->categories()->sync(collect($request->input('categories'))->pluck('id'));
        $menu->fuel_types()->sync(collect($request->input('fuel_types'))->pluck('id'));
        $menu->steerings()->sync(collect($request->input('steerings'))->pluck('id'));
        $menu->locations()->sync(collect($request->input('locations'))->pluck('id'));
        $menu->drive_types()->sync(collect($request->input('drive_types'))->pluck('id'));
        $menu->passengers()->sync(collect($request->input('passengers'))->pluck('id'));

        // Redirect back to the menu list or desired route
        return redirect()->route('frontend.page.menus.index')
            ->with('success', 'Menu updated successfully.');
    }

    public function deleteSelectedMenus(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('frontend.page.menus.index')->with('error', 'No valid menu IDs provided');
        }
        $menus = Menu::whereIn('id', $ids)->get();
        foreach ($menus as $menu) {
            $menu->brands()->detach();
            $menu->models()->detach();
            $menu->categories()->detach();
            $menu->fuel_types()->detach();
            $menu->steerings()->detach();
            $menu->locations()->detach();
            $menu->drive_types()->detach();
            $menu->passengers()->detach();
        }
        Menu::whereIn('id', $ids)->delete();
        return redirect()->route('frontend.page.menus.index');
    }

    public function getMenus(Request $request)
    {
        if ($request->ajax()) {
            // Eager load the related models to avoid N+1 query issues
            $data = Menu::with(['brands', 'models', 'categories', 'fuel_types', 'steerings', 'drive_types', 'passengers', 'locations'])
                        ->orderBy('id', 'desc')
                        ->get();

            // Return the data as a DataTable response
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('brands_name', function ($row) {
                    return $row->brands->pluck('name')->implode(', ');
                })
                ->addColumn('models_name', function ($row) {
                    return $row->models->pluck('name')->implode(', ');
                })
                ->addColumn('categories_name', function ($row) {
                    return $row->categories->pluck('name')->implode(', ');
                })
                ->addColumn('fuel_types_name', function ($row) {
                    return $row->fuel_types->pluck('name')->implode(', ');
                })
                ->addColumn('steerings_name', function ($row) {
                    return $row->steerings->pluck('name')->implode(', ');
                })
                ->addColumn('locations_name', function ($row) {
                    return $row->locations->pluck('name')->implode(', ');
                })
                ->addColumn('drive_types_name', function ($row) {
                    return $row->drive_types->pluck('name')->implode(', ');
                })
                ->addColumn('passengers_name', function ($row) {
                    return $row->passengers->pluck('name')->implode(', ');
                })
                ->rawColumns(['brands', 'models', 'categories', 'fuel_types', 'steerings', 'locations'])
                ->make(true);
        }
    }



}
