@isset($data)
    @foreach($data as $key=>$val)
        <tr class="main-clients-section__main-table_active">
            <td class="">
                <div class="{{($val->cm_is_active == 'Yes') ? 'main-green-btn main-btn' : 'main-gray-btn main-btn' }} ">{{($val->cm_is_active == 'Yes') ? "Active" : "Inactive" }}</div>
            </td>
            <td class="position-relative d-flex">
                <div class="client-photo">
                    <img src="images/agents/{{$val->cm_photo}}" alt="" class="img-fluid">
                </div>
                <div class="client-name ">
                    {{$val->cm_firstname.''.$val->cm_lastname}}
                </div>

            </td>
            <td class="position-relative phone">{{$val->cm_phone}} <img src="images/icon-mess.svg" alt=""
                                                                        class="main-clients-section__mess-icon"></td>
            <td class="">{{$val->cm_type}}</td>
            <td class="main-clients-section__main-table_overflow-text">{{$val->cpi_address}}</td>
            <td class="">${{$val->cpi_price}},00</td>
            <td><a href="#" class="cliets-notes-link" data-toggle="modal" data-target=".bd-example-modal-xl" data-id="{{$val->cm_notes}}"><img src="images/icon-notes.svg" alt=""
                                                                                                                                               class="main-clients-section__notes-pic"></a></td>
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
                        <a class="dropdown-item" href="{{route('clients.edit',$val->cm_id)}}"><img src="images/edit-icon.svg"
                                                                                                   alt="">Edit</a>
                        <a class="dropdown-item" href="#"><img src="images/icon-modal.svg"
                                                               alt="">Advance
                            Overview</a>
                        <a class="dropdown-item" href="#"><img src="images/remove-icon.svg"
                                                               alt="">Remove</a>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
@endisset
