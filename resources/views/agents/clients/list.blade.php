<section class="container">
    <div class="row main-clients-section">
        @if ($message = Session::get('success_msg'))
            <div class="msg success_msg mb-5" id="msg">
                <button type="button" class="close modal__btn-close p-0 m-0">
                    <img src="images/close.svg" alt="" class="img-fluid">
                </button>
                <img src="../images/success.svg" alt="" class="modal-img">
                {{ $message }}
            </div>
        @endif
        <div class="main-clients-section__head-block row mx-0 align-items-center justify-content-between col-12 px-0">
            <div class="main-clients-section__title col-sm-4 col-12 text-sm-left text-center p-0">Clients ({{$clientInfo->total()}})</div>
            <div class="main-clients-section__btn-section row mx-0 col-sm-8 col-12 align-items-center justify-content-sm-end justify-content-center p-0">
                <div class="dropdown dropdown__nested-style dropdown__sm-mx-auto ">
                    <span class="property-filter__sort mr-1 ">
                             Sort By
                        </span>
                    <button class=" dropdown-cast__style-1 dropdown-cast__style-2 property-filter__title dropdown-cast__after-arrow pl-0 pr-4 txt-overflow-c strains" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        @if(app('request')->input('so') == 'cm_phone')
                            {{'Contact'}}
                        @elseif(app('request')->input('so') == 'cm_firstname')
                            {{'Name'}}
                        @elseif(app('request')->input('so') == 'cm_is_active')
                            {{'Status'}}
                        @else
                            {{'Sort by'}}
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-cast_2 dropdown-w-lg mixitup-control dropdown--position-c3" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item dropdown-item-cast_2 control clients-opt" href="{{route ('clients.index',['so'=>'cm_firstname','sd'=>'desc'])}}">
                            Name
                        </a>
                        <a class="dropdown-item border-bottom dropdown-item-cast_2 control clients-opt" href="{{route ('clients.index',['so'=>'cm_phone','sd'=>'desc'])}}">
                            Contact
                        </a>
                        <a class="dropdown-item dropdown-item-cast_2 control clients-opt" href="{{route ('clients.index',['so'=>'cm_is_active','sd'=>'desc'])}}">
                            Status
                        </a>
                    </div>
                </div>
                <a href="{{route ('clients.create')}}" class="btn btn-main-big">Create client</a>
            </div>
        </div>

        <div class="w-100 wrapper-table">
            <table class="w-md-100 main-clients-section__main-table table table-responsive-xl mb-4">
                <thead>
                <tr>
                    <th class="">STATUS</th>
                    <th class="pl-c7">CLIENT NAME</th>
                    <th class="">CONTACT</th>
                    <th class="">TYPE</th>
                    {{--<th class="">PROPERTY</th>
                    <th class="">PRICE</th>--}}
                    <th class="">NOTES</th>
                    <th class=""></th>
                </tr>
                </thead>
                <tbody class="result" id="tbody_result">
                    @if($clientInfo->count() > 0)
                        @foreach($clientInfo as $key=>$val)
                            <tr class="main-clients-section__main-table_active">
                                <td class="">
                                    <div class="{{($val->cm_is_active == 'Yes') ? 'main-green-btn main-btn' : 'main-gray-btn main-btn' }}">{{($val->cm_is_active == 'Yes') ? "Active" : "Inactive" }}</div>
                                </td>
                                <td class="position-relative d-flex">
                                    <div class="client-photo">
                                        <img src="@if(isset($val->cm_photo)){{$V_Upload}}/{{$val->cm_photo}}@else{{$public_images}}/no_image.png @endif" alt="" class="img-fluid">
                                    </div>
                                    <div class="client-name ">
                                        {{$val->cm_firstname.''.$val->cm_lastname}}
                                    </div>
                                </td>
                                <td class="position-relative"> <p class="phone">{{str_replace(' ', '',$val->cm_phone)}}</p> <img src="images/icon-mess.svg" alt="" class="main-clients-section__mess-icon"></td>
                                <td class="">{{$val->cm_type}}</td>
                                {{--<td class="main-clients-section__main-table_overflow-text">{{$val->cpi_address}}</td>
                                <td class="">${{$val->cpi_price}},00</td>--}}
                                <td><a href="#" id="cliets-notes-link" class="cliets-notes-link" data-toggle="modal" data-target="#modal-popup-sm" data-value='<form id="client-notes" action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <textarea id="cm_notes" name="cm_notes">{{$val->cm_notes}}</textarea>
                                    <input type="hidden" name="cm_id" value="{{$val->cm_id}}"/>
                                    </form>' data-title="Notes"><img src="images/icon-notes.svg" alt="" class="main-clients-section__notes-pic"></a></td>{{--<p>{{$val->cm_notes}}</p>--}}
                                <td class="">
                                    <div class="dropdown d-flex align-items-end  h-c1">
                                        <button
                                                class="btn btn-secondary dropdown-toggle btn-circle-span reset-dropdown-button "
                                                type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                            <span>.</span>
                                            <span>.</span>
                                            <span>.</span>
                                        </button>
                                        <div class="dropdown-menu  open-modal-menu" aria-labelledby="dropdownDealMain">
                                            <a class="dropdown-item" href="{{route('clients.edit',$val->cm_id)}}"><img src="{{$public_images}}/edit-icon.svg" alt="">Edit</a>
                                            <form action="{{route('clients.isActive','id='.$val->cm_id)}}" method="POST" enctype="multipart/form-data">
                                                <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">

                                                <input type = "hidden" name = "cm_is_active" value = "{{$val->cm_is_active}}">
                                                    <button type="submit" class="dropdown-item">
                                                        <img src="{{$public_images}}/{{($val->cm_is_active == 'Yes') ? 'close-eyes.png' : 'eye-close-up.png' }}" alt="">{{($val->cm_is_active == 'Yes') ? "Inactive" : "Active" }}
                                                    </button>
                                                </form>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-popup-sm" data-value='
                                            <div class="row">
                                               <div class="col-12 col-sm-12 col-md-12 col-xl-12">
                                                 <div class="form-group">
                                                    <p>Are you sure you want to delete this information?</p>
                                                         <form action="{{route('clients.destroy',$val->cm_id)}}" method="POST" enctype="multipart/form-data">
                                                               @csrf
                                                               @method('DELETE')
                                                              <button type="submit" class="btn  main-btn-marketing">Yes</button>
                                                              <button type="button"  class="btn main-btn-marketing " data-dismiss="modal">No</button>
                                                         </form>
                                                 </div>
                                               </div>
                                            </div>' data-title="Remove"><img src="{{$public_images}}/remove-icon.svg" alt="">Remove</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="main-clients-section__main-table_active">
                            <td colspan="7">
                                <h2 class="text-center">No data found</h2>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @if($clientInfo->count() > 0)
            <div class="row w-100">
                <div class="col-6 col-sm-6 col-md-6 col-xl-6 text-left">
                        {!! $clientInfo->links()  !!}
                </div>
                <div  class="col-6 col-sm-6 col-md-6 col-xl-6 text-right">
                    {{--Showing {{$clientInfo->count()}} of {{$total_client_cnt}} results--}}
                    Showing {{$clientInfo->firstItem()}} - {{$clientInfo->lastItem()}} of {{$clientInfo->total()}}
                </div>
            </div>
        @endif
    </div>
</section>

