<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Helpers\ListingHelper;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProductRequest;

use App\Permission;
use App\Page;

use Storage;
use Auth;

use App\EcommerceModel\ProductAdditionalInfo;
use App\EcommerceModel\ProductCategory;
use App\EcommerceModel\ProductPhoto;
use App\EcommerceModel\ProductTag;
use App\EcommerceModel\Product;

use App\StPaulModel\OnSaleProducts;
use App\InventoryReceiverHeader;
use App\InventoryReceiverDetail;

use App\EcommerceModel\Customer;
use App\User;

use Illuminate\Support\Facades\Input;
class ProductController extends Controller
{
    private $searchFields = ['name'];
    private $advanceSearchFields = ['category_id', 'name', 'brand', 'short_description', 'description', 'status', 'price1', 'price2', 'user_id', 'updated_at1', 'updated_at2'];

    public function __construct()
    {
        Permission::module_init($this, 'product');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customConditions = [
            [
                'field' => 'status',
                'operator' => '!=',
                'value' => 'UNEDITABLE',
                'apply_to_deleted_data' => true
            ]
        ];

        $listing = new ListingHelper( 'desc', 10, 'updated_at', $customConditions);

        $products   = $listing->simple_search(Product::class, $this->searchFields);
        $parentcategories = ProductCategory::where('parent_id',0)->where('status','PUBLISHED')->get(); 
        $subcategories    = ProductCategory::where('parent_id','>',0)->where('status','PUBLISHED')->get();

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);

        $advanceSearchData = $listing->get_search_data($this->advanceSearchFields);
        $uniqueProductByCategory = $listing->get_unique_item_by_column('App\EcommerceModel\Product', 'category_id');
        $uniqueProductByUser = $listing->get_unique_item_by_column('App\EcommerceModel\Product', 'created_by');

        $searchType = 'simple_search';

        return view('admin.products.index',compact('products', 'filter', 'searchType','uniqueProductByCategory','uniqueProductByUser','advanceSearchData','subcategories','parentcategories'));

    }

    public function advance_index(Request $request)
    {
        $customConditions = [
            [
                'field' => 'status',
                'operator' => '!=',
                'value' => 'UNEDITABLE',
                'apply_to_deleted_data' => true
            ]
        ];

        $equalQueryFields = ['category_id', 'status', 'created_by','brand'];

        $listing = new ListingHelper( 'desc', 10, 'updated_at', $customConditions);
        $products = $listing->advance_search('App\EcommerceModel\Product', $this->advanceSearchFields, $equalQueryFields);

        $filter = $listing->get_filter($this->searchFields);

        $advanceSearchData = $listing->get_search_data($this->advanceSearchFields);
        $uniqueProductByCategory = $listing->get_unique_item_by_column('App\EcommerceModel\Product', 'category_id');
        $uniqueProductByUser = $listing->get_unique_item_by_column('App\EcommerceModel\Product', 'created_by');

        $parentcategories = ProductCategory::where('parent_id',0)->where('status','PUBLISHED')->get(); 
        $subcategories    = ProductCategory::where('parent_id','>',0)->where('status','PUBLISHED')->get(); 

        $searchType = 'advance_search';

        return view('admin.products.index',compact('products', 'filter', 'searchType','uniqueProductByCategory','uniqueProductByUser','advanceSearchData','subcategories','parentcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentCategories = ProductCategory::where('parent_id',0)->where('status','PUBLISHED')->get();

        return view('admin.products.create',compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $slug = Page::convert_to_slug($request->name);

        $product = Product::create([
            'code' => $request->code,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->long_description,
            'reorder_point' => $request->reorder_point,
            'currency' => 'PHP',
            'price' => Input::get('price'),
            'size' => $request->size,
            'weight' => Input::get('weight'),
            'status' => ($request->has('status') ? 'PUBLISHED' : 'PRIVATE'),
            'is_featured' => $request->has('is_featured'),
            'uom' => Input::get('uom'),
            'discount' => $request->discount,
            'is_recommended' => ($request->has('is_recommended') ? 1 : 0),
            'isfront' => ($request->has('isfront') ? 1 : 0),
            'for_pickup' => ($request->has('for_pickup') ? 1 : 0),
            'meta_title' => $request->seo_title,
            'meta_keyword' => $request->seo_keywords,
            'meta_description' => $request->seo_description,
            'created_by' => Auth::id(),
        ]);

        $this->store_inventory($product->id,$request->qty);
        $this->store_product_additional_info($product->id,$request);
        $this->tags($product->id, $request->tags);

        $newPhotos = $this->set_order(request('photos'));
        $productPhotos = $this->move_product_to_official_folder($product->id, $newPhotos);

        $this->delete_temporary_product_folder();

        foreach ($productPhotos as $key => $photo) {
            ProductPhoto::create([
                'product_id' => $product->id,
                'name' => (empty($photo['name']) ? '' : $photo['name']),
                'description' => '',
                'path' => $photo['image_path'],
                'status' => 'PUBLISHED',
                'is_primary' => ($key == $request->is_primary) ? 1 : 0,
                'created_by' => Auth::id()
            ]);
        }

        return redirect()->route('products.index')->with('success', __('standard.products.product.create_success'));
    }

    public function add_inventory(Request $request)
    {
        $this->store_inventory($request->productid,$request->qty);

        return back()->with('success','Inventory has been added.');
    }

    public function store_inventory($productid,$qty)
    {
        $header= InventoryReceiverHeader::create([
            'user_id' => Auth::id(),
            'posted_at' => now(),
            'posted_by' => Auth::id(),
            'status' => 'POSTED'
        ]);

        InventoryReceiverDetail::create([
            'product_id' => $productid,
            'inventory' => $qty,
            'header_id' => $header->id
        ]);
    }
    
    public function store_product_additional_info($prodID,$request)
    {
        ProductAdditionalInfo::create([
            'product_id' => $prodID,
            'synopsis' => $request->synopsis,
            'authors' => $request->authors,
            'materials' => $request->materials,
            'no_of_pages' => $request->no_of_pages,
            'isbn' => $request->isbn,
            'editorial_reviews' => $request->editorial_review,
            'about_author' => $request->about_author,
            'additional_info' => $request->add_info,
            'user_id' => Auth::id()
        ]);
    }

    public function tags($id,$tags)
    {
        if($tags <> ''){
            foreach(explode(',',$tags) as $tag)
            {
                ProductTag::create([
                    'product_id' => $id,
                    'tag' => $tag,
                    'created_by' => Auth::id()
                ]);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $parentCategories = ProductCategory::where('parent_id',0)->where('status','PUBLISHED')->get();

        return view('admin.products.edit',compact('product','parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'code' => 'required|max:150',
            'name' => 'required|max:150',
            'category_id' => 'required',
            'uom' => 'required',
            'meta_title' => 'max:60',
            'meta_keyword' => 'max:150',
            'meta_description' => 'max:250',
            'price' => 'required',
            'weight' => 'required',
        ])->validate();

        $product = Product::findOrFail($id);

        if($product->name == $request->name){
            $slug = $product->slug;
        }
        else{
            $slug = \App\Page::convert_to_slug($request->name);
        }

        $product->update([
            'code' => $request->code,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->long_description,
            'currency' => 'PHP',
            'price' => $request->price,
            'reorder_point' => $request->reorder_point,
            'size' => $request->size,
            'weight' => $request->weight,
            'status' => ($request->has('status') ? 'PUBLISHED' : 'PRIVATE'),
            'is_featured' => $request->has('is_featured'),
            'uom' => $request->uom,
            'discount' => $request->discount,
            'is_recommended' => ($request->has('is_recommended') ? 1 : 0),
            'isfront' => ($request->has('isfront') ? 1 : 0),
            'for_pickup' => ($request->has('for_pickup') ? 1 : 0),
            'meta_title' => $request->seo_title,
            'meta_keyword' => $request->seo_keywords,
            'meta_description' => $request->seo_description,
            'created_by' => Auth::id()
        ]);
        
        $this->update_product_additional_info($product->id,$request);
        $this->update_tags($product->id,$request->tags);

        $photos = $this->set_order(request('photos'));
        $this->update_photos($this->get_product_photos($photos));
        $this->remove_photos_from_product(request('remove_photos'));

        $newPhotos = $this->get_new_photos($photos);

        $newPhotos = $this->move_product_to_official_folder($product->id, $newPhotos);

        foreach ($newPhotos as $key => $photo) {
            ProductPhoto::create([
                'product_id' => $product->id,
                'name' => (empty($photo['name']) ? '' : $photo['name']),
                'description' => '',
                'path' => $photo['image_path'],
                'status' => 'PUBLISHED',
                'is_primary' => ($key == $request->is_primary) ? 1 : 0,
                'created_by' => Auth::id()
            ]);
        }

        return back()->with('success', __('standard.products.product.update_success'));
    }

    public function update_product_additional_info($prodID,$request)
    {
        ProductAdditionalInfo::where('product_id',$prodID)->update([
            'synopsis' => $request->synopsis,
            'authors' => $request->authors,
            'materials' => $request->materials,
            'no_of_pages' => $request->no_of_pages,
            'isbn' => $request->isbn,
            'editorial_reviews' => $request->editorial_review,
            'about_author' => $request->about_author,
            'additional_info' => $request->add_info,
            'user_id' => Auth::id()
        ]);
    }

    public function update_photos($photos)
    {
        foreach ($photos as $photo) {
            if ($photo) {
                $photo['name'] = ($photo['name']) ? $photo['name'] : '';
                ProductPhoto::find($photo['id'])->update($photo);
            }
        }
    }

    public function update_tags($id,$tags)
    {
        $delete = ProductTag::where('product_id',$id)->forceDelete();

        if($delete){
            foreach(explode(',',$tags) as $tag){
                ProductTag::create([
                    'product_id' => $id,
                    'tag' => $tag,
                    'created_by' => Auth::id()
                ]);
            }
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_slug(Request $request)
    {
        return Page::convert_to_slug($request->url, $request->parentPage);
    }

    public function restore($id)
    {
        Product::withTrashed()->find($id)->update(['created_by' => Auth::id() ]);
        Product::whereId($id)->restore();

        return back()->with('success', __('standard.products.product.restore_product_success'));
    }

    public function change_status($id,$status)
    {
        Product::where('id',$id)->update([
            'status' => $status,
            'created_by' => Auth::id()
        ]);

        return back()->with('success', __('standard.products.product.update_status_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $product = Product::findOrFail($request->products);
        $product->update([ 'created_by' => Auth::id() ]);
        $product->delete();

        OnSaleProducts::where('product_id',$request->products)->forceDelete();

        return back()->with('success', __('standard.products.product.single_delete_success'));

    }

    public function multiple_change_status(Request $request)
    {
        $products = explode("|", $request->products);

        foreach ($products as $product) {
            $publish = Product::where('status', '!=', $request->status)->whereId($product)->update([
                'status'  => $request->status,
                'created_by' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.products.product.change_status_success', ['STATUS' => $request->status]));
    }

    public function multiple_assign_category(Request $request)
    {
        $products = explode("|", $request->products);

        foreach ($products as $product) {
            Product::whereId($product)->update([
                'category_id'  => $request->categoryid,
                'created_by' => Auth::id()
            ]);
        }

        return back()->with('success', 'Product category of the selected products has been updated.');
    }

    

    public function multiple_delete(Request $request)
    {
        $products = explode("|",$request->products);

        foreach($products as $product){
            Product::whereId($product)->update(['created_by' => Auth::id() ]);
            Product::whereId($product)->delete();

            OnSaleProducts::where('product_id',$product)->forceDelete();
        }

        return back()->with('success', __('standard.products.product.multiple_delete_success'));
    }

// save files to temporary folder
    public function upload(Request $request)
    {
        if ($request->hasFile('banner')) {

            $newFile = $this->upload_file_to_temporary_storage($request->file('banner'));

            return response()->json([
                'status' => 'success',
                'image_url' => $newFile['url'],
                'image_name' => $newFile['name'],
                'image_path' => $newFile['path'],
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'image_url' => '',
            'image_name' => ''
        ]);
    }

    public function get_product_photos($photos)
    {
        return array_filter($photos, function ($photo) {
            return isset($photo['id']);
        });
    }

    public function get_new_photos($photos)
    {
        return array_filter($photos, function ($photo) {
            return !isset($photo['id']);
        });
    }

    public function remove_photos_from_product($photos)
    {
        ProductPhoto::find($photos ?? [])->each(function ($photo, $key) {
            $imagePath = $this->get_banner_path_in_storage($photo->image_path);
            Storage::disk('public')->delete($imagePath);
            $photo->update(['user_id' => auth()->id()]);
            $photo->delete();

        });
    }

    public function upload_file_to_temporary_storage($file)
    {
        $temporaryFolder = 'temporary_products'.auth()->id();
        $fileName = $file->getClientOriginalName();
        if (Storage::disk('public')->exists($temporaryFolder.'/'.$fileName)) {
            $fileName = $this->make_unique_file_name($temporaryFolder, $fileName);
        }

        $path = Storage::disk('public')->putFileAs($temporaryFolder, $file, $fileName);
        $url = Storage::disk('public')->url($path);

        return [
            'path' => $temporaryFolder.'/'.$fileName,
            'name' => $fileName,
            'url' => $url
        ];
    }
//

// move uploaded product files to official product folder
    public function delete_temporary_product_folder()
    {
        $temporaryFolder = 'temporary_products'.auth()->id();
        Storage::disk('public')->deleteDirectory($temporaryFolder);
    }

    public function set_order($products = [])
    {
        $products = $products ?? [];

        $count = 1;
        foreach($products as $key => $product) {
            $products[$key]['order'] = $count;
            $count += 1;
        }

        return $products;
    }

    public function move_product_to_official_folder($productId, $products)
    {
        foreach ($products as $key => $product) {
            $temporaryPath = $this->get_banner_path_in_storage($products[$key]['image_path']);
            $fileName = $this->get_banner_file_name($products[$key]['image_path']);
            $bannerFolder = '';

            $products[$key]['image_path'] = $this->move_to_products_folder($productId, $temporaryPath, $bannerFolder.$fileName);
        }

        return $products;
    }

    public function get_banner_path_in_storage($path)
    {
        $paths = explode('storage/', $path);

        if (count($paths) == 1) {
            return '';
        }

        return explode('storage/', $path)[1];
    }

    public function get_banner_file_name($path)
    {
        $temporaryFolder = 'temporary_products'.auth()->id();
        return explode($temporaryFolder, $path)[1];
    }

    public function move_to_products_folder($id,$temporaryPath, $fileName)
    {
        $folder = 'products/'.$id;
        if (Storage::disk('public')->exists($folder.$fileName)) {
            $fileName = $this->make_unique_file_name($folder, $fileName);
        }

        $newPath = $folder.$fileName;
        Storage::disk('public')->move($temporaryPath, $newPath);
        return $id.$fileName;
    }

    public function make_unique_file_name($folder, $fileName)
    {
        $fileNames = explode(".", $fileName);
        $count = 2;
        $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
        while(Storage::disk('public')->exists($folder.'/'.$newFilename)) {
            $count += 1;
            $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
        }

        return $newFilename;
    }

    public function upload_file_to_storage($folder, $file, $key = '') {

        $fileName = $file->getClientOriginalName();
        if (Storage::disk('public')->exists($folder.'/'.$fileName)) {
            $fileNames = explode(".", $fileName);
            $count = 2;
            $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
            while(Storage::disk('public')->exists($folder.'/'.$newFilename)) {
                $count += 1;
                $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
            }

            $fileName = $newFilename;
        }

        $path = Storage::disk('public')->putFileAs($folder, $file, $fileName);
        $url = Storage::disk('public')->url($path);
        $returnArr = [
            'name' => $fileName,
            'url' => $url
        ];

        if ($key == '') {
            return $returnArr;
        } else {
            return $returnArr[$key];
        }
    }
//


















    public function upload_main(Request $request)
    {

        $csv = array();

        if(($handle = fopen($request->csv, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;
            while(($data = fgetcsv($handle, 5000, ',')) !== FALSE) {
                $row++;
                // number of fields in the csv
                $col_count = count($data);
                if($row > 1){
                    $product = Product::where('beta_id',$data[0])->count();

                    if($product > 0){

                    } else {
                        $product = Product::create([
                            'beta_id' => $data[0],
                            'name' => $data[1],
                            'slug' => $data[2],
                            'price' => $data[3],
                            'discount' => $data[4],
                            'weight' => $data[5],
                            'size' => $data[8],
                            'description' => $data[14],
                            'category_id' => 0,
                            'currency' => 'PHP',
                            'status' => ($data[18] == 'TRUE') ? 'PUBLISHED' : 'PRIVATE',
                            'reorder_point' => 0,
                            'for_pickup' => ($data[19] == 1) ? 1 : 0,
                            'is_recommended' => ($data[16] == 'TRUE') ? 1 : 0,
                            'isfront' => 0,
                            'photo' => $data[15],
                            'created_by' => 1,
                        ]);

                        if($product){
                            ProductAdditionalInfo::create([
                                'product_id' => $product->id,
                                'authors' => $data[6],
                                'isbn' => $data[7],
                                'materials' => $data[9],
                                'no_of_pages' => $data[10],
                                'sypnosis' => $data[11],
                                'editorial_reviews' => $data[12],
                                'about_author' => $data[13],
                            ]);

                            Product::find($product->id)->update([
                                'code' => 'PR'.str_pad($product->id, 6, '0', STR_PAD_LEFT)
                            ]);
                        }
                    }
                    
                }


            }
            fclose($handle);
        }

        return back()->with('success','Products uploaded successfully.');

    }

    public function upload_category(Request $request)
    {

        $csv = array();

        if(($handle = fopen($request->csv, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;
            while(($data = fgetcsv($handle, 5000, ',')) !== FALSE) {
                $row++;
                // number of fields in the csv
                $col_count = count($data);
                if($row > 1){

                    $qry = Product::where('beta_id',$data[1]);

                    if($qry->count() > 0){

                        $qry->update(['category_id' => $data[0]]);
                    }
                    
                }
            }
            fclose($handle);
        }

        return back()->with('success','Product category has been updated.');
    }

    public function upload_photos(Request $request)
    {

        $csv = array();

        if(($handle = fopen($request->csv, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;
            while(($data = fgetcsv($handle, 5000, ',')) !== FALSE) {
                $row++;
                // number of fields in the csv
                $col_count = count($data);
                if($row > 1){

                    $qry = Product::where('beta_id',$data[0]);

                    if($qry->count() > 0){
                        $product = $qry->update(['photo' => $data[1]]);
                    }
                    
                }


            }
            fclose($handle);
        }

        return back()->with('success','Product photo has been updated.');

    }

    public function upload_images(Request $request)
    {   
        foreach($request->file('images') as $file)
        {
            $qry = Product::where('photo',$file->getClientOriginalName());

            if($qry->count() > 0){
                $product = $qry->first();

                ProductPhoto::create([
                    'product_id' => $product->id,
                    'name' => $file->getClientOriginalName(),
                    'description' => 'product photo',
                    'path' => $product->id.'/'.$file->getClientOriginalName(),
                    'status' => 'PUBLISHED',
                    'is_primary' => 1,
                    'created_by' => Auth::id()
                ]);

                Storage::makeDirectory('/public/products/'.$product->id);
                Storage::putFileAs('/public/products/'.$product->id, $file, $file->getClientOriginalName());
            }
        }

        return back()->with('success','Product images has been uploaded.');

    }

    public function upload_additional(Request $request)
    {
        $csv = array();

        if(($handle = fopen($request->csv, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;
            while(($data = fgetcsv($handle, 5000, ',')) !== FALSE) {
                $row++;
                // number of fields in the csv
                $col_count = count($data);
                if($row > 1){

                    $qry = Product::where('beta_id',$data[0])->first();

                    ProductAdditionalInfo::create([
                        'product_id' => $product->id,
                        'authors' => $data[6],
                        'isbn' => $data[7],
                        'materials' => $data[9],
                        'no_of_pages' => $data[10],
                        'sypnosis' => $data[11],
                        'editorial_reviews' => $data[12],
                        'about_author' => $data[13],
                    ]);
                }


            }
            fclose($handle);
        }

        return back()->with('success','Products additional info has been uploaded.');
    }

    public function upload_featured(Request $request)
    {
        $csv = array();

        if(($handle = fopen($request->csv, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;
            while(($data = fgetcsv($handle, 5000, ',')) !== FALSE) {
                $row++;
                // number of fields in the csv
                $col_count = count($data);
                if($row > 1){
                    $product = Product::where('beta_id',$data[0])->update(['is_featured' => 1]);
                }


            }
            fclose($handle);
        }

        return back()->with('success','Feautured product has been updated.');
    }

    public function upload_customers(Request $request)
    {
        $csv = array();

        if(($handle = fopen($request->csv, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;
            while(($data = fgetcsv($handle, 5000, ',')) !== FALSE) {
                $row++;
                // number of fields in the csv
                $col_count = count($data);
                if($row > 1){

                    $qry = User::where('email',$data[1]);

                    if($qry->count() == 0){
                        $customer = User::create([
                            'name' => $data[3].' '.$data[4],
                            'email' => $data[1],
                            'firstname' => $data[3],
                            'lastname' => $data[4],
                            'email_verified_at' => NULL,
                            'password' => $data[2],
                            'role_id' => 3,
                            'is_active' => $data[9],
                            'user_id' => 1,
                            'remember_token' => str_random(60),
                            'fromMigration' => 1
                        ]);

                        if($customer){
                            Customer::create([
                                'firstname' => $data[3],
                                'lastname' => $data[4],
                                'email' => $data[1],
                                'address' => $data[5],
                                'city' => $data[6],
                                'province' => $data[7],
                                'is_active' => $data[9],
                                'user_id' => 1,
                                'customer_id' => $customer->id,
                                'country' => $data[8]

                            ]);
                        }
                        
                    }
                    
                }


            }
            fclose($handle);
        }

        return back()->with('success','Product photo has been updated.');

    }
}
