<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Cart;
use Illuminate\Support\Facades\DB;
use App\order_product;
use App\Order;
use App\cart_product;
use App\Events\Qty;
use App\Http\Requests\checkoutRequest;
use App\Product;
use Carbon\Traits\Cast;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Else_;
use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use App\Mail\DemoEmail;
use Illuminate\Support\Facades\Mail;
use App\User;
class UserCartcontroller extends Controller

{
    public function index1()
    {

        $products = DB::table('product')->get();

        return view('index', compact('products'));
    }
 
    public function history(Request $request)
    {
       
        $user=$request->session()->get('key');
        if(empty($user))
            return view('user.watchorder');
        else
        {
          
            $order=Order::where(['user_id'=>$user->id])
            ->where('status', 'not like', '0')->
            get();
           
            return view('user.watchorder',['myOrder'=>$order]);
        }
    }
    public function historyview(Request $request,$id)
    {
      
        $user=$request->session()->get('key');
        $order=Order::find($id);
        if(empty($user))
            return view('user.watchorder');
        else if(!empty($order)&&($order->user_id!=$user->id))
        {
            return view('user.watchdetailorder');
        }
      
        else
        {
         

            return view('user.watchdetailorder',['myViewOrder'=>$order]);
        }
    }

    public function livesearch(Request $request)
    {


           $output = '';
           if($request->search!="")
          { $products=Product::where(['status'=>"1"])
           ->join('detail_product','detail_product.product_id','=','product.id') ;
           $products = $products->where('name', 'LIKE', '%' . $request->search . '%')->get();
         
           if ($products) {
             
                foreach ($products->take(6) as $key => $product) {
                  
                    $output .= 
                    '<tr onclick="Redirectlivesearch(this)" class="search_items">
                    <td hidden class="search_name">' . $product->slug . '</td> 
                    <td  style="width:50px" >
                    <img  height="50px" width="50px"  src="/images/'.$product->image.'"/>
                    </td> 
                    <td >' . $product->name .
                    '<br/>' . $product->price . "??".'</td>
                  
                   </tr>';
              }
              $resultOuput ="";
              
              $resultOuput.= $output.'<tr><td colspan="2" onclick="searchSubmit()">Hi???n k???t qu??? t??m ki???m cho '.$request->search.'</td></tr>';
           if($products->count()==0)
            {
                $resultOuput="<tr><td> kh??ng c?? k???t qu??? b???n t??m ki???m<td></tr>";
            }
              return Response::json(array(
                'status'=> $resultOuput,
               
                
              
       )); 
    }
        }
    }
    public function postDiaChiCheckOut(checkoutRequest $request)
    {   
        $name=$request->name;
        $phone=$request->phone;
        $add=$request->address;
        return Response::json(array(
            'name'=>$name,
            'phone'=>$phone,
            'address'=>$add

         ));
    }
    public function cart(Request $request)
    {

        $value=$request->session()->get('key');

        //check user da dang nhap neu chua quya lai login
        if(empty($value))
        {
          //  return redirect()->action('loginController@index');

          return view('user.cart_detail');

        }
        else
        {
            $user_id=$value->id;
            //l???y gi??? h??ng c???a user_id
            $orders=Order::where(['user_id'=>$user_id,'status'=>'0'])->first();

            if(!empty($orders))
            {

              // $products=Order::find($orders->id)->product;
               //n???u c?? s???n ph???m khi show s???n ph???m trong gi??? h??ng c???a user
               /// if(count($products)>0)
                    return view('user.cart_detail')->with(['orders'=>$orders]);
                //echo $products;
               // else
                //return view('user.cart_detail');
            }
            else
            {
                return view('user.cart_detail');
            }


         }


       // return redirect()->action('Admincontroller@index');

    }
    public function index( Request $request){

        $value=$request->session()->get('key');
        return view('admin.index')->with('id',$value);
    }
    public function checkout(Request $request)
    {
        return view('checkout');
    }
    public function getOrder(Request $request)
    {
        //N???u user thay ?????i gi??? h??ng th?? return status ??? json

            $name=$request->name;
            $phone=$request->phone;
            $add=$request->address;

            $product_update=$request->product_update;
            if($name==null || $phone==null||$add==null)
            {
                return abort('404');
            }

            $user_id= $request->session()->get('key');

            //T??m order ??ang ?????t c???a user hi???n t???i

            $orders=Order::where(['user_id'=>$user_id->id,'status'=>'0'])->first();

            if(empty($orders))
            {
                return Response::json(array(
                        'status'=>'gi??? h??ng c???a b???n tr???ng',

               ));
            }

            //chuy???n tr???ng th??i sang ???? ?????t h??ng
            $orders_product=array();
            if(!empty($orders))
           {


                foreach($orders->product as $products)
                {
                    if($products->status=="1")

                    array_push($orders_product,$products->pivot->updated_at->toDateTimeString());

                }

                if(!empty($orders_product))
                {

                   if($product_update=="")
                   {
                    return Response::json(array(
                        'status'=> 'thay ?????i'
                        ));
                   }
                    $a=array_diff($product_update,$orders_product);
                    if(!empty($a))
                        return Response::json(array(
                        'status'=> 'thay ?????i'
                    ));

                }
                if(empty($orders_product))
                {

                    if($product_update!="")
                   {
                        return Response::json(array(
                        'status'=>   'thay ?????i',
                        ));
                    }
                    else
                    {
                        return Response::json(array(
                            'status'=>   'gi??? h??ng c???a b???n tr???ng',
                            ));
                    }
                }
                order_product::where
                (['order_id'=>$orders->id ])->
                 join('product','order_product.product_id','=','product.id')
                 ->where(['product.status'=>"0"])
                 ->delete();
                $orders->name=$name;
                $orders->address=$add;

                $orders->phone=$phone;
                $orders->status="1";
                $orders->date=Carbon::now();

                $orders->save();

                $objDemo = new \stdClass();
                $objDemo->order =  $orders;
                $user=User::find($orders->user_id);
                $objDemo->user =  $user;

                 Mail::to($user->email)->send(new DemoEmail($objDemo));
                //x??a   order_product ???? h???t h??ng trong gi??? h??ng


            }





            /* if(!empty(array_diff($product_update,$orders_product)))
            return Response::json(array(
            'status'=>   'thay ?????i',

            ));*/





    }
    /*   public function getUpdateCart(Request $request)
    {
        $request->session()->put('change','update');
        $order_id=$request->order_id;
        $product_id=$request->product_id;
        $c=$request->qty;

        if($product_id=="" )
                return abort('404');
        //Khi h???y session cart v?? ch??a ????ng nh???p
        if(empty($order_id) && !$request->session()->has('cart'))
            {
                 return Response::json(array(
                     'status'=>'no',

            ));
        }
        if($c>10 ||$c<0 ||empty($c))
        $request->session()->put('qty','qty ph???i t??? 0 t???i 10 v?? kh??ng ???????c tr???ng');
        else
        {
            $product=Product::find($product_id);
        //t??m order trong gi??? h??ng hi???n t???i
            if($request->session()->get('cart'))
            {
                $cart=new Cart(session()->get('cart'));
                 //n???u item kh??ng t???n t???i
                if(!isset($cart->items[$product_id]))
                return Response::json(array(
                'status'=>'no',

            ));
            //Khi c??n session cart nh??ng user v???n ????ng nh???p ??? tab kh??c
                if(!empty($a))
                {
                return Response::json(array(
                 'status'=>'no',

                ));
                }
                $cart->update1($product,$c);
                $request->session()->put('cart',$cart);

            }

            else
            {


                 $p = order_product::where
                ([
                'order_id'=>$order_id,
                'product_id'=>$product_id

                ])->first();
             //Khi tr???ng s???n ph???m ho???c ????ng nh???p v???i id kh??c
                $user=Order::find($order_id)->user_id;
                if($request->session()->has('key'))
                {

                    $a=$request->session()->get('key')->id;
                    //N???u order m???i c???p nh???p

                }
                else
                {
                        $a=null;
                         if(empty($p)||$a!=$user )
                         return Response::json(array(
                'status'=>'no',

                 ));
                }

                order_product::where
                ([
                 'order_id'=>$order_id,
                'product_id'=>$product_id
                ])->update(['qty' => $c,'amount'=>Product::find($product_id)->price*$c]);
                //Update l???i t???ng gi?? c???a ????n h??ng ????
                $c=Order::find($order_id);
                $c->total=order_product::where
                ([
                    'order_id'=>$order_id

                ])->sum('amount');
                 $c->save();


            }
        }
    }*/
    public function getUpdateCart(Request $request)
    {

        $order_id=$request->order_id;
        $product_id=$request->product_id;
        $c=$request->qty;
        $time_create=$request->timecreate;

        
        if($product_id=="" )
                return abort('404');
       //khi gi??? h??ng ??? tab hi???n t???i tr???ng(tab hi???n t???i ch??a login) v?? user ch??a ????ng nh???p  tab kh??c
        if(empty($order_id) &&!$request->session()->has('key') && !$request->session()->has('cart'))
        {

                 return Response::json(array(
                     'status'=>'no1',
           ));
        }

        //khi gi??? h??ng ??? tab hi???n t???i tr???ng(tab hi???n t???i ???? login) v?? user ????ng xu???t b??n tab kh??c
        if(!empty($order_id) && !$request->session()->has('key'))
            {
                 return Response::json(array
                 (
                     'status'=>'no2',

           ));
        }

        $product=Product::find($product_id);
        //t??m order trong gi??? h??ng hi???n t???i
        if($request->session()->get('cart'))
        {
            
            $cart=new Cart(session()->get('cart'));

            if(isset($cart->items[$product_id]))
            {
                   $status=Product::find($cart->items[$product_id]['id'])->status;

            }
            //n???u items kh??ng  ho???t ?????ng ho??c ,tab hi???n t???i c?? s???n ph???m v?? v???a x??a b??n tab kh??c
           if(!isset($cart->items[$product_id])||$status=="0")
            return Response::json(array(
                'status'=>'no3',

            ));

            //Th???i gian mua s???n ph???m tab hi???n t???i v?? tab kh??c kh??ng gi???ng nhau
            if($time_create!=$cart->items[$product_id]['time_at'])
            return Response::json(array(
                 'status'=>'no6'
            ));
            $sum=0;
            $totalQty=0;
            $beforechange=$cart->items[$product_id]['qty'];
            $cart->update1($product,$c);
            foreach($cart->items as $item)
            {
                if(!empty(Product::find($item['id']))&&Product::find($item['id'])->status=="1")
                {       
                    $totalQty+=$item['qty'];
                    $sum+=Product::find($item['id'])->price*$item['qty'];
                }
            }
            if($totalQty>100)
                return Response::json(array(
                'status'=> 't???i ??a' ,
                'qty'=> $beforechange

            ));
            else
            {
                $request->session()->put('cart',$cart);
                 return Response::json(array(
                'total'=> $sum ,
                ));
            }

        }

        if($request->session()->has('key'))
        {
            
            $a= $request->session()->get('key')->id;

            //L???y id order m???i ho???c id order c???a user kh??c
            if(!empty($order_id))

            {
                $user_id=Order::where(['id'=>$order_id])->first()->user_id;
                //N???u user t???o ????n m???i kh??c ????n tab hi???n t???i ho???c ????ng nh???p v???i id kh??c
                if($user_id!=$a )
                return Response::json(array(
                'status'=>'no4' ));
            }

           
            //Tr?????ng h???p th??? 8
            //Khi mua h??ng l??c ch??a ????ng nh???p empty($order_id)
            if(empty($order_id))
            {
                //H??a ????n session hi???n t???i
                $order=Order::where(['user_id'=>$a,'status'=>'0'])->first();
                
                if(!empty($order))
                {
                    $order_id= $order->id;

                }
                //????n h??ng c??n ??? session cart
                $old_order_id="have";

            }
            else
            {
                $old_order_id="";
            }
            //H???t tr?????ng h???p 8
           
            $p = order_product::where
            ([
                'order_id'=>$order_id,
                'product_id'=>$product_id
            ])->first();

            //Khi tab hi???n t???i c??n s???n ph???m, tr???ng s???n ph???m ??? tab kh??c ho???c s???n ph???m kh??ng ho???t ?????ng
            if(empty($p) || Product::find($product_id)->status=="0")
                return Response::json(array(
                'status'=>'no5',
            ));
            $order_status=Order::find($order_id)->status;
            //Tr?????ng h???p 9 khi ???? ?????t h??ng v?? tab hi???n t???i trong gi???
            if( $order_status!=0)
            return Response::json(array(
                'status'=>'no9'
            ));
            /*khi c?? s???n ph???m ??? tab hi???n t???i(l??c ch??a ????ng nh???p), ????ng nh???p ???
             tab kh??c th?? s??? l??u th???i gian
            t???o s???n ph???m c???a session v???i user hi???n t???i, n???u user kh??c ????ng nh???p 
            ho???c th???i gian t???o s???n ph???m kh??c th?? kh??ng t??m th???y item*/
            if($old_order_id)
            {
               if(($p->created_at->timestamp+3600*7)!=$time_create )
                return Response::json(array(
                 'status'=>'no8'
                ));
            }
             //Th???i gian mua s???n ph???m tab hi???n t???i v?? tab kh??c kh??ng gi???ng nhau
            if($old_order_id=="")
            {

                if($p->created_at!=\Carbon\Carbon::parse($time_create) )
                return Response::json(array(
                     'status'=>'no7'
                ));
            }
            //s??? l?????ng mua t???i ??a l?? 10
           
            //update order_product
             /*  order_product::where
             ([
            'order_id'=>$order_id,
            'product_id'=>$product_id
             ])->update(['qty' => $c,'amount'=>Product::find($product_id)->price*$c]);
            //Update l???i t???ng gi?? c???a ????n h??ng ????
             $carts=Order::find($order_id);
            
            /* $total=order_product::where
             (['order_id'=>$order_id ])->
             join('product','order_product.product_id','=','product.id')
             ->where(['product.status'=>"1"])
             ->sum('amount');
             $cart->total=$total;
             $cart->save();*/
             $carts=Order::find($order_id);
             $totalPrice=0;
             $totalQty=0;
             $qty_beforechange=0;
             foreach($carts->product as $items)
             {    
                if($items->id!=$product_id)
                { 
                    $totalPrice+=$items->pivot->price*$items->pivot->qty;
                    $totalQty+=$items->pivot->qty;
                }
                else
                {
                    $qty_beforechange=$items->pivot->qty;
                    $totalPrice+=$items->pivot->price*$c;
                    $totalQty+=$c;
                }
             }
             if($totalQty>100)
                 return Response::json(array(
             'status'=>'t???i ??a',
             'qty'=> $qty_beforechange
             ));
             else
             {
                order_product::where
                ([
               'order_id'=>$order_id,
               'product_id'=>$product_id
                ])->update(['qty' => $c,'amount'=>Product::find($product_id)->price*$c]);
               //Update l???i t???ng gi?? c???a ????n h??ng ????
               $carts->total=$totalPrice;
               $carts->save();
                    
                return Response::json(array(
                    'total'=>$carts->total  ,
    
                ));
             }
         

        }
    }

    public function delete(Request $request) {

        //$request->session()->put('change','update');
        $product_id=$request->product_id;
        $order_id=$request->order_id;
        $time_create=$request->timecreate;
        if( $product_id=="")
            return abort('404');

        $product=Product::find($product_id);
         //Khi v???a ????ng nh???p ??? tab kh??c ho???c cart tr???ng v?? ch??a ????ng nh???p
        if(empty($order_id) &&  !$request->session()->has('key') && !$request->session()->has('cart'))
        {
         return Response::json(array(
             'status'=>'no1',

            ));
        }

        //khi ????ng xu???t ??? tab kh??c v?? tab hi???n t???i ch??a ????ng xu???t
        if(!empty($order_id)  && !$request->session()->has('key'))
        {
             return Response::json(array(
                 'status'=>'no2',
          ));
        }
        if($request->session()->has('cart'))
        {

            $cart=new Cart(session()->get('cart'));
            //n???u item kh??ng t???n t???i
            if(!isset($cart->items[$product_id]))
                return Response::json(array(
                'status'=>'no3',

               ));

            //Tr?????ng h???p th???i gian order item kh??c v?? c?? session cart
            if($time_create!=$cart->items[$product_id]['time_at'])
               return Response::json(array(
                    'status'=>'no6'
               ));

            $cart->delete1($product);
            $request->session()->put('cart',$cart);

            //N???u user ???? x??a h???t s???n ph???m trong gi??? h??ng th?? h???y session cart
            if(empty($cart->items))
            $request->session()->forget('cart');

            $sum=0;
            foreach($cart->items as $item)
            {

                if(!empty(Product::find($item['id']))&&Product::find($item['id'])->status=="1")
                {
                    $sum+=Product::find($item['id'])->price*$item['qty'];
                }
            }
            return Response::json(array(
                'total'=> $sum ,

            ));
        }

        if($request->session()->has('key'))
        {

            $a=  $request->session()->get('key')->id;
            //L???y id order m???i ho???c id order c???a user kh??c
           // $new_order=Order::where(['user_id'=>$a,'status'=>'0'])->first();



           if(!empty($order_id))
            {

               $user_id=Order::where(['id'=>$order_id])->first()->user_id;
               //N???u order m???i c???p nh???p ho???c ????ng nh???p v???i id kh??c
               if($user_id!=$a )
               return Response::json(array(
               'status'=>'no4' ));

            }

            //Khi s???n ph???m c?? b??? tr???ng
            if(empty($order_id))
            {
                $order=Order::where(['user_id'=>$a,'status'=>'0'])->first();
                if(!empty($order))
                {
                    $order_id= $order->id;

                }
                $old_order_id="have";
            }
            else
            {
                $old_order_id="";
            }
            $p = order_product::where
            ([
                'order_id'=>$order_id,
                'product_id'=>$product_id

            ])->first();
             //Khi tr???ng s???n ph???m

            if(empty($p))
                return Response::json(array(
                'status'=>'no5',
                ));
            $order_status=Order::find($order_id)->status;
            //Tr?????ng h???p 9 khi ???? ?????t h??ng v?? tab hi???n t???i trong gi???
             if( $order_status!=0)
            return Response::json(array(
                    'status'=>'no9'
             ));

            if($old_order_id)
            {
                if(($p->created_at->timestamp+3600*7)!=$time_create )
                return Response::json(array(
                     'status'=>'no8'
                    ));
            }
            if($old_order_id=="")
            {
                if($p->created_at!=\Carbon\Carbon::parse($time_create) )
                return Response::json(array(
                         'status'=>'no7'
                ));
            }
            $c=Order::where(['id'=>$order_id,'status'=>"0"])->first();

            //s???n ph???m ch??a h???t h??ng ho???c c??n ho???t ?????ng th?? tr??? ??i gi?? s???n ph???m ????
           /* if($p->status=="1")
            {
                $c->total=$c->total-$p->amount;

                $c->save();

            }
          */
          if(Product::find($p->product_id)->status=="1")
          { $c->total=$c->total-$p->amount;

          $c->save();

            }

                 //X??a order trong gi??? h??ng hi???n t???i
            $order_product= order_product::where
            ([
                'order_id'=>$order_id,
                'product_id'=>$product_id
            ])->delete();

            return Response::json(array(
                    'total'=>$c->total  ,

                ));
        }


    }
    public function addCart(Request $request){

       //  $request->session()->put('change','update');
        $id=$request->product_id;
        if($id=="")
            return abort('404');
        $product=Product::find($id);
        $user= $request->session()->get('key');
        if(empty($user))
        {
            if($request->session()->get('cart'))
            {
                $cart=new Cart(session()->get('cart'));
                $totalQty=0;
                foreach($cart->items as $items)
                    $totalQty+= $items['qty'];
                if($totalQty>99)
                    return Response::json(array(
                    'status'=>'s??? l?????ng trong gi??? h??ng ???? ?????t t???i ??a',
       
                ));
            }
            else
            {
               $cart=new Cart();
            }
            $cart->add($product);
            $request->session()->put('cart',$cart);

            /*
            if(isset($cart->items[$id]))
            {
                if($cart->items[$id]['qty']<10 )
                {
                    $cart->add($product);
                }
                 else
               {
                 return Response::json(array(
                    'status'=>'s???n ph???m max',
                     'message'   => 'S??? l?????ng s???n ph???m trong gi??? h??ng l???n h??n 10'
                   ));
               }
             }

            else
            {
                //Th???i gian t???o s???n ph???m order m???i session

                 $cart->add($product);
            }*/
               
              //dd($cart);


        }
        else
        {
           
            $user_id=$user->id;
             //Ki???m tra xem user_id c?? t???n t???i trong database gi??? h??ng
            $carts=Order::where(['user_id'=>$user_id,'status'=>'0'])->first();
            //n???u kh??ng t??m ???????c gi??? h??ng ch???a id ???? th?? t???o order m???i
            if(empty($carts))
            {
                $carts=new Order();
                $carts->user_id=$user_id;
                $carts->total=0;
                $carts->status="0";
                $carts->date=Carbon::now();
                $carts->name=$user->name;
                $carts->address=$user->address;
                $carts->phone=$user->phone;
                $carts->save();
            }
            $totalQty=0;
            foreach($carts->product as $items)
            {
                $totalQty+=$items->pivot->qty;
            }
            if($totalQty>99)
                return Response::json(array(
            'status'=>'s??? l?????ng trong gi??? h??ng ???? ?????t t???i ??a',
            ));
        //kiem tra san pham vua them da nam trong cart chua
            $order_product=order_product::where([
            'order_id'=>$carts->id,
            'product_id'=>$id

            ])->first();
            if(empty($order_product))
            {
                $order_product=new order_product();
                $order_product->order_id=$carts->id;
                $order_product->product_id=$id;
                $order_product->price=Product::find($id)->price;
                $order_product->qty=1;
                $order_product->amount=$order_product->price;
                $order_product->save();
                $carts->total=$carts->total+=Product::find($id)->price;
                $carts->save();
            }

        //neu da co san pham thi tagn qty len 1,n???u s??? l?????ng l???n h??n 10 th?? th??ng b??o l???i
            else
            {
                $order_product::where
                ([
                    'order_id'=>$carts->id,
                    'product_id'=>$id
                ])->update(['qty'=> $order_product->qty+1,'amount'=>Product::find($id)->price*($order_product->qty+1)]);
                $order_product->save();
                $carts->total=$carts->total+=Product::find($id)->price;
                $carts->save();
            }
       }
    }
}
