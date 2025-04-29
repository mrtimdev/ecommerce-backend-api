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
use App\Models\TaxInfo;
use App\Models\Category;
use App\Models\FuelType;
use App\Models\Location;
use App\Models\Steering;
use App\Models\Community;
use App\Models\ContactUs;
use App\Models\DriveType;
use App\Models\Guarantee;
use App\Models\Passenger;
use App\Models\ServiceItem;
use App\Models\TaxInfoItem;
use Illuminate\Http\Request;
use App\Models\CommunityItem;
use App\Models\GuaranteeItem;
use App\Models\HomePageSlider;
use App\Models\MenuCarGallery;
use Illuminate\Validation\Rule;
use App\Models\MenuCarGalleryItem;
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
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_path_2' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
            'link' => 'nullable|string|url',
        ], [
            'image_path_2.required' => 'The image path field is required.'
        ]);

        // Handle file upload
        $imagePath = $request->file('image_path')->store('homepagesliders', 'public');
        $imagePath2 = $request->file('image_path_2')->store('homepagesliders', 'public');

        HomePageSlider::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'image_path_2' => $imagePath2,
            'link' => $request->link,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('frontend.page.sliders.index');
    }

    public function sliderUpdate(Request $request, HomePageSlider $slider)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_path_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
            'link' => 'nullable|string|url',
        ]);

        if ($request->hasFile('image_path')) {
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }
            $imagePath = $request->file('image_path')->store('homepagesliders', 'public');
        } else {
            $imagePath = $slider->image_path;
        }
        if ($request->hasFile('image_path_2')) {
            if ($slider->image_path_2) {
                Storage::disk('public')->delete($slider->image_path_2);
            }
            $imagePath2 = $request->file('image_path_2')->store('homepagesliders', 'public');
        } else {
            $imagePath2 = $slider->image_path_2;
        }

        $slider->update([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'image_path_2' => $imagePath2,
            'link' => $request->link,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('frontend.page.sliders.index')
            ->with('success', 'Slider updated successfully.');
    }

    public function deleteSelectedSliders(Request $request)
    {
        $ids = $request->input('ids');
        $sliders = HomePageSlider::whereIn('id', $ids)->get();
        foreach ($sliders as $slider) {
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }
            if ($slider->image_path_2) {
                Storage::disk('public')->delete($slider->image_path_2);
            }
            $slider->delete();
        }
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
            'contact_label'     => 'nullable|string',
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
            'contact_label'     => $request->contact_label,
            'address'            => $request->address,
        ]);
        return redirect()->route('frontend.page.contactus.index');
    }

    public function agencyContactKhIndex()
    {
        $contactusnow = DB::table('agency_contact')->where('type', '=', 'khmer')->firstOrFail();
        return Inertia::render('Admin/Frontend/Pages/AgencyContact/Khmer', [
            'contactusnow' => fn () => $contactusnow,
        ]);
    }

    public function agencyContactKrIndex()
    {
        $contactusnow = DB::table('agency_contact')->where('type', '=', 'korea')->firstOrFail();
        return Inertia::render('Admin/Frontend/Pages/AgencyContact/Korea', [
            'contactusnow' => fn () => $contactusnow,
        ]);
    }

    public function storeAgencyContact(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'phone' => 'required|max:20',
            'name' => 'required|max:255',
            'telegram_link' => 'nullable|url',
            'facebook_link' => 'nullable|url',
            'whatapp_link' => 'nullable|url',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $contactusnow = DB::table('agency_contact')->where('type', '=', $request->type)->first();
        $avatar = $contactusnow->avatar ?? "";
        if ($request->hasFile('avatar')) {
            if (Storage::disk('public')->exists($avatar)) {
                Storage::disk('public')->delete($avatar);
            }            
            $avatar = $request->file('avatar')->store('contactusnow', 'public');
        }
        
        DB::table('agency_contact')->where('type', '=' , $request->type)->update([
            'title' => $request->title,
            'phone' => $request->phone,
            'name' => $request->name,
            'telegram_link' => $request->telegram_link,
            'facebook_link' => $request->facebook_link,
            'whatapp_link' => $request->whatapp_link,
            'avatar' => $avatar,
        ]);
        if($request->type === "khmer") {
            return redirect()->route('frontend.page.agencycontact-kh.index');
        }
        return redirect()->route('frontend.page.agencycontact-kr.index');
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
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
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
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);
        $image_path = null;
        if ($request->hasFile('image_path')) {
            $image_path = $request->file('image_path')->store('guarantees', 'public');
        }

        $guaranteeItem = GuaranteeItem::create(
            [
                'title' => $request->title,
                'description' => $request->description,
                'guarantee_id' => $request->guarantee_id,
                'image_path' => $image_path
            ]
        );
        
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                if($item['name'] !== "") {
                    $guaranteeItem->items()->create([
                        'name' => $item['name'],
                    ]);
                }
            }
        }

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
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        if ($request->hasFile('image_path')) {
            if($guaranteeItem?->image_path) {
                Storage::disk('public')->delete($guaranteeItem->image_path);  
            }
            
            $guaranteeItem->image_path = $request->file('image_path')->store('guarantees', 'public');
        }

        $guaranteeItem->update(
            [
                'title' => $request->title,
                'description' => $request->description,
            ]
        );
        
        if ($request->has('items')) {
            $guaranteeItem->items()->delete();
            foreach ($request->items as $item) {
                if($item['name']) {
                    $guaranteeItem->items()->create([
                        'name' => $item['name'],
                    ]);
                }
            }
        }

        return redirect()->route('frontend.page.guarantees.index');
    }

    public function getListGuaranteeItems(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(GuaranteeItem::with(['items'])->select([
                'id',
                'title',
                'description',
                'image_path',
            ]))
            ->addIndexColumn()
            ->addColumn('items_list', function ($row) {
                return $row->items->pluck('name')->implode(', ');
            })
            ->make(true);
        }
    }
    public function deleteSelectedGuaranteeItems(Request $request)
    {
        $ids = $request->input('ids');
        $guaranteeItems = GuaranteeItem::whereIn('id', $ids)->get();
        foreach ($guaranteeItems as $guaranteeItem) {
            if ($guaranteeItem->image_path) {
                Storage::disk('public')->delete($guaranteeItem->image_path);
            }
            $guaranteeItem->items()->delete();
            $guaranteeItem->delete();
        }
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
            'pdf' => 'required|file|mimes:pdf|max:51200',
        ]);
        $image_path = null;
        $pdf_path = null;
        if ($request->hasFile('image_path')) {
            $image_path = $request->file('image_path')->store('communities', 'public');
        }
        
        if ($request->hasFile('pdf')) {
            $pdf_path = $request->file('pdf')->store('communities', 'public');
        }

        CommunityItem::create(
            [
                'title' => $request->title,
                'pdf_path' => $pdf_path,
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
            'pdf' => 'required|file|mimes:pdf|max:51200',
        ]);

        if ($request->hasFile('image_path')) {
            if($communityItem?->image_path) {
                Storage::disk('public')->delete($communityItem->image_path);  
            }
            $communityItem->image_path = $request->file('image_path')->store('communities', 'public');
        }
        if ($request->hasFile('pdf')) {
            if ($communityItem->pdf_path) {
                Storage::disk('public')->delete($communityItem->pdf_path);
            }
            $communityItem->pdf_path = $request->file('pdf')->store('communities', 'public');
        }

        $communityItem->update(
            [
                'title' => $request->title,
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
            if ($communityItem->pdf_path) {
                Storage::disk('public')->delete($communityItem->pdf_path);
            }
            $communityItem->delete();
        }
        return redirect()->route('frontend.page.communities.index');
        
    }

    public function communityItemPdfPreview(CommunityItem $communityItem)
    {

        return Inertia::render('Admin/Frontend/Pages/Communities/PdfPreview', [
            'item' => $communityItem,
        ]);
    }
    public function communityItemDownloadPdf(CommunityItem $communityItem)
    {

        if (!Storage::disk('public')->exists($communityItem->pdf_path)) {
            abort(404, 'File not found');
        }
    
        // Return the file for download
        return Storage::disk('public')->download($communityItem->pdf_path);
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
            'category' => 'required|array',
            'category.*.id' => 'nullable|integer|exists:categories,id',
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
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $imagePath = $request->file('image_path')->store('menus', 'public');

        $menu = Menu::create([
            'code' => $request->code,
            'name' => $request->name,
            'image_path' => $imagePath,
            'category_id' => $request->input('category.id'),
        ]);

        // Attach related models
        if ($request->has('brands')) {
            $menu->brands()->attach(collect($request->input('brands'))->pluck('id'));
        }
        if ($request->has('models')) {
            $menu->models()->attach(collect($request->input('models'))->pluck('id'));
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
            'category' => 'required|array',
            'category.*.id' => 'nullable|integer|exists:categories,id',
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
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image_path')) {
            if ($menu->image_path) {
                Storage::disk('public')->delete($menu->image_path);
            }
            $imagePath = $request->file('image_path')->store('menus', 'public');
        } else {
            $imagePath = $menu->image_path;
        }

        $menu->update([
            'code' => $request->code,
            'name' => $request->name,
            'image_path' => $imagePath,
            'category_id' => $request->input('category.id'),
        ]);

        // Sync related models (replace old relationships with the new ones)
        $menu->brands()->sync(collect($request->input('brands'))->pluck('id'));
        $menu->models()->sync(collect($request->input('models'))->pluck('id'));
        $menu->fuel_types()->sync(collect($request->input('fuel_types'))->pluck('id'));
        $menu->steerings()->sync(collect($request->input('steerings'))->pluck('id'));
        $menu->locations()->sync(collect($request->input('locations'))->pluck('id'));
        $menu->drive_types()->sync(collect($request->input('drive_types'))->pluck('id'));
        $menu->passengers()->sync(collect($request->input('passengers'))->pluck('id'));

        // Redirect back to the menu list or desired route
        return redirect()->route('frontend.page.menus.index');
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
            $menu->fuel_types()->detach();
            $menu->steerings()->detach();
            $menu->locations()->detach();
            $menu->drive_types()->detach();
            $menu->passengers()->detach();

            if ($menu->image_path) {
                Storage::disk('public')->delete($menu->image_path);
            }
            $menu->delete();
        }
        return redirect()->route('frontend.page.menus.index');
    }

    public function getMenus(Request $request)
    {
        if ($request->ajax()) {
            // Eager load the related models to avoid N+1 query issues
            $data = Menu::with(['brands', 'models', 'category', 'fuel_types', 'steerings', 'drive_types', 'passengers', 'locations'])
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
                ->addColumn('category_name', function ($row) {
                    return $row->category->name;
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


    # taxinfos

    public function taxInfoIndex()
    {
        $taxInfo = TaxInfo::first();
        $items = TaxInfoItem::all();

        return Inertia::render('Admin/Frontend/Pages/TaxInfos/Index', [
            'taxInfo' => $taxInfo,
            'is_limited' => $items->count() >= 5 ? true : false
        ]);
    }
    public function taxInfoStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'label' => 'required|string',
        ]);
        $taxinfo = Taxinfo::first();
        $taxinfo->update($validated);

        return redirect()->route('frontend.page.taxInfos.index');
    }


    public function taxInfoItemStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:tax_info_items,title',
            'pdf' => 'required|file|mimes:pdf|max:51200',
        ]);
        $items = TaxInfoItem::all();
        if($items->count() >= 5) {
            return redirect()->route('frontend.page.taxInfos.index');
        }
        $image_path = null;
        $pdf_path = null;
        if ($request->hasFile('image_path')) {
            $image_path = $request->file('image_path')->store('taxInfos', 'public');
        }
        
        if ($request->hasFile('pdf')) {
            $pdf_path = $request->file('pdf')->store('taxInfos', 'public');
        }

        TaxInfoItem::create(
            [
                'title' => $request->title,
                'pdf_path' => $pdf_path,
                'tax_info_id' => $request->tax_info_id,
            ]
        );

        return redirect()->route('frontend.page.taxInfos.index');
    }
    public function taxInfoItemUpdate(Request $request, TaxInfoItem $taxInfoItem)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:191',
                Rule::unique('tax_info_items', 'title')->ignore($taxInfoItem->id), 
            ],
            'pdf' => 'nullable|file|mimes:pdf|max:51200',
        ]);

        if ($request->hasFile('image_path')) {
            if($taxInfoItem?->image_path) {
                Storage::disk('public')->delete($taxInfoItem->image_path);  
            }
            $taxInfoItem->image_path = $request->file('image_path')->store('taxInfos', 'public');
        }
        if ($request->hasFile('pdf')) {
            if ($taxInfoItem->pdf_path) {
                Storage::disk('public')->delete($taxInfoItem->pdf_path);
            }
            $taxInfoItem->pdf_path = $request->file('pdf')->store('taxinfos', 'public');
        }

        $taxInfoItem->update(
            [
                'title' => $request->title,
                'tax_info_id' => $request->tax_info_id,
            ]
        );

        return redirect()->route('frontend.page.taxInfos.index');
    }

    public function taxInfoItemChangeStatus(Request $request, TaxInfoItem $taxInfoItem)
    {
        $request->validate([
            'id' => 'required|integer|exists:tax_info_items,id',
            'is_active' => 'required|boolean',
        ]);

        $taxInfoItem->update(
            [
                'is_active' => $request->is_active,
            ]
        );

        return redirect()->route('frontend.page.taxInfos.index');
    }

    public function getListTaxInfoItems(Request $request)
    {
        if ($request->ajax()) {
            $data = TaxInfoItem::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function deleteSelectedTaxInfoItems(Request $request)
    {
        $ids = $request->input('ids');
        $taxInfoItem = TaxinfoItem::whereIn('id', $ids)->get();
        if ($taxInfoItem->isEmpty()) {
            return redirect()->route('frontend.page.taxInfos.index', ['message' => 'No tax infos found with the provided IDs']);
        }
        foreach ($taxInfoItem as $taxInfoItem) {
            if ($taxInfoItem->pdf_path) {
                Storage::disk('public')->delete($taxInfoItem->pdf_path);
            }
            $taxInfoItem->delete();
        }
        return redirect()->route('frontend.page.taxInfos.index');
    }

    public function taxInfoItemPdfPreview(TaxInfoItem $taxInfoItem)
    {

        return Inertia::render('Admin/Frontend/Pages/TaxInfos/PdfPreview', [
            'item' => $taxInfoItem,
        ]);
    }
    public function taxInfoItemDownloadPdf(TaxInfoItem $taxInfoItem)
    {

        if (!Storage::disk('public')->exists($taxInfoItem->pdf_path)) {
            abort(404, 'File not found');
        }
    
        // Return the file for download
        return Storage::disk('public')->download($taxInfoItem->pdf_path);
    }




    # Menu Car Gallery

    public function menuCarGalleryIndex()
    {
        $menuCarGallery = MenuCarGallery::first();
        return Inertia::render('Admin/Frontend/Pages/MenuCarGalleries/Index', [
            'menuCarGallery' => $menuCarGallery,
        ]);
    }
    public function menuCarGalleryStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'label' => 'required|string',
        ]);

        $menuCarGallery = MenuCarGallery::first();
        $menuCarGallery->update($validated);

        return redirect()->route('frontend.page.menuCarGallery.index');
    }


    public function menuCarGalleryItemStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191|unique:menu_car_gallery_items,title',
            'pdf' => 'required|file|mimes:pdf|max:51200',
        ]);
        $image_path = null;
        $pdf_path = null;
        if ($request->hasFile('image_path')) {
            $image_path = $request->file('image_path')->store('menuCarGalleries', 'public');
        }
        
        if ($request->hasFile('pdf')) {
            $pdf_path = $request->file('pdf')->store('menuCarGalleries', 'public');
        }

        MenuCarGalleryItem::create(
            [
                'title' => $request->title,
                'pdf_path' => $pdf_path,
                'menu_car_gallery_id' => $request->menu_car_gallery_id,
            ]
        );

        return redirect()->route('frontend.page.menuCarGallery.index');
    }
    public function menuCarGalleryItemUpdate(Request $request, MenuCarGalleryItem $menuCarGalleryItem)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:191',
                Rule::unique('menu_car_gallery_items', 'title')->ignore($menuCarGalleryItem->id), 
            ],
            'pdf' => 'nullable|file|mimes:pdf|max:51200',
        ]);

        if ($request->hasFile('image_path')) {
            if($menuCarGalleryItem?->image_path) {
                Storage::disk('public')->delete($menuCarGalleryItem->image_path);  
            }
            $menuCarGalleryItem->image_path = $request->file('image_path')->store('menuCarGalleries', 'public');
        }
        if ($request->hasFile('pdf')) {
            if ($menuCarGalleryItem->pdf_path) {
                Storage::disk('public')->delete($menuCarGalleryItem->pdf_path);
            }
            $menuCarGalleryItem->pdf_path = $request->file('pdf')->store('menuCarGalleries', 'public');
        }

        $menuCarGalleryItem->update(
            [
                'title' => $request->title,
                'menu_car_gallery_id' => $request->menu_car_gallery_id,
            ]
        );

        return redirect()->route('frontend.page.menuCarGallery.index');
    }

    public function menuCarGalleryItemChangeStatus(Request $request, MenuCarGalleryItem $menuCarGalleryItem)
    {
        $request->validate([
            'id' => 'required|integer|exists:menu_car_gallery_items,id',
            'is_active' => 'required|boolean',
        ]);

        $menuCarGalleryItem->update(
            [
                'is_active' => $request->is_active,
            ]
        );

        return redirect()->route('frontend.page.menuCarGallery.index');
    }

    public function getListMenuCarGalleryItems(Request $request)
    {
        if ($request->ajax()) {
            $data = MenuCarGalleryItem::orderBy('id', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function deleteSelectedMenuCarGalleryItems(Request $request)
    {
        $ids = $request->input('ids');
        $menuCarGalleryItem = MenuCarGalleryItem::whereIn('id', $ids)->get();
        if ($menuCarGalleryItem->isEmpty()) {
            return redirect()->route('frontend.page.menuCarGallery.index', ['message' => 'No tax infos found with the provided IDs']);
        }
        foreach ($menuCarGalleryItem as $menuCarGalleryItem) {
            if ($menuCarGalleryItem->pdf_path) {
                Storage::disk('public')->delete($menuCarGalleryItem->pdf_path);
            }
            $menuCarGalleryItem->delete();
        }
        return redirect()->route('frontend.page.menuCarGallery.index');
    }

    public function menuCarGalleryItemPdfPreview(MenuCarGalleryItem $menuCarGalleryItem)
    {

        return Inertia::render('Admin/Frontend/Pages/MenuCarGalleries/PdfPreview', [
            'item' => $menuCarGalleryItem,
        ]);
    }
    public function menuCarGalleryItemDownloadPdf(MenuCarGalleryItem $menuCarGalleryItem)
    {

        if (!Storage::disk('public')->exists($menuCarGalleryItem->pdf_path)) {
            abort(404, 'File not found');
        }
    
        // Return the file for download
        return Storage::disk('public')->download($menuCarGalleryItem->pdf_path);
    }



}
