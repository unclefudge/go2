@inject('ozstates', 'App\Http\Utilities\Ozstates')
@extends('layouts/checkin')

@section('content')
    <style>
        body, html {
            @if ($instance->event->background)
                  background-image: url("{!! $instance->event->background_path !!}") !important;
            @endif
                  height: 100%; /* set height */

            /* Create the parallax scrolling effect */
            background-attachment: fixed !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 50px;
            background-color: white;
            color: black;
        }

        .search-row {
            cursor: pointer;
        }

        .search-row:hover {
            background: #F8F9FB;
        }

        .search-title {
            font-size: 1.1rem;
            font-weight: bolder;
            margin-bottom: 3px
        }

        .search-info {
            font-size: .8rem;
        }
    </style>
    <div>
        <div class="row text-white" style="height:70px; background: rgb(61, 59, 86)">
            <div class="col text-center">
                <img src="/img/logo-med.png" style="float: left; padding:5px 0px 5px 20px">
                <h1 style="padding-top: 10px">{{ $instance->name }} <span class="pull-right" style="font-size: 14px; padding-right: 20px">{!! \Carbon\Carbon::now()->timezone(session('tz'))->format(session('df'). " g:i a") !!}</span></h1>
            </div>
        </div>

        {{-- Student Rego  --}}
        <div class="row justify-content-lg-center" style="padding: 30px">
            <div class="col-lg-8">
                <div class="m-portlet">
                    <div class="m-portlet__body">
                        {!! Form::model('people', ['action' => ['Event\CheckinController@studentRegister', $instance->id], 'files' => true]) !!}
                        @include('form-error')

                        <div class="row" style="padding-bottom: 10px">
                            <div class="col-md-5">
                                <h4>Student Registration</h4>
                            </div>
                            <div class="col-md-7">
                                <a href="/checkin/{{ $instance->id }}/register/volunteer" class="btn btn-brand pull-right" style="margin-left: 20px">Switch to Volunteer</a>
                                <a href="/checkin/{{ $instance->eid }}" class="btn btn-secondary pull-right">Return to check-in</a>
                            </div>
                        </div>
                        {{-- First + Last Name --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-form__group {!! fieldHasError('firstname', $errors) !!}">
                                    <label for="firstname" class="form-control-label">First Name {!! REQUIRED_FIELD !!}</label>
                                    {!! Form::text('firstname', null, ['class' => 'form-control', 'required']) !!}
                                    {!! fieldErrorMessage('firstname', $errors) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {!! fieldHasError('lastname', $errors) !!}">
                                    <label for="lastname" class="form-control-label">Last Name {!! REQUIRED_FIELD !!}</label>
                                    {!! Form::text('lastname', null, ['class' => 'form-control', 'required']) !!}
                                    {!! fieldErrorMessage('lastname', $errors) !!}
                                </div>
                            </div>
                        </div>
                        {{-- Gender + DOB --}}
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
                                        {!! Form::text('dob', null, ['class' => 'form-control m-input', 'style' => 'background:#FFF', 'placeholder' => session('df-datepicker'), 'id' => 'dob']) !!}
                                        {!! fieldErrorMessage('dob', $errors) !!}
                                    </div>
                                    {!! fieldErrorMessage('dob', $errors) !!}
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

                        {{-- Grade + School --}}
                        <div class="row" style="background-color: #F7F7F7; margin: 10px -25px; padding: 10px 30px">
                            <div class="col-md-3">
                                <br><h6>School details</h6>
                            </div>
                            {{-- Grade --}}
                            <div class="col-md-4">
                                <div class="form-group {!! fieldHasError('grade', $errors) !!}">
                                    {!! Form::label('grade', 'Grade', ['class' => 'control-label']) !!}
                                    {!! Form::select('grade', ['' => 'Select grade', '5' => 'Grade 5', '6' => 'Grade 6', '7' => 'Grade 7', '8' => 'Grade 8', '9' => 'Grade 9', '10' => 'Grade 10', '11' => 'Grade 11', '12' => 'Grade 12'], null, ['class' => 'form-control m-bootstrap-select m_selectpicker']) !!}
                                    {!! fieldErrorMessage('grade', $errors) !!}
                                </div>
                            </div>
                            {{-- School --}}
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="school_id" class="control-label">School <span id="loader" style="visibility: hidden"><i class="fa fa-spinner fa-spin"></i></span></label>
                                    <select name="school_id" class="form-control select2" id="school_id">
                                        @foreach (\App\Models\Account\Account::find(1)->schools->sortBy('name') as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{---------------------}}
                        {{-- Vue Parent Info --}}
                        {{---------------------}}
                        <div id="parent_info">
                            <input v-model="xx.parent.id" type="hidden" name="parent_id" value="{{ old('parent_id', 0) }}">

                            {{-- Parent / Guardian Search --}}
                            <div class="row">
                                <div v-if="xx.parent_search" class="col-12">
                                    <div class="{!! fieldHasError('parent_id', $errors) !!}">
                                        <label for="parent_name" class="form-control-label">Parent / Guardian Name {!! REQUIRED_FIELD !!}</label>
                                        <div class="input-group">
                                            <input v-on:click="xx.search_options = true" v-model="xx.searchQuery" type="search" class="form-control m-input" name="parent_name" placeholder="Search for parent" value="{{ old('parent_name', '' ) }}">
                                            <div class="input-group-append"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
                                        </div>
                                        {!! fieldErrorMessage('parent_id', $errors) !!}
                                    </div>

                                    {{-- Search for household --}}
                                    <div v-if="xx.search_options">
                                        <search-parent :data="xx.parents" :filter-key="xx.searchQuery"></search-parent>
                                    </div>

                                </div>
                            </div>

                            {{-- Parent / Guardian Add --}}
                            <div v-if="xx.parent_add">
                                {{-- Parent First + Last Name --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group m-form__group {!! fieldHasError('parent_firstname', $errors) !!}">
                                            <label for="parent_firstname" class="form-control-label">Parent / Guardian First Name {!! REQUIRED_FIELD !!}</label>
                                            <input v-model="xx.parent.firstname" v-on:click="xx.parent.id = 'add'" type="text" name="parent_firstname" id="parent_firstname" class="form-control" required>
                                            {!! fieldErrorMessage('parent_firstname', $errors) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {!! fieldHasError('parent_lastname', $errors) !!}">
                                            <label for="parent_lastname" class="form-control-label">Parent / Guardian Last Name {!! REQUIRED_FIELD !!}</label>
                                            <input v-model="xx.parent.lastname" v-on:click="xx.parent.id = 'add'" type="text" name="parent_lastname" id="parent_lastname" class="form-control" required>
                                            {!! fieldErrorMessage('parent_lastname', $errors) !!}
                                        </div>
                                    </div>
                                </div>
                                {{-- Parent Phone + Email --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group {!! fieldHasError('parent_phone', $errors) !!}">
                                            <label for="parent_phone" class="form-control-label">Phone {!! REQUIRED_FIELD !!}</label>
                                            {!! Form::text('parent_phone', null, ['class' => 'form-control', 'required']) !!}
                                            {!! fieldErrorMessage('parent_phone', $errors) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group {!! fieldHasError('parent_email', $errors) !!}">
                                            <label for="parent_email" class="form-control-label">Email {!! REQUIRED_FIELD !!}</label>
                                            {!! Form::text('parent_email', null, ['class' => 'form-control', 'required']) !!}
                                            {!! fieldErrorMessage('parent_email', $errors) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--<pre>@{{ $data }}</pre> -->

                        </div>
                        <hr>
                        {{-- Photo --}}
                        <div class="row">
                            <div class="col-md-12">
                                <span class="{!! fieldHasError('photo', $errors) !!}" style="font-size: 16px">{!! fieldErrorMessage('photo', $errors) !!}</span>
                                <div class="slim {!! fieldHasError('photo', $errors) !!}" data-ratio="1:1" data-push=true data-size="400,400" data-label="<i class='fa fa-camera fa-4x'></i><br>Click and smile!">
                                    <input type="file" name="photo"/>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Register</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="footer">
            <p><a href="/event" class="btn btn-secondary">Back to Events</a></p>
        </div>
    </div>

    {{--  Search template --}}
    <script type="text/x-template" id="search-template">
        <table width="100%" style="background: #fff">
            <tbody>
            <tr v-if="xx.searchQuery" v-on:click="searchSelect()" class="search-row" style="border: solid 1px #eee;">
                <td colspan="2" style="padding: 10px 0px 5px 20px">
                    <div class="search-title">ADD NEW PARENT <span class="search-info">(not listed below)</span></div>
                </td>
            </tr>
            <template v-for="person in filteredData">
                <tr v-on:click="searchSelect(person)" class="search-row" style="border: solid 1px #eee;">
                    <td style="padding: 5px 0px 5px 20px">
                        <div class="search-title">@{{ person.name }}</div>
                        <span v-if="person.phone" class="search-info"><i class="fa fa-phone" style="padding-right: 5px"></i> @{{ person.phone }}</span>
                        <span v-if="person.phone && person.email" class="search-info"> &nbsp; &nbsp;</span>
                        <span v-if="person.email" class="search-info"><i class="fa fa-envelope" style="padding-right: 5px"></i> @{{ person.email }}</span>
                    </td>
                    <td style="padding:5px 15px 5px 20px">
                        <div class="search-title">&nbsp;</div>
                        <div class="search-info text-right">
                            <span v-if="person.suburb">@{{ person.suburb }}</span>
                            <span v-if="person.suburb && person.state">, </span>
                            <span v-if="person.state">@{{ person.state }}</span>
                        </div>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </script>

@stop


@section('vendor-scripts')
@stop

@section('page-styles')
    <link href="/css/slim.min.css" rel="stylesheet">
@stop

@section('page-scripts')  {{-- Metronic + custom Page Scripts --}}
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-select.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="/assets/demo/default/custom/crud/forms/widgets/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="/js/slim.kickstart.min.js"></script>
<script src="/js/vue.min.js"></script>
<script type="text/javascript">

    // Form errors - show modal
    if ($('#formerrors').val() == 'personal')
        $('#modal_personal').modal('show');

    display_fields();

    function display_fields() {
        var type = 'Student';

        // Dynamic School dropdown from Grade
        $("#school_id").select2({width: '100%', minimumResultsForSearch: -1});
        var grade = $("#grade").val();
        var school = $("#school_id").val();
        if (grade) {
            $.ajax({
                url: '/data/schools-by-grade/' + grade,
                type: "GET",
                dataType: "json",
                beforeSend: function () {
                    $('#loader').css("visibility", "visible");
                },

                success: function (data) {
                    $("#school_id").empty();
                    $("#school_id").append('<option value="">Select school</option>');

                    var school_names = [];
                    $.each(data, function (key, value) {
                        school_names.push(value);
                    });
                    school_names.sort();
                    var other_key = 0;
                    for (var i = 0; i < school_names.length; i++) {
                        var val = school_names[i];
                        var key = Object.keys(data)[Object.values(data).indexOf(school_names[i])];
                        if (val == 'Other') {
                            other_key = key;
                        } else {
                            if (school == key)
                                $("#school_id").append('<option value="' + key + '" selected>' + val + '</option>');
                            else
                                $("#school_id").append('<option value="' + key + '">' + val + '</option>');
                        }
                    }
                    // Append Other to end of list
                    if (school == 'Other')
                        $("#school_id").append('<option value="' + other_key + '" selected>Other</option>');
                    else
                        $("#school_id").append('<option value="' + other_key + '">Other</option>');
                },
                complete: function () {
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $("#school_id").empty();
            $("#school_id").append('<option value="">Select grade first</option>');
        }
    }

    $("#grade").change(function () {
        display_fields();
    });

    // DOB
    $("#dob").datepicker({
        todayHighlight: !0,
        orientation: "bottom left",
        autoclose: true,
        clearBtn: true,
        format: "{{ session('df-datepicker') }}",
    });

</script>
{{-- Student Vue Code --}}
<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    var xx = {
        parent_search: true, parent_add: false, search_options: true,
        searchQuery: "{!! old('parent_name', app('request')->input('query') )!!}",
        parent: {
            id: "{{ old('parent_id', 0) }}",
            firstname: "{{ old('parent_firstname') }}",
            lastname: "{{ old('parent_lastname') }}"
        },
        parents: [], date: new Date(2016, 9, 16),
    };

    // register the search member component
    Vue.component('search-parent', {
        template: '#search-template',
        props: {data: Array, filterKey: String},
        data: function () {
            return {xx: xx}
        },
        computed: {
            filteredData: function () {
                var filterKey = this.filterKey && this.filterKey.toLowerCase()
                var data = this.data
                if (filterKey) {
                    data = data.filter(function (row) {
                        return Object.keys(row).some(function (key) {
                            return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                        })
                    })
                    return data.slice(0, 10); // Return first x records only
                }
                return []; // Return no records unless search string contains characters
            }
        },
        methods: {
            searchSelect: function (person) {
                if (!person) {
                    this.xx.parent.id = 'add'
                    this.xx.parent.firstname = xx.searchQuery.substr(0, xx.searchQuery.indexOf(' '));
                    this.xx.parent.lastname = xx.searchQuery.substr(xx.searchQuery.indexOf(' ') + 1);
                    if (!this.xx.parent.firstname) {
                        this.xx.parent.firstname = this.xx.parent.lastname;
                        this.xx.parent.lastname = '';
                    }
                    this.xx.parent_search = false;
                    this.xx.parent_add = true;
                }
                else {
                    this.xx.parent.id = person.pid
                    this.xx.searchQuery = person.name;
                    this.xx.search_options = false;
                }
            },
        }
    })

    var vue_rego = new Vue({
        el: '#parent_info',
        data: {xx: xx,},
        created: function () {
            this.getParents();
        },
        methods: {
            getParents: function () {
                $.getJSON('/data/checkin/parents', function (data) {
                    this.xx.parents = data;
                    if (xx.parent.id == 'add') {
                        this.xx.parent_search = false;
                        this.xx.parent_add = true;
                    }
                }.bind(this));
            },
        },
    });
</script>
@stop