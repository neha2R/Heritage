<?php

namespace App\Http\Controllers;

use App\Product;
use App\Product_images;
use App\Product_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_categories=Product_categories::where('status','1')->get();
        $products=Product::all();
        return view('product.list',compact('products','product_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {

        $validator=$req->validate([
            'name'=>'required',
            'price'=>'required',
            'link'=>'required',
            'description'=>'required|max:200',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
            'category_id'=>'required'
        ]);
       
        
      
        $product = new Product;
        $product->name=$req->name;
        $product->category_id=$req->category_id;
        $product->price=$req->price;
        $product->link=$req->link;
        $product->description=$req->description;
        $product->save();

        if($req->hasfile('images'))
        {
                foreach($req->file('images') as $key=>$file)
                {
                    
                        $type = '0';
                        $name = $file->store('product','public');
                        $image = new Product_images;
                        $image->product_id = $product->id;
                        $image->image = $name;
                        $image->save();
                    
                   
                }
     
       }

       return redirect()->back()->with('success',"You have created product successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product=Product::whereId($id)->first();
        if ($product->status == '1') {
            $product->status = '0';
        } else {
            $product->status = '1';

        }
        $product->save();

        if ($product->id) {
            return redirect()->back()->with(['success' => 'Status updated Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        
        $validator=$req->validate([
            'name'=>'required',
            'price'=>'required',
            'link'=>'required',
            'description'=>'required|max:200',
            'images.*' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
            'category_id'=>'required'
        ]);
       
        $product = Product::whereId($id)->first();
        $product->name=$req->name;
        $product->category_id=$req->category_id;
        $product->price=$req->price;
        $product->link=$req->link;
        $product->description=$req->description;
        $product->save();


        
     
        if(isset($req->old_images) && count($req->old_images)>0)
       {   
              if(Product_images::where('product_id',$id)->first())
              {
                Product_images::where('product_id',$id)->delete();
              }
                    foreach($req->old_images as $key=>$file)
                    {
                        $name = $file;
                        $image = new Product_images;
                        $image->product_id = $product->id;
                        $image->image = $name;
                        $image->save();
                    }
        }
        else
        {
            if(Product_images::where('product_id',$id)->first())
              {
                Product_images::where('product_id',$id)->delete();
              }
        }

        if($req->hasfile('images'))
        {
                foreach($req->file('images') as $key=>$file)
                {
                    $type = '0';
                    $name = $file->store('product','public');
                    $image = new Product_images;
                    $image->product_id = $product->id;
                    $image->image = $name;
                    $image->save();
                }
     
       }  

       return redirect()->back()->with('success',"You have updated product successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product = Product::find($product->id);

        if ($product) {
            $product->delete();
            
        }

        if ($product->id) {
            return redirect()->back()->with(['success' => 'Product Deleted Successfully']);
        } else {
            return redirect()->back()->with(['error' => 'Something Went Wrong Try Again Later']);
        }
    }


    //api function 

    public function get_all_products(Request $req)
    {
        if ($req->search!="") {
            $str = $req->search;
            $products=Product::where('name', 'like', '%' . $str . '%')->get();
        }
        else
        {
          
            $products=Product::where('status','1')->get();
        }
        
       
        $data=[];
        if(!empty($products))
        {
            foreach($products as $product)
            {
                   $productData['category']=ucwords(strtolower($product->category->name));
                   $productData['name']=ucwords(strtolower($product->name));
                   $productData['price']="₹ ".$product->price;
                   $productData['description']=ucwords(strtolower($product->description));
                   $productData['link']=ucwords(strtolower($product->link));
    
                   $i=1;
               
                   if(count($product->images)>0)
                   {
                    $productData['images']=$this->get_files($product->images);
                   }
                   else
                   {
                    $productData['images']="";
                   }
                   $data[]=$productData;
                        
            }
        }
        else
        {
            return response()->json(['status' => 200, 'message' => 'no product found.', 'data' => array()]);
        }
        
        if(empty($data)){
            return response()->json(['status' => 200, 'message' => 'no product found.', 'data' => array()]);
        }
        return response()->json(['status' => 200, 'message' => 'Products Found','data' => $data]);

    }
    public function get_files($files)
    {
        $productImages=[];
        foreach($files as $image)
        {
            $productImages[]=url('/storage').'/'.$image->image;
        }
        return $productImages;
    }
    // public function product_search(Request $req)
    // {
    //     if ($req->has('search')) {
    //         $str = $req->search;
    //     }

    //     $products=Product::where('name', 'like', '%' . $str . '%')->get()->toArray();
    //     if(empty($products)){
    //         return response()->json(['status' => 200, 'message' => 'no product found.', 'data' => '']);
    //     }
    //     return response()->json(['status' => 200, 'message' => 'Products Found','data' => $products]);

    // }
}
