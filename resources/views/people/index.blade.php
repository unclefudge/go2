@inject('ozstates', 'App\Http\Utilities\Ozstates')
@extends('layout/main')

@section('bodystyle')
    style="background-image: url(/img/head-purple.jpg)"
@endsection

@section('subheader')
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">People</h3>
            <h4 class="kt-subheader__desc">Because people matter</h4>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal_create_person">Add Person</a>
                <div class="dropdown dropdown-inline" data-toggle="kt-tooltip" title="">
                    <a href="#" class="btn kt-subheader__btn-secondary kt-subheader__btn-options" style="padding: 1.4rem 1rem;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" style="font-size: 18px !important; color: #fff"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button class="dropdown-item" onclick="uSet('show_inactive_people', 0)" id="but_hide_inactive"><i class="fa fa-eye-slash" style="width: 25px"></i> Hide inactive people</button>
                        <button class="dropdown-item" onclick="uSet('show_inactive_people', 1)" id="but_show_inactive"><i class="fa fa-eye" style="width: 25px"></i> Show inactive people</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('people/_create_personal')
@endsection

@section('content')
    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}

    <div class="kt-content kt-grid__item kt-grid__item--fluid">
        <div class="container-fluid">
            {{-- Overview --}}
            <div class="kt-portlet kt-portlet--tabs">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-toolbar">
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-primary nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_all">
                                    <i class="fa fa-users"></i> All &nbsp; <span id="count_all_num"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_student">
                                    <i class="fa fa-user-graduate"></i> Students &nbsp; <span id="count_students_num"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_parent">
                                    <i class="fa fa-user-tie"></i> Parents &nbsp; <span id="count_parents_num"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link show" data-toggle="tab" href="#m_tabs_all" role="tab" aria-selected="true" id="type_volunteer">
                                    <i class="fa fa-user-friends"></i> Volunteers <span id="count_volunteers_num"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="pull-right" style="padding: 15px">
                        <span style="" id="inactive_status"></span>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    {!! Form::hidden('show_type', null, ['id' => 'show_type']) !!}
                    {!! Form::hidden('pagelength', ($agent->isMobile() ? 100 : 25), ['id' => 'pagelength']) !!}
                    {!! Form::hidden('formerrors', ($errors && $errors->first('FORM')) ? $errors->first('FORM') : null, ['id' => 'formerrors']) !!}
                    {!! Form::hidden('show_inactive', session('show_inactive_people'), ['id' => 'show_inactive']) !!}
                    {!! Form::hidden('count_all', count($users->where('status', 1)), ['id' => 'count_all']) !!}
                    {!! Form::hidden('count_students', count($users->where('status', 1)->whereIn('type', ['Student', 'Student/Volunteer'])), ['id' => 'count_students']) !!}
                    {!! Form::hidden('count_parents', count($users->where('status', 1)->whereIn('type', ['Parent', 'Parent/Volunteer'])), ['id' => 'count_parents']) !!}
                    {!! Form::hidden('count_volunteers', count($users->where('status', 1)->whereIn('type', ['Volunteer', 'Parent/Volunteer'])), ['id' => 'count_volunteers']) !!}
                    {!! Form::hidden('counti_all', count($users), ['id' => 'counti_all']) !!}
                    {!! Form::hidden('counti_students', count($users->whereIn('type', ['Student', 'Student/Volunteer'])), ['id' => 'counti_students']) !!}
                    {!! Form::hidden('counti_parents', count($users->whereIn('type', ['Parent', 'Parent/Volunteer'])), ['id' => 'counti_parents']) !!}
                    {!! Form::hidden('counti_volunteers', count($users->whereIn('type', ['Volunteer', 'Parent/Volunteer'])), ['id' => 'counti_volunteers']) !!}

                    <style>
                        #datatable1 tbody td.selected {
                            color: black;
                            background-color: #F8F9FB;
                        }

                        tr.person_inactive td{
                            color: #fff !important;
                            background: #777 !important;
                        }

                        .person_inactive:hover {
                            /*background: #777;*/
                            color: #FF0000;
                        }
                    </style>
                    <table class="table table-hover table-checkable table-bordered table-responsive table--head-bg-primary" id="datatable1" width="100%">
                        <thead>
                        <tr>
                            <th width="5%"> #</th>
                            <th class="text-white"> Name</th>
                            <th class="text-white"> Type</th>
                            <th class="text-white"> Phone</th>
                            <th class="text-white"> Email</th>
                            <th class="text-white"> Address</th>
                            <th class="text-white"> Grade</th>
                            <th class="text-white"> School</th>
                            <th class="text-white"> Media</th>
                            <th class="text-white" width="10%"> WWC Expiry</th>
                            <th width="5%"> Actions</th>
                            <th class="text-white">status</th>
                        </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-styles')
    <link href="/massets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('vendor-scripts')
    <script src="/massets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
    <script src="/massets/app/custom/general/crud/datatables/basic/basic.js" type="text/javascript"></script>
    <script src="/massets/app/custom/general/crud/datatables/data-sources/ajax-client-side.js" type="text/javascript"></script>
    <script type="text/javascript">
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        $(document).ready(function () {
            $('.dataTables_filter input[type="search"]').attr('placeholder', 'Search for something').css(
                    {'width': '350px', 'height': '40px', 'font-size': '14px', 'margin-left': '0px', 'display': 'inline-block'});
        });

        //
        // Datatable
        //
        var datatable1 = $('#datatable1').DataTable({
            pageLength: $('#pagelength').val(),
            processing: true,
            serverSide: true,
            //bFilter: false,
            //bLengthChange: false,
            responsive: true,
            //select: true,
            language: {search: ""},
            select: {
                style: 'single',
                items: 'cell'
            },
            dom: "<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>\n\t\t\t<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
            //buttons: ["print", "copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
            buttons: [
                    @if ($agent->isDesktop())
                {
                    text: "<i class='fa fa-cloud-download-alt' style='padding-right: 5px'></i> Export",
                    action: function (e, dt, node, config) {
                        alert('Export - An unrealised dream...');
                    }
                },
                {
                    text: "<i class='fa fa-cloud-upload-alt' style='padding-right: 5px'></i> Import",
                    action: function (e, dt, node, config) {
                        alert('Import - An unrealised dream...');
                    }
                },
                    @endif
                    @if (false)
                {
                    text: "<i class='fa fa-cloud-upload-alt' style='padding-right: 5px'></i> Hide/Show Columns",
                    action: function (e, dt, node, config) {
                        alert('Columns');
                    }
                },
                {
                    text: '<div class="dropdown"><button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown button </button> <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> <a class="dropdown-item" href="#" data-toggle="m-tooltip" title="Tooltip title" data-placement="right" data-skin="dark" 	data-container="body">Action</a> <a class="dropdown-item" href="#">Another action</a> <a class="dropdown-item" href="#" data-toggle="m-tooltip" title="Tooltip title" data-placement="left">Something else here</a></div></div>',
                    action: function (e, dt, node, config) {
                        //alert('Columns');
                    }
                },
                @endif
            ],
            ajax: {
                'url': '/data/people',
                'type': 'GET',
                'data': function (d) {
                    d.show_type = $('#show_type').val();
                    d.show_inactive = $('#show_inactive').val();
                }
            },
            columns: [
                {data: 'id', name: 'users.id', visible: false},
                {data: 'full_name', name: 'full_name', orderable: true, searchable: false},
                {data: 'type', name: 'users.type', orderable: false},
                {data: 'phone', name: 'users.phone', orderable: false},
                {data: 'email', name: 'users.email', orderable: false},
                {data: 'address', name: 'users.address', orderable: false},
                {data: 'grade_name', name: 'grades.name', orderable: true},
                {data: 'school_name', name: 'schools.name', orderable: true},
                {data: 'media_consent', name: 'users.media_consent', orderable: true},
                {data: 'wwc_exp2', name: 'users.wwc_exp', visible: false, orderable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                {data: 'status', name: 'users.status', visible: false},
                {data: 'firstname', name: 'users.firstname', visible: false},
                {data: 'lastname', name: 'users.lastname', visible: false},
            ],
            order: [
                [1, "asc"]
            ],
            createdRow: function (row, data, dataIndex) {
                if (data['status'] == 0)
                    $(row).addClass('person_inactive');
                //$("#count_all").html('all');
            },
        });

        //
        // Delete select profile on trashcan
        //
        datatable1.on('click', '.btn-archive[data-remote]', function (e) {
            e.preventDefault();
            var url = $(this).data('remote');
            var name = $(this).data('name');

            Swal.fire({
                title: "Are you sure?",
                html: "All information and check-ins will be archived for<br><b>" + name + "</b><br>",
                cancelButtonText: "Cancel!",
                confirmButtonText: "Yes, delete!",
                showCancelButton: true,
                reverseButtons: true,
                allowOutsideClick: true,
                animation: false,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary',
                    popup: 'animated tada'
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {submit: true},
                        success: function (data) {
                            toastr.error('Archived person');
                        },
                    }).always(function (data) {
                        $('#datatable1').DataTable().draw(false);
                    });
                }
            });
        });

        //
        // Delete select profile on trashcan
        //
        datatable1.on('click', '.btn-delete[data-remote]', function (e) {
            e.preventDefault();
            var url = $(this).data('remote');
            var name = $(this).data('name');

            Swal.fire({
                title: "Are you sure?",
                html: "All information and check-ins will be deleted for<br><b>" + name + "</b><br><br><span class='m--font-danger'><i class='fa fa-exclamation-triangle'></i>You will not be able to recover this person!</span> ",
                cancelButtonText: "Cancel!",
                confirmButtonText: "Yes, delete!",
                showCancelButton: true,
                reverseButtons: true,
                allowOutsideClick: true,
                animation: false,
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary',
                    popup: 'animated tada'
                }
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {method: '_DELETE', submit: true},
                        success: function (data) {
                            toastr.error('Deleted person');
                        },
                    }).always(function (data) {
                        $('#datatable1').DataTable().draw(false);
                    });
                }
            });
        });

        //
        // View selected profile on click
        //
        datatable1.on('select', function (e, dt, type, indexes) {
            var colnum = indexes[0].column;
            var rownum = indexes[0].row;
            var cell_data = datatable1.cell(indexes).data();
            //console.log('R:'+rownum+' C:'+colnum+' D:'+cell_data);
            //var rowData = datatable1.rows(indexes).data().toArray(); // For row select
            var rowData = datatable1.rows(rownum).data().toArray(); // for cell selection
            //console.log(rowData);
            var id = rowData[0][Object.keys(rowData[0])[0]];
            if (colnum != 10)
                window.location.href = "/people/" + id;
        });


        //
        //  View profiles of 'Type' + hide/show columns for select type
        //
        $("#type_all").click(function () {
            $("#show_type").val('');
            datatable1.column(2).visible(true);  // Type
            datatable1.column(6).visible(true);  // Grade
            datatable1.column(7).visible(true);  // School
            datatable1.column(8).visible(true);  // Media
            datatable1.column(9).visible(false);   // WWC Exp
            datatable1.ajax.reload();
        });
        $("#type_student").click(function () {
            $("#show_type").val('Student');
            datatable1.column(2).visible(false);  // Type
            datatable1.column(6).visible(true);   // Grade
            datatable1.column(7).visible(true);   // School
            datatable1.column(8).visible(true);   // Media
            datatable1.column(9).visible(false);  // WWC Exp
            datatable1.ajax.reload();
        });
        $("#type_parent").click(function () {
            $("#show_type").val('Parent');
            datatable1.column(2).visible(false);  // Type
            datatable1.column(6).visible(false);  // Grade
            datatable1.column(7).visible(false);  // School
            datatable1.column(8).visible(false);  // Media
            datatable1.column(9).visible(false);  // WWC Exp
            datatable1.ajax.reload();
        });
        $("#type_volunteer").click(function () {
            $("#show_type").val('Volunteer');
            datatable1.column(2).visible(false);  // Type
            datatable1.column(6).visible(false);  // Grade
            datatable1.column(7).visible(false);  // School
            datatable1.column(8).visible(false);  // Media
            datatable1.column(9).visible(true);   // WWC Exp
            datatable1.ajax.reload();
        });

        jQuery(document).ready(function () {
            datatable1.init()
        });

    </script>

    <script type="text/javascript">
        // Form errors - show modal
        if ($('#formerrors').val() == 'personal')
            $('#modal_create_person').modal('show');

        $(document).ready(function () {

            display_fields();

            $("#type").change(function () {
                display_fields();
            });
            $("#grade_id").change(function () {
                display_fields();
            });

            // DOB
            $("#dob").datepicker({todayHighlight: !0, orientation: "bottom left", autoclose: true, clearBtn: true, format: "{{ session('df-datepicker') }}"});
            // WWC Exp
            $("#wwc_exp").datepicker({todayHighlight: !0, orientation: "bottom left", autoclose: true, clearBtn: true, format: "{{ session('df-datepicker') }}"});
        });

        function uSetReturn(result, val) {
            if (result) {
                //alert('reloaded');
                $("#show_inactive").val(val)
                display_fields();
                datatable1.ajax.reload();
            }
        }

        function display_fields() {
            var type = $("#type").val();
            $('#fields_student').hide();
            $('#fields_volunteer').hide();
            $('#but_show_inactive').hide();
            $('#but_hide_inactive').hide();

            if (type == 'Student' || type == 'Student/Volunteer') {
                $('#fields_student').show();
            }
            if (type == 'Volunteer' || type == 'Student/Volunteer' || type == 'Parent/Volunteer') {
                $('#fields_volunteer').show();
            }

            // Hide / Show Inactive records
            var show_inactive = $("#show_inactive").val();
            if (show_inactive == 0) {
                $('#but_show_inactive').show('kk');
                $('#inactive_status').html('');
                $('#count_all_num').html('(' + $('#count_all').val() + ')');
                $('#count_students_num').html('(' + $('#count_students').val() + ')');
                $('#count_parents_num').html('(' + $('#count_parents').val() + ')');
                $('#count_volunteers_num').html('(' + $('#count_volunteers').val() + ')');
            } else {
                $('#but_hide_inactive').show();
                $('#inactive_status').html('Inactive shown');
                $('#count_all_num').html('(' + $('#counti_all').val() + ')');
                $('#count_students_num').html('(' + $('#counti_students').val() + ')');
                $('#count_parents_num').html('(' + $('#counti_parents').val() + ')');
                $('#count_volunteers_num').html('(' + $('#counti_volunteers').val() + ')');
            }

            // Dynamic School dropdown from Grade
            $("#school_id").select2({width: '100%', minimumResultsForSearch: -1});
            var gid = $("#grade_id").val();
            var school = $("#school_id").val();
            if (gid) {
                $.ajax({
                    url: '/data/schools-by-grade/' + gid,
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
                        console.log(school_names);
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

        /*
         var Typeahead = function () {
         var e = ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"];
         return {
         init: function () {
         var a, n, o, t, i, s;
         $("#m_typeahead_1, #m_typeahead_1_modal, #m_typeahead_1_validate, #m_typeahead_2_validate, #m_typeahead_3_validate").typeahead({hint: !0, highlight: !0, minLength: 1}, {
         name: "states", source: (a = e, function (e, n) {
         var o;
         o = [], substrRegex = new RegExp(e, "i"), $.each(a, function (e, a) {
         substrRegex.test(a) && o.push(a)
         }), n(o)
         })
         }), n = new Bloodhound({datumTokenizer: Bloodhound.tokenizers.whitespace, queryTokenizer: Bloodhound.tokenizers.whitespace, local: e}), $("#m_typeahead_2, #m_typeahead_2_modal").typeahead({hint: !0, highlight: !0, minLength: 1}, {
         name: "states",
         source: n
         }), o = new Bloodhound({
         datumTokenizer: Bloodhound.tokenizers.whitespace,
         queryTokenizer: Bloodhound.tokenizers.whitespace,
         prefetch: "https://keenthemes.com/metronic/themes/themes/metronic/dist/preview/inc/api/typeahead/countries.json"
         }), $("#m_typeahead_3, #m_typeahead_3_modal").typeahead(null, {name: "countries", source: o}), t = new Bloodhound({
         datumTokenizer: Bloodhound.tokenizers.obj.whitespace("value"),
         queryTokenizer: Bloodhound.tokenizers.whitespace,
         prefetch: "inc/api/typeahead/movies.json"
         }), $("#m_typeahead_4").typeahead(null, {
         name: "best-pictures",
         display: "value",
         source: t,
         templates: {empty: ['<div class="empty-message" style="padding: 10px 15px; text-align: center;">', "unable to find any Best Picture winners that match the current query", "</div>"].join("\n"), suggestion: Handlebars.compile("<div><strong>{value}</strong> â€“ {year}</div>")}
         }), i = new Bloodhound({datumTokenizer: Bloodhound.tokenizers.obj.whitespace("team"), queryTokenizer: Bloodhound.tokenizers.whitespace, prefetch: "inc/api/typeahead/nba.json"}), s = new Bloodhound({
         datumTokenizer: Bloodhound.tokenizers.obj.whitespace("team"),
         queryTokenizer: Bloodhound.tokenizers.whitespace,
         prefetch: "inc/api/typeahead/nhl.json"
         }), $("#m_typeahead_5").typeahead({highlight: !0}, {name: "nba-teams", display: "team", source: i, templates: {header: '<h3 class="league-name" style="padding: 5px 15px; font-size: 1.2rem; margin:0;">NBA Teams</h3>'}}, {
         name: "nhl-teams",
         display: "team",
         source: s,
         templates: {header: '<h3 class="league-name" style="padding: 5px 15px; font-size: 1.2rem; margin:0;">NHL Teams</h3>'}
         })
         }
         }
         }();
         jQuery(document).ready(function () {
         Typeahead.init()
         });
         */


        var bloodhound = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '/user/find?q=%QUERY%',
                wildcard: '%QUERY%'
            },
        });

        $('#search').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'users',
            source: bloodhound,
            display: function (data) {
                return data.firstname  //Input value to be set when you select a suggestion.
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function (data) {
                    //return '<div>' + data.firstname + ' ' + data.lastname + '</div></div>'
                    var color = "#777";
                    if (data.status === "owner") {
                        color = "#ff1493";
                    }
                    data.avatar = '/img/avatar-user.jpg'
                    return '<span class="row">' +
                            '<span class="avatar">' + '<img src="' + data.avatar + '">' + "</span>" +
                            '<span class="username">' + data.firstname + ' ' + data.lastname + '</span><br><br>' +
                            '<span class="id">(' + data.type + ')</span>' +
                            "</span>"
                }
            }
        });

        /*
         $.typeahead({
         input: '.js-typeahead-user_v1',
         minLength: 1,
         order: "asc",
         dynamic: true,
         delay: 500,
         backdrop: {
         "background-color": "#fff"
         },
         template: function (query, item) {

         var color = "#777";
         if (item.status === "owner") {
         color = "#ff1493";
         }

         return '<span class="row">' +
         '<span class="avatar">' +
         '<img src="'+item.avatar+'">' +
         "</span>" +
         '<span class="username">'+item.username+' <small style="color: ' + color + ';">('+item.status+')</small></span>' +
         '<span class="id">('+item.id+')</span>' +
         "</span>"
         },
         emptyTemplate: "no result for "+query,
         source: {
         user: {
         display: "username",
         href: "https://www.github.com/"+item.username,
         data: [{
         "id": 415849,
         "username": "an inserted user that is not inside the database",
         "avatar": "https://avatars3.githubusercontent.com/u/415849",
         "status": "contributor"
         }],
         ajax: function (query) {
         return {
         type: "GET",
         //url: "/jquerytypeahead/user_v1.json",
         url: "/data/people/search-add",
         path: "data.user",
         data: {
         q: query
         },
         callback: {
         done: function (data) {
         for (var i = 0; i < data.data.user.length; i++) {
         if (data.data.user[i].username === 'running-coder') {
         data.data.user[i].status = 'owner';
         } else {
         data.data.user[i].status = 'contributor';
         }
         }
         return data;
         }
         }
         }
         }

         },
         project: {
         display: "project",
         href: function (item) {
         return '/' + item.project.replace(/\s+/g, '').toLowerCase() + '/documentation/'
         },
         ajax: [{
         type: "GET",
         //url: "/jquerytypeahead/user_v1.json",
         url: "/data/people/search-add",
         data: {
         q: query
         }
         }, "data.project"],
         template: '<span>' +
         '<span class="project-logo">' +
         '<img src="'+item.image+'">' +
         '</span>' +
         '<span class="project-information">' +
         '<span class="project">'+item.project+' <small>'+item.version+'</small></span>' +
         '<ul>' +
         '<li>'+item.demo+' Demos</li>' +
         '<li>'+item.option+'+ Options</li>' +
         '<li>'+item.callback+'+ Callbacks</li>' +
         '</ul>' +
         '</span>' +
         '</span>'
         }
         },
         callback: {
         onClick: function (node, a, item, event) {

         // You can do a simple window.location of the item.href
         alert(JSON.stringify(item));

         },
         onSendRequest: function (node, query) {
         console.log('request is sent')
         },
         onReceiveRequest: function (node, query) {
         console.log('request is received')
         }
         },
         debug: true
         });
         */

        {{--
        $.typeahead({
            input: '.js-typeahead-user_v1',
            minLength: 1,
            order: "asc",
            dynamic: true,
            delay: 500,
            backdrop: {
                "background-color": "#fff"
            },
            template: function (query, item) {

                var color = "#777";
                if (item.status === "owner") {
                    color = "#ff1493";
                }

                return '<span class="row">' +
                        '<span class="avatar">' +
                        '<img src="{{avatar}}">' +
                        "</span>" +
                        '<span class="username">{{username}} <small style="color: ' + color + ';">({{status}})</small></span>' +
                        '<span class="id">({{id}})</span>' +
                        "</span>"
            },
            emptyTemplate: "no result for {{query}}",
            source: {
                user: {
                    display: "username",
                    href: "https://www.github.com/{{username|slugify}}",
                    data: [{
                        "id": 415849,
                        "username": "an inserted user that is not inside the database",
                        "avatar": "https://avatars3.githubusercontent.com/u/415849",
                        "status": "contributor"
                    }],
                    ajax: function (query) {
                        return {
                            type: "GET",
                            //url: "/jquerytypeahead/user_v1.json",
                            url: "/data/people/search-add",
                            path: "data.user",
                            data: {
                                q: "{{query}}"
                            },
                            callback: {
                                done: function (data) {
                                    for (var i = 0; i < data.data.user.length; i++) {
                                        if (data.data.user[i].username === 'running-coder') {
                                            data.data.user[i].status = 'owner';
                                        } else {
                                            data.data.user[i].status = 'contributor';
                                        }
                                    }
                                    return data;
                                }
                            }
                        }
                    }

                },
                project: {
                    display: "project",
                    href: function (item) {
                        return '/' + item.project.replace(/\s+/g, '').toLowerCase() + '/documentation/'
                    },
                    ajax: [{
                        type: "GET",
                        //url: "/jquerytypeahead/user_v1.json",
                        url: "/data/people/search-add",
                        data: {
                            q: "{{query}}"
                        }
                    }, "data.project"],
                    template: '<span>' +
                    '<span class="project-logo">' +
                    '<img src="{{image}}">' +
                    '</span>' +
                    '<span class="project-information">' +
                    '<span class="project">{{project}} <small>{{version}}</small></span>' +
                    '<ul>' +
                    '<li>{{demo}} Demos</li>' +
                    '<li>{{option}}+ Options</li>' +
                    '<li>{{callback}}+ Callbacks</li>' +
                    '</ul>' +
                    '</span>' +
                    '</span>'
                }
            },
            callback: {
                onClick: function (node, a, item, event) {

                    // You can do a simple window.location of the item.href
                    alert(JSON.stringify(item));

                },
                onSendRequest: function (node, query) {
                    console.log('request is sent')
                },
                onReceiveRequest: function (node, query) {
                    console.log('request is received')
                }
            },
            debug: true
        });
    --}}
    </script>
@endsection
