<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Pagination\Paginator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $product_all = Product::all();
        foreach ($product_all as $key => $value) {
            $array_images = json_decode(html_entity_decode($product_all[$key]->product_image));
    
            //bikin array of basepath, di loop dulu si $array_images
            $array_images_basepath = [];
            foreach ($array_images as $keyImages => $value) {
                $path = base_path('IMAGES\product').'/'.$array_images[$keyImages]; 
                $array_images_basepath[] = $path;
            }
            // asign array to product All
            $product_all[$key]->product_image = $array_images_basepath; //pas iterasi kedua gagal
        };
       
        return $product_all;

    }

     /**
     * Display a listing of the resource paginate.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagination($pagination, $page){
        // set page untuk pagination
        // $page = 2;
        Paginator::currentPageResolver(function () use($page) {
            return $page;
        });
        //kalau mau pake paginate
        $product_paginate = Product::paginate(1);

        // loop product paginate
        foreach ($product_paginate as $key => $value) {
            $array_images = json_decode(html_entity_decode($product_paginate[$key]->product_image));
    
            //bikin array of basepath, di loop dulu si $array_images
            $array_images_basepath = [];
            foreach ($array_images as $keyImages => $value) {
                $path = base_path('IMAGES\product').'/'.$array_images[$keyImages]; 
                $array_images_basepath[] = $path;
            }
            // asign array to product All
            $product_paginate[$key]->product_image = $array_images_basepath; //pas iterasi kedua gagal
        };

        return $product_paginate; 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        // echo $request;
        //var_dump((int)$request->price);
        

        // NANGKEP FILE UPLOAD WITH LARAVEL: 
            // SOURCE METHOD: https://api.symfony.com/3.0/Symfony/Component/HttpFoundation/File/UploadedFile.html
        //var_dump($request->image->path()); //array associative
        //var_dump($request->product_image[0]->extension());
        //var_dump($request->product_image[0]->getClientOriginalName());
        //var_dump($request->product_image[0]->extension());
        
        // CARA MANUAL
        // $imageType = $_FILES['image']['type'];
        // $imageName = $_FILES['image']['name'];
        // $imageSize = $_FILES['image']['size'];
        // $image_tmp_name = $_FILES['image']['tmp_name'];
        // $imageError = $_FILES['image']['error'];

            //image yang ke-1
            $image1_name = $_FILES['product_image']['name'][0];
            //image yang ke-2
            $image2_name = $_FILES['product_image']['name'][1];

            
            //looping untuk image name, langsung save di folder
            $array_product_name = [];
            foreach($_FILES['product_image']['tmp_name'] as $key=>$tmp_name){

                $file_original_name = $request->file('product_image')[$key]->getClientOriginalName();
                $file_original_extension = $request->file('product_image')[$key]->extension();

                $newImageFileName = $request->owner_id
                                    .'_'.
                                    $request->product_name
                                    .'_'.
                                    ($key+1)
                                    // .'_'.
                                    // $file_original_name;
                                    .'.'.
                                    $file_original_extension;
                
                $array_product_name[] = $newImageFileName;

                $path = base_path('IMAGES\product');
                // $path = public_path();
                
                //$image_tmp_name = $_FILES['product_image']['tmp_name'][$key];
                // move_uploaded_file($image_tmp_name, base_path('IMAGES\product').'/'.$newImageFileName);
                $file = $request->file('product_image')[$key];
                $file->move( $path , $newImageFileName);
            };
            
        $string_array_product_name = json_encode($array_product_name); // untuk encode dari PHP array ke string
        //var_dump(json_decode($new_product_image_name)); // untuk decode dari string ke PHP array
        $new_product_image_name = $string_array_product_name;
        
        
        $product = new Product;
        $product->product_name = htmlspecialchars($request->product_name);
        $product->product_image = htmlspecialchars($new_product_image_name); //bukan dari html
        $product->category = (int)htmlspecialchars($request->category);
        $product->price = (int)htmlspecialchars($request->price);
        $product->description = htmlspecialchars($request->description);
        var_dump(json_decode(html_entity_decode($product->product_image))); // kalo di panggil dari html, biar ga error
        // var_dump(json_decode($product->product_image));
        $product->save();
        die;

        return "Data successfully created";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if(!$product){
            return "Product not found";
        }

        // $getTypeProductImage = gettype(json_decode(html_entity_decode($product->product_image)));
        $array_images = json_decode(html_entity_decode($product->product_image));
        $array_images_basepath = [];

       
        //bikin array of basepath, di loop dulu si $array_images
        foreach ($array_images as $key => $value) {
            $path = base_path('IMAGES\product').'/'.$array_images[$key];
            $array_images_basepath[] = $path;
        }

        //ganti product image, sama array of path product image
        $product->product_image = $array_images_basepath;
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) //pake POST aja, belum ada solution buat PUT
    {
   
        $product = Product::find($id);
        if(!$product){
            return "User not Found";
        };
        echo "ini product awalnya: ";
        var_dump($product->product_image);
        echo "<br>";
        
        //HANDLE IMAGE 
        //looping untuk image name, langsung save di folder
        $array_product_name = [];
        foreach($_FILES['product_image']['tmp_name'] as $key=>$tmp_name){

            $file_original_name = $request->file('product_image')[$key]->getClientOriginalName();
            $file_original_extension = $request->file('product_image')[$key]->extension();

            $newImageFileName = $request->owner_id
                                .'_'.
                                $request->product_name
                                .'_'.
                                ($key+1)
                                // .'_'.
                                // $file_original_name;
                                .'.'.
                                $file_original_extension;
            
            $array_product_name[] = $newImageFileName;

            $path = base_path('IMAGES\product');
            // $path = public_path();
            
            //$image_tmp_name = $_FILES['product_image']['tmp_name'][$key];
            // move_uploaded_file($image_tmp_name, base_path('IMAGES\product').'/'.$newImageFileName);
            $file = $request->file('product_image')[$key];
            $file->move( $path , $newImageFileName);
        };
        
    $string_array_product_name = json_encode($array_product_name); // untuk encode dari PHP array ke string
    //var_dump(json_decode($new_product_image_name)); // untuk decode dari string ke PHP array
    $new_product_image_name = $string_array_product_name;
    var_dump($new_product_image_name);
    echo "<br>wawawaw<br>";
    var_dump($product->product_image);
    

        //put request cannot be used with multipart form data yet
        // multipartform data is used to upload an edited image
        $product_name = htmlspecialchars($request->product_name);
        $product_image = htmlspecialchars($new_product_image_name);
        $category = (int)htmlspecialchars($request->category);
        $price = (int)htmlspecialchars($request->price);
        $description = htmlspecialchars($request->description);
        
        // var_dump($request->product_name);
        $product = Product::find($id);
        $product->product_name = $product_name;
        $product->product_image = $product_image;
        $product->category = $category; 
        $product->price = $price;
        $product->description = $description;
        
        echo "<br>";
        echo "ini product akhirnya: <br>";
        // var_dump($product);
        echo $product;
        echo "<br>";
        
        $product->save();

        return "Data successfully updated";
        // return Product::find($id);

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
        $product = Product::find($id);
        if(!$product){
            return "User not Found";
        };

        // ketika delete, termasuk delete file juga berarti
        
        //delete file
        $array_images = json_decode(html_entity_decode($product->product_image));
        foreach ($array_images as $key => $value) {
            $path = base_path('IMAGES\product').'/'.$array_images[$key];
            File::delete($path);
        }
        //delete database
        $product->delete();

        return "Data successfully deleted";
    }
}
