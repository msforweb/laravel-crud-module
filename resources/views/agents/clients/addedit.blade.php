<section class="container">
    <div class="row title-page-section">
        <div class="title-page"><a href="{{url ('clients')}}">Client Info</a> </div>
    </div>
    @if ($errors->any())
        <div class="msg error_msg" id="msg">
            <button type="button" class="close modal__btn-close p-0 m-0">
                <img src="{{$public_images}}/close.svg" alt="" class="img-fluid">
            </button>
            <img src="{{$public_images}}/error.svg" alt="" class="modal-img">
            @foreach ($errors->all() as $error)
                {{$error}}<br/>
            @endforeach
        </div>
    @endif
    <form id="form" action="@if(empty($editclientlist)){{route('clients.store')}}@else{{route ('clients.update', $editclientlist->cm_id )}}@endif" method="POST" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                @isset($editclientlist)
                    @method('PUT')
                @endisset

                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 clearfix">
                        <div class="form-group">
                            <label for="cm_photo">Photo</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input extension" name="cm_photo" value="@isset($editclientlist){{str_replace(' ', '', $editclientlist->cm_photo)}}@endisset{{old ('cm_photo')}}" id="cm_photo" aria-describedby="cm_photo" extension="jpg|jpeg|png|gif" maxsize="1KB">
                                <label class="custom-file-label" id="ImageName" for="ImageName">  @if(isset($editclientlist)){{str_replace(' ', '', $editclientlist->cm_photo)}} @else Choose File @endif</label>
                                 <p class="note">Valid file format :.jpg,.jpeg,.png,.gif<br>Maximum File Size : 16MB</p>
                            </div>
                            @isset($editclientlist)
                                    <img src="{{$public_images}}/agents/clients/{{$editclientlist->cm_photo}}" alt="" width="50" height="50" class="img-fluid">
                                     <div class="form-check">
                                         <input class="form-check-input" type="checkbox" name="rm_cm_photo" value="true" id="defaultCheck1">
                                            <label class="form-check-label" for="defaultCheck1">
                                                Remove Image
                                            </label>
                                         <input type="hidden" name="hd_cm_photo" value="@if(isset($editclientlist)){{str_replace(' ', '', $editclientlist->cm_photo)}}@endif">
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="cm_firstname">First Name</label>
                            <input id="cm_firstname" type="text" name="cm_firstname"  value="@isset($editclientlist){{str_replace(' ', '', $editclientlist->cm_firstname)}}@endisset{{old('cm_firstname')}}" placeholder="First Name" autofocus="autofocus" class="form-control " required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="cm_lastname">Last Name</label>
                            <input id="cm_lastname" type="text" name="cm_lastname" value="@isset($editclientlist){{str_replace(' ', '', $editclientlist->cm_lastname)}}@endisset{{old('cm_lastname')}}" placeholder="Last Name" autofocus="autofocus" class="form-control " required>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="cm_email">Email Address</label>
                            <input type="text" class="form-control  email" value="@isset($editclientlist){{str_replace(' ', '', $editclientlist->cm_email)}}@else{{old('cm_email')}}@endisset" id="cm_email" aria-describedby="emailHelp"
                                   placeholder="Email Address" name="cm_email" required >
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="cm_type">Client Type</label>
                            <select class="form-control custom-select" id="cm_type" name="cm_type" required>
                                <option value="">Client Type</option>
                                @foreach($client_type as $key=>$val)
                                    <option @if((isset($editclientlist) && str_replace(' ', '', $editclientlist->cm_type) == $key) || old('cm_type') == $key) selected @endif value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="cm_phone">Phone Number</label>
                            <input id="cm_phone" type="text" name="cm_phone" value="@isset($editclientlist){{str_replace(' ', '', $editclientlist->cm_phone)}}@endisset{{old('cm_phone')}}" placeholder="Phone Number" required="required" autofocus="autofocus" class="form-control phoneUS" minlength="10" maxlength="10">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="cm_notes">Notes</label>
                            <textarea type="text" class="form-control" value="{{old ('cm_notes')}}" placeholder="Put your notes here" id="cm_notes" rows="5" name="cm_notes" spellcheck="true">@isset($editclientlist){{str_replace(' ', '', $editclientlist->cm_notes)}}@endisset {{old ('cm_notes')}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="cm_type">Is Active?</label>
                            <select class="form-control custom-select" id="cm_is_active" name="cm_is_active" required>
                                @foreach($OL_YesNo as $key=>$val)
                                    <option @if((isset($editclientlist) && str_replace(' ', '', $editclientlist->cm_is_active) == $key) || old('cm_is_active') == $key) selected @endif value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-xl-12">
                        <div class="form-group">
                            <button type="submit" class="btn order-section-first__form-btn-submit main-btn-marketing">@if(empty($editclientlist)){{'Save'}}@else{{'Update'}}@endif</button>
                            <button type="reset" onclick="location.href = '{{url ('clients')}}';" class="btn order-section-first__form-btn-cansel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>