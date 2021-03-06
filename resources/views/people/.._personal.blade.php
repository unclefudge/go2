{{-- Personal Info --}}
<div class="m-portlet">
    <div class="m-portlet__body">
        <div class="row" style="padding-bottom: 10px">
            <div class="col-10"><h4>Personal Info</h4></div>
            @if ($user->status)
                <div class="col-2"><a href="#" class="pull-right" data-toggle="modal" data-target="#modal_personal">Edit</a></div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="row" style="padding: 5px 0px">
                    <div class="col-1">
                        @if ($user->gender == 'Male')
                            <i class="fa fa-2x fa-male"></i>
                        @elseif ($user->gender == 'Female')
                            <i class="fa fa-2x fa-female" style="padding-right: 5px"></i>
                        @else
                            <i class="fa fa-user" style="padding-right: 5px"></i>
                        @endif
                    </div>
                    <div class="col">
                        {{ $user->type }} <br>
                        {!! ($user->dob) ? "$user->age years old &nbsp; (".$user->dob->format('j M Y').") <br>" : '' !!}
                    </div>
                </div>

            </div>
            <div class="col-md-7">
                <div class="row" style="padding: 5px 0px">
                    <div class="col-1 col-lg-3"><i class="fa fa-envelope" style="padding-right: 4px"></i><span class="d-none d-lg-inline">Email</span></div>
                    <div class="col col-lg-9">{!! ($user->email) ? "<a href='mailto:$user->email'> $user->email</a>" : '-' !!}</div>
                </div>
                <div class="row" style="padding: 5px 0px">
                    <div class="col-1 col-lg-3"><i class="fa fa-phone" style="padding-right: 4px"></i><span class="d-none d-lg-inline">Phone</span></div>
                    <div class="col col-lg-9">{!! ($user->phone) ? "<a href='tel:'".preg_replace("/[^0-9]/", "", $user->phone)."> $user->phone </a>" : '-' !!}</div>
                </div>
                <div class="row" style="padding: 5px 0px">
                    <div class="col-1 col-lg-3"><i class="fa fa-map-marker-alt" style="padding-right: 5px"></i><span class="d-none d-lg-inline">Address</span></div>
                    <div class="col col-lg-9">{!! ($user->address_formatted) ? $user->address_formatted : '-' !!}</div>
                </div>

            </div>
        </div>

        <hr class="field-hr">

        {{-- Student --}}
        @if ($user->type == 'Student')
            <div class="row">
                {{-- School --}}
                <div class="col-md-5">
                    <div class="row" style="padding: 5px 0px">
                        <div class="col-10"><h5>School</h5></div>
                        <div class="col-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-1"><i class="fa fa-apple-alt"></i></div>
                        <div class="col">{!! ($user->grade_id) ? "$user->grade_name" : '-' !!}<br>{!! $user->school_name !!}</div>
                    </div>
                </div>

                {{-- Media --}}
                <div class="col-md-7">
                    <div class="row" style="padding: 5px 0px">
                        <div class="col-10"><h5>Media Consent</h5></div>
                        <div class="col-2"><!--<a href="#" class="pull-right" data-toggle="modal" data-target="#modal_profile"> <i class="fa fa-edit"></i></a>--></div>
                    </div>
                    <div class="row">
                        <div class="col-1">{!! ($user->media_consent == 'y') ? '<i class="fa fa-user m--font-success"></i>' : '<i class="fa fa-user-slash m--font-danger"></i>'!!}</div>
                        <div class="col">
                            @if ($user->media_consent)
                                <?php $media_action = ($user->media_consent == 'y') ? 'Consent given' : 'Media denied' ?>
                                 {{ $media_action }} by {!! $user->mediaConsentByUser->name.' ('.$user->media_consent_at->format(session('df')).')' !!}
                                @else
                                No Media Consent
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Volunteer --}}
        @if ($user->type == 'Volunteer' || $user->type == 'Parent/Volunteer')
            <div class="row">
                {{-- School --}}
                <div class="col-md-12">
                    <div class="row" style="padding: 5px 0px">
                        <div class="col-10"><h5>WWC Registration</h5></div>
                        <div class="col-2"></div>
                    </div>
                    <div class="row">
                        <div class="col-2">No.</div>
                        <div class="col">{!! ($user->wwc_no) ? "$user->wwc_no" : '-' !!}</div>
                    </div>
                    <div class="row">
                        <div class="col-2">Expiry</div>
                        <div class="col">{!! ($user->wwc_exp) ? $user->wwc_exp->format(session('df')) : '' !!}</div>
                    </div>
                    <div class="row">
                        <div class="col">{!! ($user->wwc_verified_by) ? "<br>Verified by ".$user->wwcVerifiedByUser->name." on ".$user->wwc_verified->format(session('df')) : '<br><span class="m--font-danger">Not Verified Yet</span>' !!} </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>


{{-- Edit Profile Modal --}}
<div class="modal fade" id="modal_personal" tabindex="-1" role="dialog" aria-labelledby="Profile" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {!! Form::model($user, ['method' => 'PATCH', 'action' => ['People\PeopleController@update', $user->id]]) !!}
            <div class="modal-header" style="background: #32c5d2">
                <h5 class="modal-title text-white" id="ModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #F7F7F7; padding:20px; border-bottom: 1px solid #ddd">
                {{--@include('form-error')--}}
                {{-- First + Last Name --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group m-form__group {!! fieldHasError('firstname', $errors) !!}">
                            {!! Form::label('firstname', 'First Name', ['class' => 'form-control-label']) !!}
                            {!! Form::text('firstname', null, ['class' => 'form-control', 'required']) !!}
                            {!! fieldErrorMessage('firstname', $errors) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! fieldHasError('lastname', $errors) !!}">
                            {!! Form::label('lastname', 'Last Name', ['class' => 'control-label']) !!}
                            {!! Form::text('lastname', null, ['class' => 'form-control', 'required']) !!}
                            {!! fieldErrorMessage('lastname', $errors) !!}
                        </div>
                    </div>
                </div>
                {{-- Phone + Email --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {!! fieldHasError('phone', $errors) !!}">
                            {!! Form::label('phone', 'Phone', ['class' => 'control-label']) !!}
                            {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                            {!! fieldErrorMessage('phone', $errors) !!}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group {!! fieldHasError('email', $errors) !!}">
                            {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
                            {!! Form::text('email', null, ['class' => 'form-control']) !!}
                            {!! fieldErrorMessage('email', $errors) !!}
                        </div>
                    </div>
                </div>

                {{-- Adddress --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {!! fieldHasError('address', $errors) !!}">
                            {!! Form::label('address', 'Address', ['class' => 'control-label']) !!}
                            {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'street address']) !!}
                            {!! fieldErrorMessage('address', $errors) !!}
                        </div>
                    </div>
                </div>
                {{-- Suburb + State + Postcode --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! fieldHasError('suburb', $errors) !!}">
                            {!! Form::text('suburb', null, ['class' => 'form-control', 'placeholder' => 'suburb']) !!}
                            {!! fieldErrorMessage('suburb', $errors) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {!! fieldHasError('state', $errors) !!}">
                            {!! Form::select('state', $ozstates::all(), 'TAS', ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                            {!! fieldErrorMessage('state', $errors) !!}
                        </div>
                    </div>
                    {{-- Postcode --}}
                    <div class="col-md-3">
                        <div class="form-group {!! fieldHasError('postcode', $errors) !!}">
                            {!! Form::text('postcode', null, ['class' => 'form-control', 'placeholder' => 'postcode']) !!}
                            {!! fieldErrorMessage('postcode', $errors) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body" style="padding:20px; border-bottom: 1px solid #ddd">
                {{-- Additional Info --}}
                <div class="row">
                    {{-- Gender --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group {!! fieldHasError('gender', $errors) !!}">
                            {!! Form::label('gender', 'Gender', ['class' => 'control-label']) !!}
                            {!! Form::select('gender', ['' => 'Gender', 'Male' => 'Male', 'Female' => 'Female'], null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                            {!! fieldErrorMessage('gender', $errors) !!}
                        </div>
                    </div>
                    {{-- Birthday --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="form-group {!! fieldHasError('dob', $errors) !!}">
                            {!! Form::label('dob', 'Birthday', ['class' => 'control-label']) !!}
                            <div class="input-group date">
                                {!! Form::text('dob', ($user->dob) ? $user->dob->format(session('df')) : '', ['class' => 'form-control m-input', 'style' => 'background:#FFF', 'placeholder' => session('df-datepicker'), 'id' => 'dob']) !!}
                            </div>
                            {!! fieldErrorMessage('dob', $errors) !!}
                        </div>
                    </div>

                    {{-- Spacer --}}
                    <div class="col-lg-2">
                    </div>

                    {{-- Type --}}
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group {!! fieldHasError('type', $errors) !!}">
                            {!! Form::label('type', 'Type', ['class' => 'control-label']) !!}
                            {!! Form::select('type', \App\User::types(), null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                            {!! fieldErrorMessage('type', $errors) !!}
                        </div>
                    </div>
                </div>

                {{-- Student Info --}}
                <div id="fields_student">
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-12">
                            <br><h6>Student Details</h6>
                        </div>
                        {{-- Grade --}}
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group {!! fieldHasError('grade_id', $errors) !!}">
                                {!! Form::label('grade_id', 'Grade', ['class' => 'control-label']) !!}
                                {!! Form::select('grade_id', $user->account->gradesSelect('prompt'), null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                                {!! fieldErrorMessage('grade_id', $errors) !!}
                            </div>
                        </div>
                        {{-- School --}}
                        <div class="col-lg-5 col-md-8">
                            <div class="form-group">
                                <label for="school_id" class="control-label">School <span id="loader" style="visibility: hidden"><i class="fa fa-spinner fa-spin"></i></span></label>
                                <select name="school_id" class="form-control select2" id="school_id">
                                    @foreach ($user->account->schools->sortBy('name') as $key => $value)
                                        <option value="{{ $key }}" {{ ($user->school_id == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- Media Consent --}}
                        <div class="col-lg-2 col-md-3">
                            <div class="form-group {!! fieldHasError('media_consent', $errors) !!}">
                                {!! Form::label('media_consent', 'Media Consent', ['class' => 'control-label']) !!}
                                {!! Form::select('media_consent', ($user->media_consent) ? ['y' => 'Yes', 'n' => 'No'] : ['' => 'Select', 'y' => 'Yes', 'n' => 'No'], null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                                {!! fieldErrorMessage('media_consent', $errors) !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Volunteer Info --}}
                <div id="fields_volunteer">
                    <hr>
                    <div class="row">
                        <div class="col-lg-3 col-md-12">
                            <br><h6>WWC Registration</h6>
                        </div>
                        {{-- WWC No. --}}
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group {!! fieldHasError('wwc_no', $errors) !!}">
                                {!! Form::label('wwc_no', 'No.', ['class' => 'control-label']) !!}
                                {!! Form::text('wwc_no', null, ['class' => 'form-control']) !!}
                                {!! fieldErrorMessage('wwc_no', $errors) !!}
                            </div>
                        </div>
                        {{-- WWC Expiry --}}
                        <div class="col-lg-3 col-md-6">
                            <div class="form-group {!! fieldHasError('wwc_exp', $errors) !!}">
                                {!! Form::label('wwc_exp', 'Expiry', ['class' => 'control-label']) !!}
                                <div class="input-group date">
                                    {!! Form::text('wwc_exp', ($user->wwc_exp) ? $user->wwc_exp->format(session('df')) : '', ['class' => 'form-control m-input', 'style' => 'background:#FFF', 'placeholder' => session('df-datepicker'), 'id' => 'wwc_exp']) !!}
                                </div>
                                {!! fieldErrorMessage('wwc_exp', $errors) !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer" style="background-color: #F7F7F7; padding: 20px">
                <button type="button" class="btn btn-secondary" style="border: 0; background: #F7F7F7" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


