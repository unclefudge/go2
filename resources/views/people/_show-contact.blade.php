<div class="m-portlet">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">Contact Details</h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <button type="button" class="m-portlet__nav-link btn btn-sm btn-outline-accent m-btn--pill" data-toggle="modal" data-target="#modal_profile">Edit</button>
                </li>
                <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">
                    <a href="#" class="m-portlet__nav-link m-dropdown__toggle btn btn-sm btn-accent m-btn m-btn--pill">Settings</a>
                    <div class="m-dropdown__wrapper" style="z-index: 101;">
                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 47px;"></span>
                        <div class="m-dropdown__inner">
                            <div class="m-dropdown__body">
                                <div class="m-dropdown__content">
                                    <ul class="m-nav">
                                        <li class="m-nav__section m-nav__section--first">
                                            <span class="m-nav__section-text">Quick Actions</span>
                                        </li>
                                        <li class="m-nav__item">
                                            <a href="" class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                <span class="m-nav__link-text">Activity</span>
                                            </a>
                                        </li>
                                        <li class="m-nav__item">
                                            <a href="" class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                <span class="m-nav__link-text">Messages</span>
                                            </a>
                                        </li>
                                        <li class="m-nav__separator m-nav__separator--fit">
                                        </li>
                                        <li class="m-nav__item">
                                            <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">Cancel</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    @include('form-error')
    <div class="m-portlet__body">
        <div class="row">
            <div class="col-md-3">Name:</div>
            <div class="col-xs-9">{!! $people->name !!}</div>
        </div>
        <hr class="field-hr">

        <div class="row">
            <div class="col-md-3">Type:</div>
            <div class="col-xs-9">{!! $people->type !!}</div>
        </div>
        <hr class="field-hr">

        <div class="row">
            <div class="col-md-3">Phone:</div>
            <div class="col-xs-9">{!! ($people->phone) ? "<a href='tel:'".preg_replace("/[^0-9]/", "", $people->phone)."> $people->phone </a>" : '-' !!}</div>
        </div>
        <hr class="field-hr">
        <div class="row">
            <div class="col-md-3">Email:</div>
            <div class="col-xs-9">{!! ($people->email) ? "<a href='mailto:$people->email'> $people->email</a>" : '-' !!}</div>
        </div>
        <hr class="field-hr">
        <div class="row">
            <div class="col-md-3">Address:</div>
            <div class="col-xs-9">{!! $people->address_formatted !!}</div>
        </div>

        <hr class="field-hr">
        <div class="row">
            <div class="col-md-3">Private Notes:</div>
            <div class="col-xs-9">@if($people->notes){!! nl2br($people->notes) !!} </a>@else - @endif
            </div>
        </div>
        <hr class="field-hr">
    </div>
</div>

{{-- Edit Profile Modal --}}
<div class="modal fade" id="modal_profile" tabindex="-1" role="dialog" aria-labelledby="Profile" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: #F7F7F7">
            {!! Form::model($people, ['method' => 'PATCH', 'action' => ['People\PeopleController@update', $people->id]]) !!}
            <div class="modal-header" style="background: #32c5d2">
                <h5 class="modal-title text-white" id="ModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- First + Last Name --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group {!! fieldHasError('firstname', $errors) !!}">
                            {!! Form::label('firstname', 'First', ['class' => 'form-control-label']) !!}
                            {!! Form::text('firstname', null, ['class' => 'form-control m-input']) !!}
                            {!! fieldErrorMessage('firstname', $errors) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! fieldHasError('lastname', $errors) !!}">
                            {!! Form::label('lastname', 'Last', ['class' => 'control-label']) !!}
                            {!! Form::text('lastname', null, ['class' => 'form-control', 'required']) !!}
                            {!! fieldErrorMessage('lastname', $errors) !!}
                        </div>
                    </div>
                </div>
                {{-- Email --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {!! fieldHasError('email', $errors) !!}">
                            {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
                            {!! Form::text('email', null, ['class' => 'form-control', 'required']) !!}
                            {!! fieldErrorMessage('email', $errors) !!}
                        </div>
                    </div>
                </div>
                {{-- Phone --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {!! fieldHasError('phone', $errors) !!}">
                            {!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}
                            {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                            {!! fieldErrorMessage('phone', $errors) !!}
                        </div>
                    </div>
                </div>

                {{-- Adddress --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {!! fieldHasError('address', $errors) !!}">
                            {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'street address']) !!}
                        </div>
                    </div>
                </div>
                {{-- Suburb + State + Postcode --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! fieldHasError('suburb', $errors) !!}">
                            {!! Form::label('suburb', 'Suburb', ['class' => 'control-label']) !!}
                            {!! Form::text('suburb', null, ['class' => 'form-control']) !!}
                            {!! fieldErrorMessage('suburb', $errors) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {!! fieldHasError('state', $errors) !!}">
                            {!! Form::label('state', 'State', ['class' => 'control-label']) !!}
                            {!! Form::select('state', $ozstates::all(), 'NSW', ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                            {!! fieldErrorMessage('state', $errors) !!}
                        </div>
                    </div>
                    {{-- Postcode --}}
                    <div class="col-md-3">
                        <div class="form-group {!! fieldHasError('postcode', $errors) !!}">
                            {!! Form::label('postcode', 'Postcode', ['class' => 'control-label']) !!}
                            {!! Form::text('postcode', null, ['class' => 'form-control']) !!}
                            {!! fieldErrorMessage('postcode', $errors) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

