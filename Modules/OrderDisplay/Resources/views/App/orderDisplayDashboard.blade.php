<!DOCTYPE html>
<html lang="en">
    <!--begin::Head-->

    <head>
        <base href="../" />
        <title>Metronic - The World's #1 Selling Bootstrap Admin Template by Keenthemes</title>
        <meta charset="utf-8" />
        <meta name="description" content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
        <meta name="keywords" content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="Metronic - Bootstrap Admin Template, HTML, VueJS, React, Angular. Laravel, Asp.Net Core, Ruby on Rails, Spring Boot, Blazor, Django, Express.js, Node.js, Flask Admin Dashboard Theme & Template" />
        <meta property="og:url" content="https://keenthemes.com/metronic" />
        <meta property="og:site_name" content="Keenthemes | Metronic" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
        <link rel="shortcut icon" href={{ asset("assets/media/logos/favicon.ico") }} />
        <!--begin::Fonts(mandatory for all pages)-->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <!--end::Fonts-->
        <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
        <link href={{ asset("assets/plugins/global/plugins.bundle.css") }} rel="stylesheet" type="text/css" />
        <link href={{ asset("assets/css/style.bundle.css") }} rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href={{asset("customCss/scrollbar.css")}}>
        <style>
                #sidebar,#main-div{
                    transition: all 0.09s ease-in;
                }
                .icon-process{
                    box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 2px 0px;
                }
                /* .res_order_card:active{
                    background-color: green;

                } */

          </style>
    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body >
         <div class="container-fluid p-0 position-fixed" >
            <nav class="navbar bg-primary" style="height: 6vh" >
                <div class="container-fluid select-none align-items-center">
                        <div class="navbar-brand mb-0 fw-bold fs-4 text-white d-flex  align-items-center">
                            <div class=" cursor-pointer text-active-dark" id="menu-icon" >
                                <i class="fa-solid fa-bars text-white fs-3 pe-5"></i>
                            </div>
                            <div class="p-1">
                                Restaurant
                            </div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center p-1">
                            <a class="btn btn-sm btn-light" href="{{url('/')}}">
                                Home
                            </a>
                        </div>
                </div>
             </nav>
            <div class="d-flex flex-row position-lg-relative" style="height: 94vh">
                <div class="col-lg-3 col-6 nav-fill card rounded-0 position-relative" style="z-index: 99999" id="sidebar" style="height: 100%;">
                    <div class="d-flex flex-column px-5 py-3 shadow">
                        <div class="col-12 text-center p-1">
                            <h4 class="text-primary-emphasis" id="detailHeader">Please Select A Table</h4>
                        </div>
                        <div class="col-12 d-flex justify-content-between">
                            <div class="">
                                <h2 class=" fs-6 fw-bold">Products</h2>
                            </div>
                            <div class="">
                                <h2 class=" fs-6 fw-bold">Qty</h2>
                            </div>
                        </div>

                        <div class="separator "></div>
                    </div>
                    <div class="food-container  overflow-scroll " style="padding-bottom: 150px">
                        {{-- <div class="col-6" data-kt-app-page-loading-enabled="true" data-kt-app-page-loading="on" >
                            <div class="page-loader flex-column">
                                <span class="spinner-border text-primary" role="status"></span>
                                <span class="text-muted fs-6 fw-semibold mt-5">Loading...</span>
                            </div>
                        </div> --}}
                    </div>

                    <div class="w-100 bg-white position-absolute bottom-0 py-3">
                        <div class="col-12 p-2">
                            <div class="col-12 d-flex justify-content-around  align-items-center">
                                <div class="col-9">
                                    <button class="btn btn-primary btn-sm w-100 mb-3" id="prepareStatus">Preparing</button>
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-primary btn-sm mb-3 "><i class="fa-solid fa-print"></i></button>
                                </div>
                            </div>
                            <div class="col-12 px-2">
                                <button class="btn btn-success btn-sm w-100" id="readyStatus">Ready</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-12 overflow-scroll position-lg-relative position-absolute" id="main-div" style="height: 95vh">
                    <div class="row m-3 g-4 user-select-none orderContainer " style="padding-bottom: 100px">

                    </div>
                    <div class="position-fixed bottom-0  w-100 z-index-3 card rounded-0">
                        <div class="row px-3 ">
                            <div class="d-flex col-1 align-items-center fs-5 bg-secondary">
                                <i class="fa-solid fa-filter me-3"></i> <span class="fw-bold">Filters</span>
                            </div>
                            <div class="col-10  d-flex">
                                <div class="pe-3 bg-secondary tab-div" id="order_tab">
                                    <button class="btn  rounded-0 text-warning-emphasis">order</button>
                                </div>
                                <div class="pe-3 tab-div" id="prepare_tab">
                                    <button class="btn rounded-0 text-primary ">Preparing</button>
                                </div>
                                <div class="pe-3 tab-div" id="ready_tab">
                                    <button class="btn  rounded-0 text-success-emphasis">Ready</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
         </div>



        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src={{ asset("assets/plugins/global/plugins.bundle.js") }}></script>
        <script src={{ asset("assets/js/scripts.bundle.js") }}></script>
        <script src="{{ asset('customJs/toastrAlert/alert.js') }}"></script>
        @if(session()->has('message'))
            <script>
                success('{{ session('message') }}');
            </script>
        @endif

            <script>

                // sidebar
                let sideBarOpen=true;
                sideBarCotntrol();
                $('#menu-icon').click(function(){
                    sideBarCotntrol();
                })
                function sideBarCotntrol() {
                    let offsetWidth=document.querySelector('#sidebar').offsetWidth;
                    if(sideBarOpen){
                        sideBarOpen=false;
                        $('#sidebar').css('margin-left',`-${offsetWidth}px`);
                        $('#main-div').removeClass('col-lg-9 col-12');
                        $('#main-div').addClass('col-12');
                    }else{
                        sideBarOpen=true;
                        $('#sidebar').css('margin-left',`0px`);

                        $('#main-div').addClass('col-lg-9 col-12');
                        $('#main-div').removeClass('col-12');
                    }
                }
                //--end-sidebar

                let resOrderLength=0;
                var resOrder=[];
                var currentTag='order';
                var selectedOrder;
                var selectedOrderId;
                let intervalMap=new Map();
                var intervalForDataFetch;
                 const fetchData=()=>{
                    intervalForDataFetch=setInterval(() => {
                        ajaxForData(currentTag);
                    }, 5000);
                 }
                 const ajaxForData=(orderStatus)=>{
                    $.ajax({
                            url: `/res/order/data`,
                            type: 'GET',
                            data:{
                                orderStatus,
                            },
                            error:function(e){
                                status=e.status;
                                if(status==405){
                                    warning('Method Not Allow!');
                                }else if(status==419){
                                    error('Session Expired')
                                }else{
                                    console.log(e);
                                    console.error(' Something Went Wrong! Error Status: '+status )
                                };
                            },
                            success: function(response) {
                                if(response.length>0){
                                    $('.empty-msg').remove();
                                    if(resOrderLength<response.length){
                                        for (let i = resOrderLength; i < response.length; i++) {
                                            $('.orderContainer').prepend(orderComponent(response[i]));
                                            if(response[i].order_status!='ready'){
                                                const givenTime = response[i].sale_detail[0].created_at;
                                                startStopwatch(givenTime,$('.time_div_'+response[i].id));
                                            }
                                            resOrder=[...resOrder,response[i]];
                                        }

                                        resOrderLength=response.length;

                                    }
                                    for (let j = 0; j < resOrderLength; j++) {
                                            if(response[j]){
                                                let upToDateSaleDetailOrderTime=response[j].sale_detail[0].sale_with_table.updated_at;
                                                let currentSaleDetailDateOrderTime=resOrder[j].sale_detail[0].sale_with_table.updated_at;

                                                if(upToDateSaleDetailOrderTime != currentSaleDetailDateOrderTime){
                                                    resOrder[j]=response[j];
                                                    $(`[data-id=${resOrder[j].id}]`).html(
                                                        subComponent(response[j])
                                                    )
                                                    if(selectedOrderId==response[j].id){
                                                        $(`[data-id=${resOrder[j].id}]`).click();
                                                    }
                                                }
                                            }
                                    }
                                }else{
                                    $('.orderContainer').html(`
                                        <div class="col-12 empty-msg">
                                            <div class="col-6 m-auto text-center mt-10 ">
                                                <h1 class="text-gray-600">There is no order</h1>
                                            </div>
                                        </div>
                                        `)
                                    }
                            }
                        })
                 }
                 ajaxForData(currentTag);
                 fetchData();
                //card detail view
                $(document).on('click','.res_order_card',function() {
                    let tableName=$(this).data('table');
                    let orderId=$(this).data('id');
                    selectedOrderId=orderId;
                    let order=resOrder.find((r)=>r.id==orderId);
                    selectedOrder=order;
                    $('#detailHeader').html(tableName);

                    $('.food-container').html(
                        detailOrderComponent(order)
                    )

                    if(!sideBarOpen){
                        sideBarOpen=true;
                        $('#sidebar').css('margin-left',`0px`);
                        $('#main-div').addClass('col-lg-9 col-12');
                        $('#main-div').removeClass('col-12');
                    }
                })
                $(document).on('click','#prepareStatus',function(){
                    let component=statusComponent('preparing',selectedOrder.services);
                    let parent=$(`[data-id=${selectedOrderId}]`);
                    let ribbon=parent.find('.ribbon');
                    let ribbonLabel=parent.find('.ribbon-label');
                    let timeCount=parent.find('.time-count');
                    ribbonLabel.remove();
                    ribbon.prepend(component);
                    timeCount.addClass('text-primary');
                    const interval = intervalMap.get(selectedOrderId);
                    if (!interval) {
                        const givenTime = selectedOrder.sale_detail[0].created_at;
                        startStopwatch(givenTime,$('.time_div_'+selectedOrderId));
                        timeCount.removeClass('text-success');
                        timeCount.addClass('text-primary');
                    }
                    ajaxForStatusChange('preparing');
                    if(currentTag!='preparing'){
                        parent.remove();
                        parent.removeClass('order-2') ;
                        parent.addClass('order-1') ;
                    }
                })

                $(document).on('click','#readyStatus',function(){
                    let component=statusComponent('ready',selectedOrder.services);
                    let parent=$(`[data-id=${selectedOrderId}]`);
                    let ribbon=parent.find('.ribbon');
                    let ribbonLabel=parent.find('.ribbon-label');
                    let timeCount=parent.find('.time-count');
                    ribbonLabel.remove();
                    ribbon.prepend(component);
                    timeCount.removeClass('text-danger');
                    timeCount.addClass('text-success');
                    stopStopwatch(selectedOrderId);
                    ajaxForStatusChange('ready');

                    if(currentTag!='ready'){
                        parent.remove();
                        parent.removeClass('order-1') ;
                        parent.addClass('order-2') ;

                    }


                })

                $('#order_tab').click(function(){
                    resOrderLength=0;
                    resOrder=[];
                    $('.orderContainer').html('');
                    currentTag='order';
                    $('.tab-div').removeClass('bg-secondary');
                    $(this).addClass('bg-secondary');
                    $('.food-container').html('');
                    ajaxForData('order');
                })
                $('#prepare_tab').click(function(){
                    resOrderLength=0;
                    resOrder=[];
                    $('.orderContainer').html('');
                    currentTag='preparing';
                    $('.tab-div').removeClass('bg-secondary');
                    $(this).addClass('bg-secondary');
                    $('.food-container').html('');
                    ajaxForData('preparing');
                })
                $('#ready_tab').click(function(){
                    resOrder=[];
                    resOrderLength=0;
                    $('.orderContainer').html('');
                    currentTag='ready';
                    $('.tab-div').removeClass('bg-secondary');
                    $(this).addClass('bg-secondary');
                    $('.food-container').html('');
                    ajaxForData('ready');
                })




                const ajaxForStatusChange=(status)=>{
                    $.ajax({
                        url: `/res/order/status/change`,
                        type: 'get',
                        data:{
                            status,
                            'id':selectedOrderId
                        },
                        error:function(e){
                            status=e.status;
                            if(status==405){
                                warning('Method Not Allow!');
                            }else if(status==419){
                                error('Session Expired')
                            }else{
                                console.log(e);
                                console.error(' Something Went Wrong! Error Status: '+status )
                            };
                        },
                        success: function(response) {
                            if(response.success){
                                success(response.success)
                            }
                        }
                    })
                }

                const orderComponent=(data)=>{
                    let Component=subComponent(data);
                    let saleDetails=data.sale_detail;
                    let fsd=saleDetails[0];
                    return `
                        <div class="col-lg-3 col-md-4 col-sm-6 res_order_card  ${data.order_status=='ready'?'order-2':''}" style="height:390px" data-table="${fsd.sale_with_table.table ?fsd.sale_with_table.table.table_no: data.order_voucher_no}" data-id=${data.id}>
                            ${Component}
                        </div>
                    `
                }

                const subComponent=(data)=>{
                    let items=``;
                    let saleDetails=data.sale_detail;
                    let fsd=saleDetails[0];
                    for (let i = 0; i < 5; i++) {
                        let sd=saleDetails[i];
                        if(sd){
                            items += `
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="fw-semibold">${sd.product.name}</span>
                                    <span class="fw-bold">x ${Number(sd.quantity)}</span>
                                </div>
                            `;
                        }

                    }
                    let moreItem=``;
                    if(saleDetails.length-5 >0){
                        moreItem+=`<div class='d-flex align-items-center py-3 pt-5'>
                            <span class='bullet bg-primary me-3'></span>
                            <em>and ${ saleDetails.length-5  } more...</em>
                        </div>`
                    }
                    console.log(data);
                    let status=statusComponent(data.order_status,data.services);
                    return `
                        <div class="card card-flush h-md-100 cursor-pointer bg-hover-light"  style="hight:40vh" >
                            <div class="card-header ribbon ribbon-top ribbon-vertical">
                                ${status}
                                <div class="card-title mt-5 fw-bold mb-2">${fsd.sale_with_table.table ?fsd.sale_with_table.table.table_no: data.order_voucher_no}</div>

                                <div class="w-100  d-flex justify-content-between mt-5">
                                    <h6 class="fw-bold fs-6 time-count time_div_${data.id}" id="timeCount_${data.id}">00:00</h6>

                                    <h6 class="fw-bold fs-6">Johny</h6>
                                </div>
                                <div class="w-100  d-flex justify-content-between mt-1 mb-10">
                                    <h6 class="fw-bold fs-7">${data.order_voucher_no}</h6>

                                    <h6 class="fw-bold">T-0001</h6>
                                </div>
                            </div>
                            <div class="card-body pt-1">
                                <div class="d-flex flex-column text-gray-800 ">
                                    ${items}
                                    ${moreItem}
                                </div>
                            </div>

                        </div>
                    `
                }


                const detailOrderComponent=(data)=>{
                    let items=``;
                    let saleDetails=data.sale_detail;
                    saleDetails.forEach(sd => {
                        items += productItem(sd.product,sd.quantity);
                    });
                        return `
                            <div class="food">
                                <div class="d-flex justify-content-center px-5 py-3 bg-light">
                                    <div class="">
                                        <h2 class=" fs-6 fw-bold">Food</h2>
                                    </div>
                                </div>
                                <div class="separator separator-dashed"></div>
                                ${items}

                            </div>


                        `
                }
                const productItem=(product,qty)=>{
                    return `
                            <div class="d-flex justify-content-between px-5 py-3">
                                <div class="">
                                    <h2 class=" fs-6 fw-bold">${product.name}</h2>
                                </div>
                                <div class="">
                                    <h2 class=" fs-6 fw-bold">x ${Number(qty)}</h2>
                                </div>
                            </div>
                            <div class="separator separator-dashed"></div>
                    `;
                }
                const statusComponent=(status,service)=>{
                    let statusComponent='';
                    let services={
                        dine_in:'fa-solid fa-utensils fs-4 text-white',
                        take_away:'fa-solid fa-bag-shopping fs-6 text-white',
                        delivery:'fa-solid fa-truck-fast fs-4 text-white'
                    };
                    if(status=='order'){
                        statusComponent= `
                            <div class="ribbon-label  bg-warning status-bg">
                                <i class="fa-solid fa-clock-rotate-left  fs-4 me-2 text-white"></i>
                                <i class="${services[service]}"></i>
                            </div>
                        `
                    }else if(status=='preparing'){
                        statusComponent= `
                            <div class="ribbon-label  bg-primary status-bg">
                               <div>
                                    <i class="fa-regular fa-hourglass-half fs-6 me-3 text-white"></i>
                                <i class="${services[service]}"></i>
                                </div>
                            </div>
                        `
                    }else if(status=='ready'){
                        statusComponent= `
                            <div class="ribbon-label  bg-success status-bg">
                                <i class="fa-regular fa-circle-check fs-4 me-3 text-white"></i>
                                <i class="${services[service]}"></i>
                            </div>
                        `
                    }
                    return statusComponent;
                }



                function getTimeDifference(givenTime) {
                    // Step 1: Parse the given timestamp into a Date object
                    const givenDate = new Date(givenTime);

                    // Step 2: Get the current time as a Date object
                    const currentDate = new Date();

                    // Step 3: Calculate the time difference in milliseconds
                    const timeDifference = currentDate - givenDate;

                    // Step 4: Convert the time difference to minutes and seconds
                    const minutesDifference = Math.floor(timeDifference / 60000);
                    const secondsDifference = Math.floor((timeDifference % 60000) / 1000);

                    return { minutes: minutesDifference, seconds: secondsDifference };
                }
                function startStopwatch(startTime, targetDiv) {
                    let parent=targetDiv.closest('.res_order_card');
                    let targetDataId=parent.data('id');
                    const interval = setInterval(() => {
                        const timeDifference = getTimeDifference(startTime);
                        let minute=timeDifference.minutes.toString().padStart(2, '0') ;
                        const formattedTime = `${minute}:${timeDifference.seconds.toString().padStart(2, '0')} `;
                        targetDiv.html(formattedTime);
                        if(minute>=10){
                            targetDiv.addClass('text-danger');
                        }
                        if(minute>1000){
                            targetDiv.addClass('text-danger');
                            targetDiv.html('expired');
                            clearInterval(interval);
                        }
                    }, 1000);
                    intervalMap.set(targetDataId, interval);
                }
                function stopStopwatch(divId) {
                    const interval = intervalMap.get(divId);
                    if (interval) {
                        clearInterval(interval);
                        intervalMap.delete(divId);
                    }
                }
            </script>
    </body>
    <!--end::Body-->
</html>




{{-- // <div class="d-flex justify-content-between px-5 py-3">
    //     <div class="">
    //         <h2 class=" fs-6 fw-bold">Moh Hingar</h2>
    //     </div>
    //     <div class="">
    //         <h2 class=" fs-6 fw-bold">x 10</h2>
    //     </div>
    // </div>

    // <div class="d-flex px-5 py-3">
    //     <div class="">
    //         <h2 class=" fs-6 fw-bold me-2">note:</h2>
    //     </div>
    //     <div class="">
    //         <h2 class=" fs-6 fw-semibold">
    //             <p>
    //             နံနံပင်မထည့်ပါ။
    //             </p>
    //         </h2>
    //     </div>
    // </div>
    // <div class="separator separator-dashed"></div>
    // <div class="d-flex justify-content-between px-5 py-3">
    //     <div class="">
    //         <h2 class=" fs-6 fw-bold">Bruschetta</h2>
    //     </div>
    //     <div class="">
    //         <h2 class=" fs-6 fw-bold">x 10</h2>
    //     </div>
    // </div> --}}

    {{-- <div class="beverage">
        <div class="d-flex justify-content-center px-5 py-3 bg-light">
            <div class="">
                <h2 class=" fs-6 fw-bold">beverage</h2>
            </div>
        </div>

        <div class="separator separator-dashed"></div>
        <div class="d-flex justify-content-between px-5 py-3">
            <div class="">
                <h2 class=" fs-6 fw-bold">White Rum</h2>
            </div>
            <div class="">
                <h2 class=" fs-6 fw-bold">x 10</h2>
            </div>
        </div>

        <div class="separator separator-dashed"></div>
        <div class="d-flex justify-content-between px-5 py-3">
            <div class="">
                <h2 class=" fs-6 fw-bold"> Air Mail</h2>
            </div>
            <div class="">
                <h2 class=" fs-6 fw-bold">x 2</h2>
            </div>
        </div>
        <div class="separator separator-dashed"></div>
        <div class="d-flex justify-content-between px-5 py-3">
            <div class="">
                <h2 class=" fs-6 fw-bold"> See You Tommorrow</h2>
            </div>
            <div class="">
                <h2 class=" fs-6 fw-bold">x 2</h2>
            </div>
        </div>
    </div> --}}




