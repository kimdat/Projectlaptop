<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Home | LapTop_shop</title>
    <link href="{{ asset('fronend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fronend/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fronend/css/prettyPhoto.css') }}" rel="stylesheet">
    <link href="{{ asset('fronend/css/price-range.css') }}" rel="stylesheet">
    <link href="{{ asset('fronend/css/animate.css') }}" rel="stylesheet">
	<link href="{{ asset('fronend/css/main.css') }}" rel="stylesheet">
	<link href="{{ asset('fronend/css/bootstrap-social.css') }}" rel="stylesheet">
    <link href="{{ asset('fronend/css/responsive.css') }}" rel="stylesheet">
	<link href="{{ asset('fronend/css/style_overview.css') }}" rel="stylesheet">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<style>
	.btn-social {
    position: relative;
    padding-left: 44px;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.btn {
    display: inline-block;
    padding: 8px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.428571429;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -o-user-select: none;
    user-select: none;
}
.btn-block {
    display: block;
    width: 100%;
    padding-right: 0;
    padding-left: 0;
}
.btn-social.btn-lg {
    padding-left: 61px;
}
.btn-google-plus {
    color: #fff;
    background-color: #dd4b39;
    border-color: rgba(0,0,0,0.2);
}
	.shoppingcart button
	{
		border: 2px solid white !important;
  		padding: 14px 20px;
  		font-size: 16px;
          cursor: pointer;
          height: 50px;
	}
	.khonghoatdong {
	opacity: 0.7;
	background-color: #ccc;

}
.cart_product {
    position: relative;
}
.cart_product .badge
{
    position: absolute;
    top:-10px;
    width: 100px;
    color:black;
    background-color: red;
}
.cart_product img
{
    margin-top:10px;
}
#loginModal
{
	z-index: 5000;
}
.img-fluid {
    width: 100px;
    height: 70px;
}
.users .dropbtn {
	width: 150px;
    background-color: #0099ff;
    color: white;
    padding: 10px;
    font-size: 16px;
    border: none;
border: 2px solid white;
  }
  .users .dropdown {
    position: relative;
    display: inline-block;
  }
  .users .dropdown-content {
	display: none;
	position: absolute;
    background-color:white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
 	 z-index: 1;
	padding-bottom: 10px;
  }
  .users .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
  }

  .users .dropdown:hover .dropdown-content {display: block;}
  .users .dropdown:hover .dropbtn {background-color: #61a2d0;}
/*drowdown*/

/*drowdown*/
</style>
</head><!--/head-->
:
<script type="text/javascript">

//load l???i trang khi user b???m back
if(!!window.performance && window.performance.navigation.type === 2)
{

    window.location.reload();
}


//Show ph???n modal ????ng nh???p
	function dangnhap()
	{
		$('#uploadTabLogin').addClass('active');
		$('#liLogin').addClass('active');
	}

	function  dangky()
	{

		$('#browseTabLogin').addClass('active');
		$('#liRegister').addClass('active');
		//$('.users .dropdown-content').css('display','block');

	}
//H??m log out
function logOut()
{
	$.ajax({
			type:  "GET",
      		url:	 " {{ asset('/logout')}}",
      		data:{logout:'true'},
			datatype: 'json',
			success:function(data)
           	{
				 location.reload();

           	}
       	}
    );
}
//h??m update s??? l?????ng item ??? modal
function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")
}
function updateCart(qty,product_id,order_id,timecreate,qty_before)
{

	$.ajax({
		type:  "GET",
    	url:	 " {{ asset('cart/update')}}",
      	data:{qty:qty,order_id:order_id,product_id:product_id,timecreate:timecreate},
		datatype: 'json',
		error:function(xhr)
      {
         var x=xhr.responseText;
            x=$.parseJSON(x);
         console.log(x.message);
		},
		success:function(data)
        {
			if(data.status=="t???i ??a")
			{	console.log(data.qty);
				$(qty_before).val(data.qty);
				$('.soluongmax').html('s??? l?????ng gi??? h??ng ???? ?????t t???i ??a, ch??? t???i ??a 100 s???n ph???m');
			}
			else if(data.status)
			{
				if($("#noFindItem").text().length<80)
				$("#noFindItem").append("kh??ng t??m th???y item ");
			}
            else if(qty!="" && qty >0 && qty<=100)
       		{
					//d??ng scrip ho???c ajax c???p nh???p l???i gi?? t???ng ti???n
				var total=0;
				$("tbody").find("tr").each(function() {
				var qty = $(this).find('td .input-qty').val();
				var price=  $(this).find("input[name='price']").val();

					var amount=qty*price;
					amount= formatNumber(amount)+ " ??";
					$(this).find('td.amount').html(amount);
					var total=data.total;
					total=formatNumber(total) + " ??";
					$('#total').html(total);
					$('.soluongmax').html('');

 			});
		}}
    });
}
//H??m x??a s??? l?????ng item ??? modal
function deleteCartModal(product_id,order_id,emn,timecreate)
{

	$.ajax({
		type:  "GET",//type l?? get
      	url: " {{ asset('cart/delete')}}",//truy c???p t???i url cart/delete
      	data:{ order_id:order_id,product_id:product_id,timecreate:timecreate},//pass tham s??? v??o key
		datatype: 'json',
        success:function(data)
        {
			if(data.status)
			{
				if($("#noFindItem").text().length<80)
				$("#noFindItem").append("kh??ng t??m th???y item ");
			}
			else
			{
				var total=data.total ;
				total=formatNumber(total) + " ??";
					$('#total').html(total);

				$(emn).closest( "tr" ).hide();
			}
		}
    });
}

//Khi nh???p s??? l?????ng b?? h??n 1 ho???c l???n 10 ??? modl
function updateModal(qty)
{
		//N???u nh???p b?? h??n 1 th?? m???c ?????nh l?? 1

	if($(qty).val()<1)
	{
		var error="s??? l?????ng ph???i t??? 1 t???i 100 v?? kh??ng ???????c tr???ng";
		$(qty).val(1);
	}
	else if($(qty).val()>100)
	{
		var error="s??? l?????ng ph???i t??? 1 t???i 100 v?? kh??ng ???????c tr???ng";
		$(qty).val(100);
	}
}

//H??m add gi??? h??ng
function AddCart(product_id)
{


    $.ajax({
    type:  "GET",
    url: "{{ asset('Addcart')}}",
    data: { product_id: product_id },
    datatype: 'json',
	error:function(xhr)
      {
         var x=xhr.responseText;
            x=$.parseJSON(x);
         console.log(x.message);
	},
    success: function (data)
	{

       	if(data.status)
        {
			console.log(data.status);
			$("#AlertModal .modal-body").html("s??? l?????ng trong gi??? h??ng ???? ?????t t???i ??a,ch??? ???????c t???i ??a 100");
        	$("#AlertModal").modal("show");
        }
        else	location.reload();
	}});
}
//click v?? item t??? live search
function Redirectlivesearch(a)
{
	var sanpham=$(a).find('.search_name').text();

	window.location.href='/product/'+sanpham;
}
function searchSubmit()
{
	$('#search').submit();
}
$(document).click(function (e)
{
    // ?????i t?????ng container ch???a popup


    // N???u click b??n ngo??i ?????i t?????ng container th?? ???n n?? ??i
	var container = $('.search');
    if (!container.is(e.target) && container.has(e.target).length === 0)
    {
        container.find('table').hide();
    }

});
$(document).ready(function()
{
	//livesearchclic

	$('.textsearch').on('mouseup',function(){

		if($('.resultsearch').text()!="")
		{
			$('.resultsearch').show();
		}
	})
	 $('.textsearch').on('keyup',function()
	{
		var value = $(this).val();
		value=value.split(" ").join("");
		console.log(value);
		if(value!="")
		{
			 $.ajax({
                    type: 'get',
                    url:  " {{ asset('/livesearch')}}",
                    data: {
                        'search': value
                    },
					error:function(xhr)
            {
                var x=xhr.responseText;
                 x=$.parseJSON(x);
                     console.log(x.message);

             },
                    success:function(data){
					//	console.log(data);

							$('.resultsearch').show();
							$('.resultsearch').html(data.status);

							$(".search_name").each(function(){
								$(this).click(function(){
									var name=$(this).find('.search_name').text();
									console.log(name);
							});
						});


                    }

		        });
		}
		else
		{	$('.resultsearch').hide();
			$('.resultsearch').html("");
		}
        })

    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });

	$("#loginModal").on('hide.bs.modal', function(){

		$('#uploadTabLogin').removeClass('active');
		$('#browseTabLogin').removeClass('active');
		$('#liLogin').removeClass('active');
		$('#liRegister').removeClass('active');
 	});

	$("#loginModal").on('show.bs.modal', function(){
		$(this).find('input').css('border','');
		$("#dangnhap").html("");

		$("#dangnhap").html("");
		$('.error').each(function() {
			$(this).text('');
		});

 	});
	 $("#modalCheckOut").on('show.bs.modal', function(){

		$(this).find('input').css('border','');
		$("#dangnhap").html("");
		$('.error').each(function() {
			$(this).text('');
		});

 	});

	 //search
	 $('#search').submit(function(e)
    {
		var x=$('.textsearch').val();
		e.preventDefault();
        $.ajaxSetup(
        {
            headers:
            {
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
		if(x!="")
		window.location.href = "/search?keyword="+x;

    });
	//mouse up search
	$('#register input').keyup(function(e)
    {
		$(this).css('border','');
	});
	$('#login input').keyup(function(e)
    {
		$(this).css('border','');
		$("#dangnhap").html("");
	});

    //????ng nh???p v?? ????ng k??
    $('#login').submit(function(e)
    {

		$('.error').each(function() {
			$(this).text('');
		});
        e.preventDefault();
        $.ajaxSetup(
        {
            headers:
            {
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
			    method:'post',
      		    url:	 " {{ asset('/postLoginCheckOut')}}",
      		    data:$('#login').serialize(),
			    datatype: 'json',
				error:function(error)
            {
             		var x=error.responseText;
                  	x=$.parseJSON(x);
                   console.log(x);
					let errors = error.responseJSON.errors;
					//FOCUS v??o l???i ?????u ti??n
					var errorsfocus=Object.keys(errors)[0];

					var nameFocus=$("#login input[name="+errorsfocus+"]");
					nameFocus.focus();
					//nameFocus.focus();
					//console.log(a.val());
					//$(`.error[data-error="${errors[0]}"]`).focus();
      				for(let key in errors)
       			{
         			let errorDiv = $(`.error[data-error="${key}"]`);
         			if(errorDiv.length )
         			{

             			 errorDiv.text(errors[key][0]);
						 $("#login input[name="+key+"]").css('border','2px solid red');
         			}
					 //n???u kh??ng c?? l???i

        		}
				//const propertyNames = Object.keys(errors);
				////$.each(errors, function( index, value ) {
					//$('#emailTontaiR').text(errors.email);
				//})
					//errors=JSON.stringify(errors.error);

              },
			    success:function(data)
           	    {

                    if(data.status=="Th??nh c??ng" ||data.status=="admin")
                    {
                      location.reload();
                    }
                    else
                  	{
						$("#dangnhap").html(data.status)
                   		$("#dangnhap").css('color','red');
					}

           	    }
        	});

    });

         //????ng k??  mua h??ng khi user chua ????ng nh???p

	//form register submit
    $('#register').submit(function(e)
    {
		//x??a h???t l???i tr?????c khi b???t
		$('.error').each(function() {
			$(this).text('');
		});

        e.preventDefault();
        $.ajaxSetup(
        {
             headers:
            {
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
            }
        );
        $.ajax({
			method:'post',
      		url:	 " {{ asset('/postRegisterCheckOut')}}",
      		data:$('#register').serialize(),
			datatype: 'json',
			error:function(error)
            {
             		var x=error.responseText;
                  	x=$.parseJSON(x);
                   console.log(x);
					let errors = error.responseJSON.errors;
					//FOCUS v??o l???i ?????u ti??n
					var errorsfocus=Object.keys(errors)[0];

					var nameFocus=$("#register input[name="+errorsfocus+"]");
					nameFocus.focus();
					//nameFocus.focus();
					//console.log(a.val());
					//$(`.error[data-error="${errors[0]}"]`).focus();
      				for(let key in errors)
       			{
         			let errorDiv = $(`.error[data-error="${key}"]`);
         			if(errorDiv.length )
         			{

             			 errorDiv.text(errors[key][0]);
						 $("#register input[name="+key+"]").css('border','2px solid red');
         			}
					 //n???u kh??ng c?? l???i

        		}
				//const propertyNames = Object.keys(errors);
				////$.each(errors, function( index, value ) {
					//$('#emailTontaiR').text(errors.email);
				//})
					//errors=JSON.stringify(errors.error);

              },
			success:function(data)
           	{

                if(data.status=="Th??nh c??ng")
                {
					location.reload();
                }


           	}
        });
    });
});



</script>
<body>

<header id="header" style="margin-top:-25px" ><!--header-->
		<div class="header_top" ><!--header_top-->
			<div class="container">
				<div class="row" >
					<div class="col-sm-6">
						<div class="contactinfo">
							<ul class="nav nav-pills">
								<li><a href="#"><i class="fa fa-phone"></i> +08 123 456 789</a></li>
								<li><a href="#"><i class="fa fa-envelope"></i>laptopshop@gmail.com</a></li>
							</ul>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="social-icons pull-right">
							<ul class="nav navbar-nav">
								<li><a href="#"><i class="fa fa-facebook"></i></a></li>
								<li><a href="#"><i class="fa fa-twitter"></i></a></li>
								<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
								<li><a href="#"><i class="fa fa-dribbble"></i></a></li>
								<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
			<!--Search-->
		<div class="header-middle"   style="background:#fafafa; padding-bottom:10px;  z-index: 10;">
            <div class="container">
				<div class="row" >
					<div class="col-sm-2" style="padding-top:10px">
                    		<img src="{{URL::asset('images/laptop.png')}}" alt="" style="width:150px; height:100px;margin-top:-25px">
                	</div>
                	<div class="col-sm-6" >
                    		<div  class="search" style="position: relative;">
							<form id="search"   method="get" >
							{{csrf_field()}}
                         	 	<input style="float:left;width:80%;height:40px" type="text" placeholder=" t??m ki???m s???n ph???m m?? b???n mong mu???n.." name="search" class="textsearch">
                          		<button   style="float:left;width:20%;height:40px" type="submit" class="search"><i class="fa fa-search"></i> T??m ki???m</button>
							</form>

							<table  style="border:none ;background-color:white;position: absolute;margin-top:40px;  z-index: 9999;" hidden class="resultsearch table table-hover" style="background-color: white;">


                            </table>
                              </div>


					</div>
					@if(!Session::has('key'))
					<div class="col-sm-2" >
							<div class="users"  >

                        		<div class="dropdown" >

									  <button class="dropbtn" style="text-align: center; border-radius: 5px;">

                                        <i class="fa fa-user" aria-hidden="true"></i> &nbsp; <b>????ng Nh???p</b> </button>
                          			<div class="dropdown-content" style="padding: 10px;">
									<!--modal-->
									<!-- Button trigger modal -->
										<button type="button" onclick="dangnhap()" id="target1"  class="btn btn-primary btn-lg" data-toggle="modal" data-target="#loginModal" style="width: 100%; border-radius: 5px; ">????ng nh???p    </button>
										<button type="button" onclick="dangky()" id="target2"  class="btn btn-primary btn-lg" data-toggle="modal" data-target="#loginModal" style="width: 100%; margin-top:10px; border-radius: 5px;">T???o t??i kho???n    </button>
										<!--login google-->

    									<a class="btn btn-outline-dark"  href="{{ URL::to('auth/google') }}" role="button" style="text-transform:none">
      											<img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />
												  ????ng nh???p v???i google
   										 </a>

									<!--login google-->
									<!--login github-->


										<a id="github-button" href="{{ URL::to('auth/github') }}" class="btn btn-block btn-social btn-github" style="height: 40px;">
										<i class="fa fa-github"></i>
										<p style="padding-left: 20px;color:white">	????ng nh???p v???i git hub</p>
										</a>

									<!--login github-->
									</div>
                        		</div>
							</div>

					</div>
					@else
					<div class="col-sm-2" >
							<div class="users"  >

                        		<div class="dropdown" >

									  <button class="dropbtn" style="text-align: center;border-radius: 5px;">

									   <b>Ch??o {{Session::get('key')->name}}</b> <br>  </button>
                          			<div class="dropdown-content"  style="padding: 10px;">
									<!--modal-->
									<!-- Button trigger modal -->

										<button type="button" style="width: 100%; border-radius: 5px; "   class="btn btn-primary btn-lg" onclick="window.location='/myorder/history'">Xem h??a ????n  </button>

										<button type="button"  onclick="logOut()" id="target1"  class="btn btn-primary btn-lg" style="width: 100%; border-radius: 5px;">????ng xu???t   </button>

									</div>
                        		</div>
							</div>

					</div>


					@endif

					<div class="col-sm-2 shoppingcart">

                       		<button  type="button"data-toggle="modal" data-target="#cartModal" style="background: none; border:none; ">
							   <div classs>
							   <i class="fa fa-shopping-cart" style="color:rgb(15, 15, 15);"></i>
							   <b style="color: rgb(10, 10, 10);">  gi??? h??ng</b>

							   @if(isset($totalQty) &&$totalQty>0)
							   <span class="label label-warning">{{$totalQty}}</span>
							   @endif

							   <div>
                        	</button>

					</div>
					<div class="clear" style="clear: both;"></div>
				</div>
			</div>
		</div>
			<!--end Search-->
        <!--/header_top-->



		<div class="header-bottom"    style="background:  #ffffff;margin-bottom:20px"><!--header-bottom-->
			<div class="container" >
				<div class="row">
					<div class="col-sm-9" style="margin-top:-25px;">
                        <div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="mainmenu " style="float: right; margin-top:-20px;">
							<ul class="nav navbar-nav collapse navbar-collapse">
								<li><a href="{{ URL::to('/home') }}" class="active"  style="color: rgb(12, 12, 12);"><i class="fa fa-home" aria-hidden="true"></i> &nbsp; Trang Ch???</a></li>

								@if(isset($category) && $category->count()>0)
								<li class="dropdown"><a href="#"  style="color: rgb(3, 3, 3);"><i class="fa fa-laptop" aria-hidden="true"></i> &nbsp; S???n Ph???m</a>
                                    <ul role="menu"  style="word-break: break-all;" class="sub-menu" style="background: white;">
									@foreach($category as $c)

										<li><a  onclick="window.localStorage.clear();" href="{{ URL::to('/product/'.Str::slug($c->name)) }}" >{{$c->name}}</a></li>


									@endforeach
                                    </ul>
								</li>
								@endif
								<li class="dropdown"><a href="{{ URL::to('/news') }}" style="color: rgb(15, 15, 15);"><i class="fa fa-eye" aria-hidden="true"></i> &nbsp;th??ng tin v??? shop </a>

									</li>

									<li><a href="{{ URL::to('/contact') }}" style="color: rgb(15, 15, 15);"><i class="fa fa-phone-square" aria-hidden="true"></i> &nbsp;
                                        li??n h???</a></li>
								</ul>
							</div>
						</div>

					</div>
				</div>
			</div><!--/header-bottom-->
        </header><!--/header-->



	<!--slider-->
	@yield('slide')
	<!--/slider-->

		<section style="background: white;">
			<div class="container "  style="background: white;" >
				<div class="row">
					<!--t??m theo chi ti???t-->
					@yield('detail')
					@yield('money')
					@yield('ship_home')
					@yield('ship')
					@yield('warranty')
					@yield('deposit')
					@yield('detail_home')
					@yield('useroder')
					@yield('userviewOrder')
					<!--end t??m theo chi ti??te-->

					<!--s???n ph???m-->
					@yield('product')
					<!--end s???n ph???m-->

					<!--ccontact-->
					@yield('contact')
					<!--end contact-->
					@yield('dell')
					<!--s???n ph???m dell-->
					@yield('index')

					@yield('product_A')

					@yield('search')
					<!--login-->
					@yield('login')
					<!--end login-->

					<!--cart_detail-->
					@yield('cart_detail')
                    <!--end cart detail-->
                    @yield('news')
				</div>
			</div>
		</section>
<!-- Modal  login-->
<div class="modal fade" id="loginModal" data-backdrop="static"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

							<div class="modal-dialog">
							<div class="modal-content">
							<div class="modal-header">
					<button type="button" class="close"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>

					</button>


				</div>
				<div class="modal-body" style="display: block; z-index:1;">
					<div role="tabpanel">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" id="liLogin" ><a href="#uploadTabLogin" aria-controls="uploadTab" role="tab" data-toggle="tab">????ng Nh???p</a>

							</li>
							<li role="presentation" id="liRegister" ><a href="#browseTabLogin" aria-controls="browseTab" role="tab" data-toggle="tab">????NG K??</a>

							</li>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane " id="uploadTabLogin" >
								<form   id="login" method="post" action="javascrip:void(0)" >
								{{ csrf_field() }}
								<h3  style="text-align: center;">????ng Nh???p</h3>
									<h5 style="color: rgb(12, 12, 12);" >Email <sup>*</sup></h5>
									<input type="text"  class="form-control" name="email"  ><br>
									<div class="text-danger error" data-error="email"></div>
									<!-- ?????nh d???ng l???i c ss d??ng n??y -->

									<h5 style="color: rgb(15, 15, 15);">Password <sup>*</sup></h5>
									<input type="password"   name="password"  class="form-control" ><br>
									<div class="text-danger error" data-error="password"></div>
									<div id="dangnhap"></div>
									<button type="submit"  class="btn btn-primary" style=" border-radius: 15px;">????ng nh???p</button>

									<p style="color: rgb(26, 24, 24);">b???n ???? c?? t??i kho???n?



								</p>

								</form>
								<!--login google-->

								<a class="btn btn-outline-dark"  href="{{ URL::to('auth/google') }}" role="button" style="text-transform:none">
      											<img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />
												  ????ng nh???p v???i google
   										 </a>

									<!--login google-->
									<!--login github-->


										<a id="github-button" href="{{ URL::to('auth/github') }}" class="btn btn-block btn-social btn-github">
										<i class="fa fa-github"></i>
										<p style="padding-left: 20px;color:white">	????ng nh???p v???i git hub</p>
										</a>

									<!--login github-->
							</div>
							<div role="tabpanel" class="tab-pane" id="browseTabLogin"   >
								<form  id="register" method="post" action="javascrip:void(0)">
									{{ csrf_field() }}
									<h3 style="text-align: center;">T???o t??i kho???n</h3>
									<h5 style="color: rgb(12, 12, 12);" >H??? v?? t??n <sup>*</sup></h5>
									<input type="text" class="form-control" name="name" placeholder="H??? v?? t??n"  ><br>
									<div class="text-danger error" data-error="name"></div>
									<h5 style="color: rgb(12, 12, 12);" >S??T <sup>*</sup></h5>
									<input type="text"  class="form-control" name="S??T" placeholder="Nh???p s??? ??i???n tho???i"  ><br>
									<div class="text-danger error" data-error="S??T"></div>
									<h5 style="color: rgb(12, 12, 12);" >?????a ch??? <sup>*</sup></h5>
									<input type="text" class="form-control" name="address"><br>
									<div class="text-danger error" data-error="address"></div>
									<h5 style="color: rgb(12, 12, 12);" >Email <sup>*</sup></h5>

									<input id="emailR" type="text" class="form-control" name="email"  ><br>
									<div class="text-danger error" data-error="email"></div>

									<h5 style="color: rgb(15, 15, 15);">M???t Kh???u <sup>*</sup></h5>
									<input type="password"  name="password"  class="form-control" placeholder="M???t kh???u"  ><br>
									<div class="text-danger error" data-error="password"></div>
									<h5 style="color: rgb(15, 15, 15);">Nh???p l???i m???t kh???u <sup>*</sup></h5>
									<input type="password"  name="password_confirmation"  class="form-control" placeholder="M???t kh???u"  ><br>
									<div class="text-danger error" data-error="password_confirmation"></div>
										<button type="submit" class="btn btn-primary" style=" border-radius: 15px;">x??c nh???n t???o t??i kho???n</button>
									<p>Khi b???n nh???n ????ng k??, b???n ???? ?????ng ?? th???c hi???n m???i giao d???ch mua b??n theo ??i???u ki???n s??? d???ng v?? ch??nh s??ch c???a LapTop-shop.</p>
									</form>

							</div>
						</div>


					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

				</div>
			</div>
		</div>
			</div>


<!--modal alert-->
		<div class="modal fade" id="AlertModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-body" style=" text-align: center;">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="javascript:window.location.reload()" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

	</div>

		<div class="modal fade"  id="cartModal" role="dialog" aria-labelledby="exampleModalLabel"  data-backdrop="static" data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="exampleModalLabel">
                                Your Shopping Cart
                        </h5>
                        <button type="button" onclick="javascript:window.location.reload()"  class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
							</button>
							<div id="noFindItem" style="color:red;"></div>
                    </div>
                    <div class="modal-body">
                     <!--Tr?????ng h???p user ch??a ????ng nh???p thao t??c v???i session-->
                    @if(Session::has('cart'))
						<div class="table table-responsive">
							<table class="table table-image">
                                <thead>
                                <tr>

									<th scope="col">s???n ph???m</th>
									<th scope="col">T??n s???n ph???m</th>
                                    <th scope="col">Gi??</th>
                                    <th scope="col">S??? l?????ng </th>
                                    <th scope="col">T???ng c???ng</th>
                                    <th scope="col">X??a</th>
                                </tr>
                                </thead>
                                <tbody class="cart-body">
									@php  $sum=0 @endphp
									@foreach(Session::get('cart')->items as $product)
										@if(App\Product::find($product['id']))
											@if(App\Product::find($product['id'])->status=="1")
											<tr>
											<td class="image"><img  height="100px" width="100px" src="{{ url('images/'.App\Product::find($product['id'])->image) }}" alt="" />
                                    		</td>
                                    		<td class="" style="word-break: break-all;">{{App\Product::find($product['id'])->name}}</td>
                                      		<!--Tr?????ng h???p c??n h??ng(status l?? 1)-->
											<input name="price" type="hidden" value="{{App\Product::find($product['id'])->price}}">
											<td class="price"  style="white-space: nowrap;">{{number_format(App\Product::find($product['id'])->price,0,",",".")}} ??</td>
											<td class="buttons_added qty ">
											<input aria-label="quantity" class="input-qty" max="100" min="1" name="" type="number" value="{{$product['qty']}}"
                                             onchange="updateModal(this);updateCart(this.value,<?php echo $product['id'] ?>,'',<?php echo $product['time_at'] ?>,this)">
											</td>
											<td class = "amount" style="white-space: nowrap;">
											{{number_format(App\Product::find($product['id'])->price*$product['qty'],0,",",".")}}
											 ??</td>
                                    		<td>
												@php $sum+=App\Product::find($product['id'])->price*$product['qty'] @endphp
                                    		<a href="#" onclick="deleteCartModal(<?php echo $product['id'] ?>,'',this,<?php echo $product['time_at']?>)">
                                        	<svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        	</svg>
                                    		</a>
											</td>
											</tr>
											@else
											<tr class="khonghoatdong" >


												<td class="image">

													<img  height="100px" style="margin-bottom: 10px;" width="100px" src="{{ url('images/'.App\Product::find($product['id'])->image) }}" alt="" />

													<span class="badge" >Kh??ng ho???t ?????ng</span>

												</td>
                                    			<td class="" style="word-break: break-all;">{{App\Product::find($product['id'])->name}}</td>
                                      			<!--Tr?????ng h???p c??n h??ng(status l?? 1)-->

												  <td class="price" style="white-space: nowrap;">
												  {{number_format(App\Product::find($product['id'])->price,0,",",".")}}
												  ??</td>
                                				<td class="qty"> </td>
                               		 			<td class = "amount"></td>
                                	 			<td>
												 <a href="#" onclick="deleteCartModal(<?php echo $product['id'] ?>,'',this,<?php echo $product['time_at']?>)">
                                        		<svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            	<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            	<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        		</svg>
                                    			</a>
												</td>
											</tr>



										@endif
										 @endif

									@endforeach
								</tbody>
							</table>
						</div>
                        <div class="d-flex justify-content-end">
                                <h5>Total: <span class="price text-success" id="total" >
								{{number_format($sum,0,",",".")}} ??</span></h5>
								<h5 class="soluongmax" style="color:red"></h5>
                        </div>
                            <!--Tr?????ng h???p user  ????ng nh???p thao t??c v???i database-->
					@elseif(isset($orders))
					<div class="table table-responsive">
						<table class="table table-image">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">s???n ph???m</th>
                                <th scope="col">Gi??</th>
                                <th scope="col">S??? l?????ng</th>
                                <th scope="col">T???ng c???ng</th>
                                <th scope="col">X??a</th>
                            </tr>
                            </thead>
                                <tbody>
									 @foreach($orders->product as $p)

									  @if($p->status=="1")
									<tr>

										<td class="image"><img  width="100px"  height="100px" src="{{ url('images/'.$p->image) }}" alt="" />
                                        </td>
                                    	<td style="word-break: break-all;">{{$p->name}}</td>
										  <!--Tr?????ng h???p c??n h??ng(status l?? 1)-->
										  <input name="price" type="hidden" value="{{$p->price}}">
										<td class="price" style="white-space: nowrap;">	{{number_format($p->pivot->price,0,",",".")}} ??</td>


										<td class="qty">
                                        <div class="buttons_added">
                                            <input aria-label="quantity" class="input-qty" max="100" min="1" name="" type="number" value="{{ $p->pivot->qty}}"
                                         onchange="updateModal(this);updateCart(this.value,'{{$p->id}}','{{$orders->id}}','{{$p->pivot->created_at}}',this)">
										</div></td>
                                    	<td class = "amount" style="white-space: nowrap;">	{{number_format($p->pivot->amount,0,",",".")}} ?? </td>
                                    	<td>
                                        <a href="#"  onclick="deleteCartModal('{{$p->id}}','{{$orders->id}}',this,'{{$p->pivot->created_at}}')">
                                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                          </svg>
                                       </a>
									   </td>
									</tr>
									 @else

									   <tr class="khonghoatdong" >

										<td class="image">

										<img  width="100px" style="margin-bottom: 10px;"  height="100px" src="{{ url('images/'.$p->image) }}" alt="" />
										<span class="badge" >Kh??ng ho???t ?????ng</span>
										</td>
                                    	<td style="word-break: break-all;">{{$p->name}}</td>

										<td class="price" style="white-space: nowrap;">
										{{number_format($p->pivot->price,0,",",".")}} ??
										 </td>
                                      	<td class="qty"> </td>
                                    	<td class = "amount"></td>
                                    	<td>
                                      	<a href="#"  onclick="deleteCartModal('{{$p->id}}','{{$orders->id}}',this,'{{$p->pivot->created_at}}')">
                                        <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-trash" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        </svg>
                                      	</a>
										   </td>
										 </tr>

										@endif

								  	@endforeach
								</tbody>
							</table>
							</div>
                            <div class="d-flex justify-content-end">
                                <h5>Total: <span class="price text-success" id="total" >
								{{number_format($orders->total,0,",",".")}} ??</span></h5>
								<h5 class="soluongmax" style="color:red"></h5>
							</div>
							<!--end cart body-->
                			@endif
							</div><!--/cart model-->
                            <div class="modal-footer border-top-0 d-flex justify-content-between">
                              <button onclick="javascript:window.location.reload()"yyyyyyyyyyyy type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-right:400px;">Close</button>
							  <a href="{{url('/cart')}}" style="background: none; color:black;"> <button type="button"  class="btn btn-success" ><b>ki???m tra</b> </button></a>

							</div>
                </div>
            </div>
        </div>
		<footer id="footer"><!--Footer-->


			<div class="footer-widget">
				<div class="container">
					<div class="row">
						<div class="col-sm-3">
							<div class="single-widget">
								<h2>CH??NH S??CH V?? H??? TR???</h2>
								<ul class="nav nav-pills nav-stacked">
									<li><a href="/money">Ph????ng Th???c Thanh to??n</a></li>
									<li><a href="/ship-home">Ph????ng Th???c v???n chuy???n</a></li>
									<li><a href="/ship">Giao H??ng T???n nh??</a></li>
									<li><a href="/warranty">Quy ?????nh B???o h??nh</a></li>
									<li><a href="/deposit">Quy ?????nh ?????t C???c</a></li>
								</ul>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="single-widget">
								<h2>H??ng Laptop </h2>
								@if(isset($category) && $category->count()>0)
								<ul class="nav nav-pills nav-stacked">


									@foreach($category as $c)

									<li><a onclick="window.localStorage.clear();" href="{{ URL::to('/product/'.Str::slug($c->name)) }}" >{{$c->name}}</a></li>



									@endforeach

								</ul>
								@endif
							</div>
						</div>
						<div class="col-sm-3">
							<div class="single-widget">
								<h2>?????a Ch??? v?? Li??n H???</h2>
								<ul class="nav nav-pills nav-stacked">
									<li><a href="https://www.google.com/maps/place/FPT-Aptech+Computer+Education+HCM/@10.7865832,106.6639139,17z/data=!4m12!1m6!3m5!1s0x31752ed2392c44df:0xd2ecb62e0d050fe9!2sFPT-Aptech+Computer+Education+HCM!8m2!3d10.7865832!4d106.6661026!3m4!1s0x31752ed2392c44df:0xd2ecb62e0d050fe9!8m2!3d10.7865832!4d106.6661026?hl=vi-VN">- ??/C :590 C??ch M???ng Th??ng T??m, Ph?????ng 11, Qu???n 3, H??? Ch?? Minh 723564, Vi???t Nam</a></li>
									<li><a href="#">- S??? ??i???n tho???i li??n h???:  +08 123 456 789</a></li>
									<li><a href="#">- email: Laptopshop@gmail.com</a></li>
									<li><a href="#">- Th???i gian m??? c???a 8:00-22:00</a></li>
									<li><a href="#">- T??? Th??? 2 ?????n ch??? nh???t </a></li>
									<ul class="nav navbar-nav">
										<li><a href="#"><i class="fa fa-facebook"></i></a></li>
										<li><a href="#"><i class="fa fa-twitter"></i></a></li>
										<li><a href="#"><i class="fa fa-linkedin"></i></a></li>
										<li><a href="#"><i class="fa fa-dribbble"></i></a></li>
										<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                </ul>

							</ul>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="single-widget">
							<h2>MAP</h2>
							<ul class="nav nav-pills nav-stacked">
								<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.322629018092!2d106.66391391406837!3d10.786583192314618!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ed2392c44df%3A0xd2ecb62e0d050fe9!2sFPT-Aptech%20Computer%20Education%20HCM!5e0!3m2!1svi!2s!4v1595300155497!5m2!1svi!2s" width="300" height="200" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
							</ul>
						</div>
					</div>


                    </div>


				</div>
			</div>

        <div class="footer-bottom">
			<div class="container">
				<div class="row">
					<p class="pull-left">Copyright ?? 2020 laptop-shop Inc. All rights reserved.</p>
					<p class="pull-right">Designed by <span><a target="_blank" href="#">Nh??m 5</a></span></p>
				</div>
			</div>
		</div>

	</footer><!--/Footer-->




    <script src="{{ asset('fronend/js/jquery.js') }}"></script>
	<script src="{{ asset('fronend/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('fronend/js/jquery.scrollUp.min.js') }}"></script>
	<script src="{{ asset('fronend/js/price-range.js') }}"></script>
    <script src="{{ asset('fronend/js/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ asset('fronend/js/main.js') }}"></script>
</body>
</html>
