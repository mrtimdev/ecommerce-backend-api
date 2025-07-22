<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\ModelController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FuelTypeController;
use App\Http\Controllers\Admin\SteeringController;
use App\Http\Controllers\Admin\ConditionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriveTypeController;
use App\Http\Controllers\Admin\PassengerController;
use App\Http\Controllers\Admin\OtherOptionController;
use App\Http\Controllers\Admin\TransmissionTypeController;
use App\Http\Controllers\Admin\ClientController as AdminClientController;



Route::middleware('auth')->prefix('admin')->group(function () {

    Route::resource('products', ProductController::class);
    Route::get('/admin/products/ajax-products', [ProductController::class, 'ajaxProducts'])->name('admin.products.ajax-products');

    // Additional custom routes if needed
    Route::get('products/{product}/history', [ProductController::class, 'history'])
        ->name('products.history');


    Route::resource('stocks', StockController::class);
    Route::resource('deliveries', DeliveryController::class);
    // Route::resource('clients', ClientController::class);

    // Additional custom routes
    Route::get('/clients/{client}/stocks', [ClientController::class, 'stocks'])->name('clients.stocks');
});



Route::post('/admin/locale', [LocaleController::class, 'setLocale']);
Route::get('/admin/locale', [LocaleController::class, 'getLocale']);


Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard.home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->prefix('client')->group(function () {
    Route::prefix('/cars')->group(function() {
        Route::get('/', [AdminClientController::class, 'clientCarsIndex'])->name('client.cars.index');
        Route::get('/list', [AdminClientController::class, 'getListCars'])->name('client.cars.list');
    });
});


Route::middleware('auth')->prefix('admin')->group(function () {


    Route::get('/cars/all', [CarController::class, 'getCars'])->name('cars.all');
    Route::get('/cars/list', [CarController::class, 'getListCars'])->name('cars.list');
    Route::post('/cars/delete-selected', [CarController::class, 'deleteSelected'])->name('cars.destroy.selected');
    Route::get('cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('cars/store', [CarController::class, 'store'])->name('cars.store');
    Route::post('cars/handle-source-link', [CarController::class, 'handleSourceLink'])->name('cars.handle-source-link');
    Route::post('cars/{car}/handle-source-link-edit', [CarController::class, 'handleSourceLinkEdit'])->name('cars.handle-source-link-edit');
    Route::get('cars', [CarController::class, 'index'])->name('cars.index');
    Route::get('cars/{car}/galleries', [CarController::class, 'getGalleries'])->name('cars.galleries');
    Route::get('cars/{car}/show', [CarController::class, 'show'])->name('cars.show');
    Route::delete('cars/{car}/delete', [CarController::class, 'destroy'])->name('cars.destroy');
    Route::get('cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::post('cars/{car}/update', [CarController::class, 'update'])->name('cars.update');
    Route::post('cars/gallery/{car}/update-gallery', [CarController::class, 'updateGallery'])->name('cars.updateGallery');
    Route::post('cars/gallery/{car}/update-featured-image', [CarController::class, 'updateFeaturedImage'])->name('cars.updateFeaturedImage');
    Route::post('cars/gallery/{car}/update-sourced-link-image', [CarController::class, 'updateSourcedLink'])->name('cars.updateSourcedLink');
    Route::post('cars/gallery/{carImage}/remove-gallery', [CarController::class, 'removeGallery'])->name('cars.removeGallery');

    Route::get('cars/featured', [CarController::class, 'carFeatured'])->name('cars.featured');
    Route::get('cars/featured/list', [CarController::class, 'getCarsFeatured'])->name('cars.featured.list');
    Route::post('cars/featured/store', [CarController::class, 'addCarFeatured'])->name('cars.featured.store');
    Route::post('cars/featured/remove', [CarController::class, 'removeCarFeatured'])->name('cars.featured.remove');

    Route::get('/categories/all', [CategoryController::class, 'getCategories'])->name('categories.list');
    Route::post('/categories/delete-selected', [CategoryController::class, 'deleteSelected'])->name('categories.destroy.selected');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::delete('categories/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::post('categories/{category}/update', [CategoryController::class, 'update'])->name('categories.update');


    Route::get('/conditions/all', [ConditionController::class, 'getConditions'])->name('conditions.list');
    Route::post('/conditions/delete-selected', [ConditionController::class, 'deleteSelected'])->name('conditions.destroy.selected');
    Route::get('conditions/create', [ConditionController::class, 'create'])->name('conditions.create');
    Route::post('conditions/store', [ConditionController::class, 'store'])->name('conditions.store');
    Route::get('conditions', [ConditionController::class, 'index'])->name('conditions.index');
    Route::delete('condition/{condition}/delete', [ConditionController::class, 'destroy'])->name('conditions.destroy');
    Route::get('conditions/{condition}/edit', [ConditionController::class, 'edit'])->name('conditions.edit');
    Route::post('conditions/{condition}/update', [ConditionController::class, 'update'])->name('conditions.update');

    Route::get('/brands/all', [BrandController::class, 'getBrands'])->name('brands.all');
    Route::get('/brands/list', [BrandController::class, 'getListBrands'])->name('brands.list');
    Route::post('/brands/delete-selected', [BrandController::class, 'deleteSelected'])->name('brands.destroy.selected');
    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('brands/store', [BrandController::class, 'store'])->name('brands.store');
    Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
    Route::delete('brands/{brand}/delete', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::post('brands/{brand}/update', [BrandController::class, 'update'])->name('brands.update');

    Route::get('/models/by-brands', [ModelController::class, 'getModelsByBrand'])->name('models.by.brand');
    Route::get('/models/get-options', [ModelController::class, 'getModelOptions'])->name('models.options');
    Route::get('/models/all', [ModelController::class, 'getModels'])->name('models.list');
    Route::post('/models/delete-selected', [ModelController::class, 'deleteSelected'])->name('models.destroy.selected');
    Route::get('models/create', [ModelController::class, 'create'])->name('models.create');
    Route::post('models/store', [ModelController::class, 'store'])->name('models.store');
    Route::get('models', [ModelController::class, 'index'])->name('models.index');
    Route::delete('models/{model}/delete', [ModelController::class, 'destroy'])->name('models.destroy');
    Route::get('models/{model}/edit', [ModelController::class, 'edit'])->name('models.edit');
    Route::post('models/{model}/update', [ModelController::class, 'update'])->name('models.update');

    Route::get('/fuel_types/all', [FuelTypeController::class, 'getFuelTypes'])->name('fuelTypes.list');
    Route::post('/fuel_types/delete-selected', [FuelTypeController::class, 'deleteSelected'])->name('fuelTypes.destroy.selected');
    Route::get('fuel_types/create', [FuelTypeController::class, 'create'])->name('fuelTypes.create');
    Route::post('fuel_types/store', [FuelTypeController::class, 'store'])->name('fuelTypes.store');
    Route::get('fuel_types', [FuelTypeController::class, 'index'])->name('fuelTypes.index');
    Route::delete('fuel_types/{fuelType}/delete', [FuelTypeController::class, 'destroy'])->name('fuelTypes.destroy');
    Route::get('fuel_types/{fuelType}/edit', [FuelTypeController::class, 'edit'])->name('fuelTypes.edit');
    Route::post('fuel_types/{fuelType}/update', [FuelTypeController::class, 'update'])->name('fuelTypes.update');


    Route::get('/transmission_types/all', [TransmissionTypeController::class, 'getTransmissionTypes'])->name('transmissionTypes.list');
    Route::post('/transmission_types/delete-selected', [TransmissionTypeController::class, 'deleteSelected'])->name('transmissionTypes.destroy.selected');
    Route::get('transmission_types/create', [TransmissionTypeController::class, 'create'])->name('transmissionTypes.create');
    Route::post('transmission_types/store', [TransmissionTypeController::class, 'store'])->name('transmissionTypes.store');
    Route::get('transmission_types', [TransmissionTypeController::class, 'index'])->name('transmissionTypes.index');
    Route::get('transmission_types/{transmissionType}/edit', [TransmissionTypeController::class, 'edit'])->name('transmissionTypes.edit');
    Route::post('transmission_types/{transmissionType}/update', [TransmissionTypeController::class, 'update'])->name('transmissionTypes.update');

    Route::get('/drive_types/all', [DriveTypeController::class, 'getDriveTypes'])->name('driveTypes.list');
    Route::post('/drive_types/delete-selected', [DriveTypeController::class, 'deleteSelected'])->name('driveTypes.destroy.selected');
    Route::post('drive_types/store', [DriveTypeController::class, 'store'])->name('driveTypes.store');
    Route::get('drive_types', [DriveTypeController::class, 'index'])->name('driveTypes.index');
    Route::post('drive_types/{driveType}/update', [DriveTypeController::class, 'update'])->name('driveTypes.update');

    Route::get('/steerings/all', [SteeringController::class, 'getSteerings'])->name('steerings.list');
    Route::post('/steerings/delete-selected', [SteeringController::class, 'deleteSelected'])->name('steerings.destroy.selected');
    Route::post('steerings/store', [SteeringController::class, 'store'])->name('steerings.store');
    Route::get('steerings', [SteeringController::class, 'index'])->name('steerings.index');
    Route::post('steerings/{steering}/update', [SteeringController::class, 'update'])->name('steerings.update');


    Route::get('/colors/all', [ColorController::class, 'getColors'])->name('colors.list');
    Route::post('/colors/delete-selected', [ColorController::class, 'deleteSelected'])->name('colors.destroy.selected');
    Route::get('colors/create', [ColorController::class, 'create'])->name('colors.create');
    Route::post('colors/store', [ColorController::class, 'store'])->name('colors.store');
    Route::get('colors', [ColorController::class, 'index'])->name('colors.index');
    Route::get('colors/{color}/edit', [ColorController::class, 'edit'])->name('colors.edit');
    Route::post('colors/{color}/update', [ColorController::class, 'update'])->name('colors.update');

    Route::get('/passengers/all', [PassengerController::class, 'getPassengers'])->name('passengers.list');
    Route::post('/passengers/delete-selected', [PassengerController::class, 'deleteSelected'])->name('passengers.destroy.selected');
    Route::post('passengers/store', [PassengerController::class, 'store'])->name('passengers.store');
    Route::get('passengers', [PassengerController::class, 'index'])->name('passengers.index');
    Route::post('passengers/{passenger}/update', [PassengerController::class, 'update'])->name('passengers.update');

    // Route::name('other-options.')->group(function () {
        Route::prefix('hotmarks')->group(function () {
            Route::get('/all', [OtherOptionController::class, 'getHotMarks'])->name('hotmarks.list');
            Route::post('/delete-selected', [OtherOptionController::class, 'deleteSelectedHotmarks'])->name('hotmarks.destroy.selected');
            Route::get('/index', [OtherOptionController::class, 'hotMarkIndex'])->name('hotmarks.index');
            Route::post('', [OtherOptionController::class, 'hotMarkStore'])->name('hotmarks.store');
            Route::post('/{hotmark}', [OtherOptionController::class, 'hotMarkUpdate'])->name('hotmarks.update');
        });
        Route::prefix('options')->group(function () {
            Route::get('/all', [OtherOptionController::class, 'getOptions'])->name('options.list');
            Route::post('/delete-selected', [OtherOptionController::class, 'deleteSelectedoptions'])->name('options.destroy.selected');
            Route::get('/index', [OtherOptionController::class, 'optionIndex'])->name('options.index');
            Route::post('', [OtherOptionController::class, 'optionStore'])->name('options.store');
            Route::post('/{option}', [OtherOptionController::class, 'optionUpdate'])->name('options.update');
        });
        Route::get('/group/get-with-options', [OtherOptionController::class, 'getGroupOptions'])->name('group.options');

        Route::prefix('option-groups')->group(function () {
            Route::get('/all', [OtherOptionController::class, 'getOptionGroups'])->name('optionGroups.list');
            Route::post('/delete-selected', [OtherOptionController::class, 'deleteSelectedOptionGroups'])->name('optionGroups.destroy.selected');
            Route::get('/index', [OtherOptionController::class, 'optionGroupIndex'])->name('optionGroups.index');
            Route::post('', [OtherOptionController::class, 'optionGroupStore'])->name('optionGroups.store');
            Route::post('/{optionGroup}', [OtherOptionController::class, 'optionGroupUpdate'])->name('optionGroups.update');
        });
    // });
});

# settings routes
Route::middleware('auth')->prefix('admin')->name('settings.')->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('index');

    Route::prefix('settings')->group(function () {
        Route::get('/info', [SettingController::class, 'getSettingConfigs'])->name('info');
        Route::post('/shipping', [SettingController::class, 'shipping'])->name('shipping');
        Route::post('/login_logo', [SettingController::class, 'login_logo'])->name('login_logo');
        Route::post('/site_configs', [SettingController::class, 'site_configs'])->name('site_configs');

        Route::get('/countries/list', [SettingController::class, 'getCountries'])->name('countries.list');
        Route::get('/countries/all', [SettingController::class, 'getAllCountries'])->name('countries.all');
        Route::post('/countries/delete-selected', [SettingController::class, 'deleteSelectedCountries'])->name('countries.destroy.selected');
        Route::get('/countries', [SettingController::class, 'countryIndex'])->name('countries.index');
        Route::post('/countries', [SettingController::class, 'countryStore'])->name('countries.store');
        Route::post('/countries/{country}', [SettingController::class, 'countryUpdate'])->name('countries.update');
    });

});

# frontend routes
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::middleware('auth')->prefix('frontend')->name('frontend.')->group(function () {
        Route::middleware('auth')->prefix('pages')->name('page.')->group(function () {

            Route::get('/menus/all', [FrontendController::class, 'getMenus'])->name('menus.list');
            Route::post('/menus/delete-selected', [FrontendController::class, 'deleteSelectedMenus'])->name('menus.destroy.selected');
            Route::get('/menus', [FrontendController::class, 'menuIndex'])->name('menus.index');
            Route::post('/menus', [FrontendController::class, 'menustore'])->name('menus.store');
            Route::post('/menus/{menu}', [FrontendController::class, 'menuUpdate'])->name('menus.update');


            Route::get('/sliders/all', [FrontendController::class, 'getSliders'])->name('sliders.list');
            Route::post('/sliders/delete-selected', [FrontendController::class, 'deleteSelectedSliders'])->name('sliders.destroy.selected');
            Route::get('/sliders', [FrontendController::class, 'sliderIndex'])->name('sliders.index');
            Route::post('/sliders', [FrontendController::class, 'sliderStore'])->name('sliders.store');
            Route::post('/sliders/{slider}', [FrontendController::class, 'sliderUpdate'])->name('sliders.update');

            # contact us page
            Route::get('/contact-us', [FrontendController::class, 'contactUsIndex'])->name('contactus.index');
            Route::post('/contact-us-store', [FrontendController::class, 'storeContactUs'])->name('contactus.store');

            Route::get('/agency-contact/khmer', [FrontendController::class, 'agencyContactKhIndex'])->name('agencycontact-kh.index');
            Route::get('/agency-contact/korea', [FrontendController::class, 'agencycontactKrIndex'])->name('agencycontact-kr.index');
            Route::post('/agency-contact-store', [FrontendController::class, 'storeAgencyContact'])->name('agencycontact.store');



            Route::get('/videos/all', [FrontendController::class, 'getVideos'])->name('videos.list');
            Route::post('/videos/delete-selected', [FrontendController::class, 'deleteSelectedVideos'])->name('videos.destroy.selected');
            Route::get('/videos', [FrontendController::class, 'videoIndex'])->name('videos.index');
            Route::post('/videos', [FrontendController::class, 'videoStore'])->name('videos.store');
            Route::post('/videos/{video}', [FrontendController::class, 'videoUpdate'])->name('videos.update');


            Route::get('/stories/all', [FrontendController::class, 'getStories'])->name('stories.list');
            Route::post('/stories/delete-selected', [FrontendController::class, 'deleteSelectedStories'])->name('stories.destroy.selected');
            Route::get('/stories', [FrontendController::class, 'storyIndex'])->name('stories.index');
            Route::post('/stories', [FrontendController::class, 'storyStore'])->name('stories.store');
            Route::post('/stories/{story}', [FrontendController::class, 'storyUpdate'])->name('stories.update');


            Route::get('/services/items/all', [FrontendController::class, 'getListServiceItems'])->name('serviceItems.list');
            Route::post('/services/delete-item-selected', [FrontendController::class, 'deleteSelectedServiceItems'])->name('serviceItems.destroy.selected');
            Route::get('/services', [FrontendController::class, 'serviceIndex'])->name('services.index');
            Route::post('/services', [FrontendController::class, 'serviceStore'])->name('services.store');
            Route::post('/services/item', [FrontendController::class, 'serviceItemStore'])->name('serviceItems.store');
            Route::post('/services/{serviceItem}/update', [FrontendController::class, 'serviceItemUpdate'])->name('serviceItems.update');

            Route::get('/guarantees/items/all', [FrontendController::class, 'getListGuaranteeItems'])->name('guaranteeItems.list');
            Route::post('/guarantees/delete-item-selected', [FrontendController::class, 'deleteSelectedGuaranteeItems'])->name('guaranteeItems.destroy.selected');
            Route::get('/guarantees', [FrontendController::class, 'guaranteeIndex'])->name('guarantees.index');
            Route::post('/guarantees', [FrontendController::class, 'guaranteeStore'])->name('guarantees.store');
            Route::post('/guarantees/item', [FrontendController::class, 'guaranteeItemStore'])->name('guaranteeItems.store');
            Route::post('/guarantees/{guaranteeItem}/update', [FrontendController::class, 'guaranteeItemUpdate'])->name('guaranteeItems.update');

            Route::get('/communities/items/all', [FrontendController::class, 'getListCommunityItems'])->name('communityItems.list');
            Route::post('/communities/delete-item-selected', [FrontendController::class, 'deleteSelectedCommunityItems'])->name('communityItems.destroy.selected');
            Route::get('/communities', [FrontendController::class, 'communityIndex'])->name('communities.index');
            Route::post('/communities', [FrontendController::class, 'communityStore'])->name('communities.store');
            Route::post('/communities/item', [FrontendController::class, 'communityItemStore'])->name('communityItems.store');
            Route::post('/communities/{communityItem}/update', [FrontendController::class, 'communityItemUpdate'])->name('communityItems.update');
            Route::get('/communities/{communityItem}/pdf', [FrontendController::class, 'communityItemPdfPreview'])->name('communityItems.pdf-preview');

            Route::get('/community/{communityItem}/download-pdf', [FrontendController::class, 'communityItemDownloadPdf'])->name('community.download.pdf');


            # taxinfos
            Route::get('/taxinfos/items/all', [FrontendController::class, 'getListTaxInfoItems'])->name('taxInfoItems.list');
            Route::post('/taxinfos/delete-item-selected', [FrontendController::class, 'deleteSelectedTaxInfoItems'])->name('taxInfoItems.destroy.selected');
            Route::get('/taxinfos', [FrontendController::class, 'taxInfoIndex'])->name('taxInfos.index');
            Route::post('/taxinfos', [FrontendController::class, 'taxInfoStore'])->name('taxInfos.store');
            Route::post('/taxinfos/item', [FrontendController::class, 'taxInfoItemstore'])->name('taxInfoItems.store');
            Route::post('/taxinfos/{taxInfoItem}/update', [FrontendController::class, 'taxinfoItemUpdate'])->name('taxInfoItems.update');
            Route::post('/taxinfos/{taxInfoItem}/change-status', [FrontendController::class, 'taxinfoItemChangeStatus'])->name('taxInfoItems.change-status');
            Route::get('/taxinfos/{taxInfoItem}/pdf', [FrontendController::class, 'taxInfoItemPdfPreview'])->name('taxInfoItems.pdf-preview');

            Route::get('/taxinfo/{taxInfoItem}/download-pdf', [FrontendController::class, 'taxInfoItemDownloadPdf'])->name('taxInfo.download.pdf');


            # menu car gallery
            Route::get('/menu_car_gallery/items/all', [FrontendController::class, 'getListMenuCarGalleryItems'])->name('menuCarGalleryItems.list');
            Route::post('/menu_car_gallery/delete-item-selected', [FrontendController::class, 'deleteSelectedMenuCarGalleryItems'])->name('menuCarGalleryItems.destroy.selected');
            Route::get('/menu_car_gallery', [FrontendController::class, 'menuCarGalleryIndex'])->name('menuCarGallery.index');
            Route::post('/menu_car_gallery', [FrontendController::class, 'menuCarGalleryStore'])->name('menuCarGallery.store');
            Route::post('/menu_car_gallery/item', [FrontendController::class, 'menuCarGalleryItemstore'])->name('menuCarGalleryItems.store');
            Route::post('/menu_car_gallery/{menuCarGalleryItem}/update', [FrontendController::class, 'menuCarGalleryItemUpdate'])->name('menuCarGalleryItems.update');
            Route::post('/menu_car_gallery/{menuCarGalleryItem}/change-status', [FrontendController::class, 'menuCarGalleryItemChangeStatus'])->name('menuCarGalleryItems.change-status');
            Route::get('/menu_car_gallery/{menuCarGalleryItem}/pdf', [FrontendController::class, 'menuCarGalleryItemPdfPreview'])->name('menuCarGalleryItems.pdf-preview');

            Route::get('/menu_car_gallery/{menuCarGalleryItem}/download-pdf', [FrontendController::class, 'menuCarGalleryItemDownloadPdf'])->name('menuCarGallery.download.pdf');
        });
    });
});



// Route::middleware('auth')->prefix('admin/auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::get('/assets/country/{code}/flags', function (Request $req, $code) {
    $lower_code = strtolower($code);
    // return $req->is_online_icon;
        // return "https://flagcdn.com/w40/{$lower_code}.png";
        // $response = Http::get($onlinePath);
        // if ($response->successful()) {
        //     return Response::make($response->body(), 200, [
        //         'Content-Type' => 'image/png',
        //     ]);
        // }
    $code = strtoupper($code);
    $localPath = resource_path("js/country/flags/{$code}.svg");
    if (!file_exists($localPath)) {
        return Response::file(public_path('assets/images/tim-dev.png'), [
            'Content-Type' => 'image',
        ]);
    }
    return Response::file($localPath, [
        'Content-Type' => 'image/svg+xml',
    ]);
})->name('country.flag.code');

Route::get('/link', function() {

    $storagePath = "/home1/obbivemy/ftdev_files/storage/app/public";
    $linkPath = "/home1/obbivemy/ftdevs/storage";

    // Check if the link already exists
    if (file_exists($linkPath)) {
        return response()->json(['message' => 'The symbolic link already exists.'], 400);
    }

    // Create the symbolic link
    $command = "ln -s $storagePath $linkPath";
    exec($command, $output, $returnVar);

    if ($returnVar === 0) {
        return response()->json(['message' => 'Symbolic link created successfully.']);
    } else {
        Log::error('Failed to create symbolic link', ['output' => $output, 'returnVar' => $returnVar]);
        return response()->json(['message' => 'Failed to create symbolic link.'], 500);
    }
    echo 'ok';
});

Route::get('/link-storage', function () {
    Artisan::call('storage:link');
    return 'Storage link created successfully!';
});
Route::get('/clear', function () {
    Artisan::call('optimize:clear');
    return 'Application cache cleared and optimized successfully!';
});
Route::fallback(fn() => Inertia::render('Errors/404'));
require __DIR__.'/auth.php';
