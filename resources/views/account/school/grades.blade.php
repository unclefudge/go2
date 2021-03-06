@extends('layout/main')

@section('bodystyle')
    style="background-image: url(/img/head-purple.jpg)"
@endsection

@section('subheader')
    @include('account/_header')
@endsection

@section('content')
    <?php
    $months_array = ['Month', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $days_array = ['Day', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'];
    ?>

    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="vue-app">
        <div class="container-fluid">
            <div class="row">
                @include('account/_sidebar')
                {{-- Main Content --}}
                <div class="col">
                    @include('account/_sidebar-mobile')
                    <div class="row">
                        <div class="col">
                            {{-- Schools --}}
                            <div class="kt-portlet kt-portlet--tabs">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-toolbar">
                                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-primary nav-tabs-line-2x nav-tabs-line-right nav-tabs-bold">
                                            <li class="nav-item">
                                                <a class="nav-link show" data-toggle="tab" href="#" role="tab" aria-selected="true" id="type_schools">
                                                    <i class="fa fa-graduation-cap"></i> Schools
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link show active" data-toggle="tab" href="/account/grades" role="tab" aria-selected="true" id="type_grades">
                                                    <i class="fa fa-book"></i> Grades
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    {{--}}
                                    <div class="pull-right" style="min-width:150px; padding:10px">
                                        <button type="button" class="btn btn-secondary btn-sm  pull-right" style="margin-left: 10px" id="but_show_inactive"><i class="fa fa-eye"></i></button>
                                        <button type="button" class="btn btn-sm pull-right" style="margin-left: 10px; color: #000000; background: #eee" id="but_hide_inactive"><i class="fa fa-eye-slash" style="padding-right: 5px"></i> Hide Inactive</button>
                                        <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#modal_create_person">Add</button>
                                    </div>--}}
                                </div>
                                <div class="kt-portlet__body">
                                    {{-- Grades --}}
                                    <div id="vue-dragableList">
                                        <div class="root"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- loading Spinner -->
        {{--}}
        <div v-show="xx.searching" style="background-color: #FFF; padding: 20px;">
            <div class="loadSpinnerOverlay">
                <div class="loadSpinner"><i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom"></i> Loading...</div>
            </div>
        </div>--}}
    </div>

    <div>
        <div class="pull-right" style="font-size: 12px; font-weight: 200; padding: 10px 10px 0 0">
            {!! $account->displayUpdatedBy() !!}
        </div>
    </div>

    {{-- List template --}}
    <script type="text/x-template" id="list-template">
        <div class="root">
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-secondary" role="alert">
                        <div class="alert-text">
                            A list of school grades to be used by your students.<br><br>
                            Simply drag them into the correct order of school progression or hide/delete any grades you don't use.<br><br>
                            Once you are happy with the order + settings ensure you click 'Save'.
                        </div>
                    </div>
                    <div class="input-group" style="padding-bottom: 20px">
                        <input v-model="xx.addItem" type="text" class="form-control form-control" placeholder="Add new grade">
                        <div class="input-group-append">
                            <span v-on:click="add()" class="input-group-text" style="color: #FFFFFF; background: #666; padding: 0px 20px; cursor: pointer">Add</span>
                        </div>
                        <span></span>
                    </div>
                    {{-- Tablet+ display --}}
                    <div class="d-none d-md-block">
                        <button v-on:click="save()" class="btn btn-primary" id="saveList1">Save</button>
                        <a href="#" v-on:click="restore()" style="margin-left: 30px">Restore previous grades &nbsp;
                            <i class="fa fa-question-circle" data-container="body" data-toggle="kt-tooltip" data-placement="top" title="" data-original-title="You can restore your previous 'saved' grade settings"></i> </a>

                        {{-- Grade Upgrade - Standard --}}
                        <div style="padding:20px; margin-top:20px; background: #716aca; color: #fff">
                            <h3 class="kt-portlet__head-title text-center" style="color:#fff;">Beginning of School Year<br><small>(under development)</small></h3>
                            <p style="font-size: larger">
                                Once a year Go2Youth will automatically move all your students up a grade because they are such excellent pupils.<br><br>
                                What date would you like this to happen each year.
                            </p>
                            <div class="row justify-content-sm-center">
                                <div class="col-lg-6 col-md-6 col-sm-4" style="padding-bottom: 20px">
                                    {!! Form::select('month', $months_array, null, ['class' => 'form-control kt-selectpicker']) !!}
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-3">
                                    {!! Form::select('day', $days_array, null, ['class' => 'form-control kt-selectpicker']) !!}
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-sm-center">
                                <div class="col-lg-6 col-md-4 col-sm-3">
                                    <button class="btn btn-light btn-block">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" style="padding-bottom: 30px">
                    <h5 class="text-center">Grade Order</h5>
                    <SortableList lockAxis="y" v-model="xx.items" distance="5">
                        <SortableItem v-for="(item, index) in xx.items" :index="index" :key="index" :item="item" :items="xx.items">
                    </SortableList>

                    {{-- Mobile Display --}}
                    <div class="d-md-none">
                        <br><br>
                        <button v-on:click="save()" class="btn btn-primary">Save</button>

                        {{-- Grade Upgrade - Mobile --}}
                        <div style="padding:20px; margin-top:20px; background: #716aca; color: #fff">
                            <h3 class="kt-portlet__head-title text-center" style="color:#fff;">Beginning of School Year</h3>
                            <p style="font-size: larger">
                                Once a year Go2Youth will automatically move all your students up a grade because they are such excellent pupils.<br><br>
                                What date would you like this to happen each year.
                            </p>
                            <div class="row justify-content-sm-center">
                                <div class="col-lg-6 col-md-6 col-sm-4" style="padding-bottom: 20px">
                                    {!! Form::select('month', $months_array, null, ['class' => 'form-control kt-selectpicker']) !!}
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-3">
                                    {!! Form::select('day', $days_array, null, ['class' => 'form-control kt-selectpicker']) !!}
                                </div>
                            </div>
                            <br>
                            <div class="row justify-content-sm-center">
                                <div class="col-lg-6 col-md-4 col-sm-3">
                                    <button class="btn btn-light btn-block">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--}}<div class="row">
                <div class="col">
                    <pre>@{{ $data }}</pre>
                </div>
            </div>--}}
        </div>
    </script>

    {{-- Items template --}}
    <script type="text/x-template" id="item-template">
        <li v-bind:sortEnd="movedItem()" v-bind:item.order="index" class="list-group-item" :style="itemStyle(item)">
            <div v-if="item.edit == 0">
                <i class="fa fa-arrows-alt-v" style="color:#bbb; padding-right:20px"></i> @{{item.name}}
                <i v-if="item.status == 0" v-on:click="showItem(item)" class="fa fa-eye-slash icon-edit" style=""></i>
                <i v-if="item.status == 1" v-on:click="hideItem(item)" class="fa fa-eye icon-edit" style=""></i>
                <i v-if="item.status == 1" v-on:click="delItem(item)" class="fa fa-trash-alt icon-del" style=""></i>
                <i v-if="item.status == 1" v-on:click="editItem(item)" class="fa fa-edit icon-edit" style=""></i>
            </div>
            <div v-if="item.edit == 1">
                <div class="input-group">
                    <input v-on:keyup="editVerify(item)" v-model="item.name" type="text" class="form-control">
                    <div v-if="xx.editOK == 1" class="input-group-append">
                        <span v-on:click="saveItem(item)" class="input-group-text" style="color: #FFFFFF; background: #5867dd; padding: 0px 10px; cursor: pointer">Save</span>
                    </div>
                    <span></span>
                </div>
            </div>
        </li>
    </script>
@endsection

@section('page-styles')
@endsection

@section('vendor-scripts')
@endsection

{{-- Metronic + custom Page Scripts --}}
@section('page-scripts')
    <script src="/js/vue.min.js"></script>
    <script src="https://unpkg.com/vue-slicksort@latest/dist/vue-slicksort.min.js"></script>
    <script>
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        var { ContainerMixin, ElementMixin, HandleDirective } = window.VueSlicksort;
        //var xx = 'xx';
        var xx = {
            addItem: '', editOK: 1,
            items: [],
            original: []
            //items: [{id: '1', order: '0', name: 'Catty'}, {id: '2', order: '1', name: 'Dog'}, {id: '3', order: '2', name: 'Bird'}]
        };

        const SortableList = {
            mixins: [ContainerMixin],
            template: '<ul class="list-group"> <slot /> </ul>',
            data() {
                return {xx: xx};
            },
        };

        const SortableItem = {
            mixins: [ElementMixin],
            props: ['item', 'index', 'items'],
            //template: "#list-template",
            template: '#item-template',
            data() {
                return {xx: xx};
            },
            methods: {
                movedItem: function () {
                    this.item.order = this.index;
                    //console.log('moved: [' + this.index + '] ' + this.item.name);
                },
                editItem: function (item) {
                    //console.log('edit id:' + item.id + ' name:' + item.name);
                    item.edit = 1;
                    //$('#grade_name').val(item.name);
                    //$('#grade_id').val(item.id);
                    //$('#modal_edit_grade').modal('show');
                },
                editVerify: function (item) {
                    if (item.name == '')
                        this.xx.editOK = 0;
                    else
                        this.xx.editOK = 1;

                },
                saveItem: function (item) {
                    //console.log('edit id:' + item.id + ' name:' + item.name);
                    item.edit = 0;
                },
                showItem: function (item) {
                    item.status = 1;
                },
                hideItem: function (item) {
                    item.status = 0;
                },
                delItem: function (item) {
                    //console.log('delete id:' + item.id + ' name:' + item.name);
                    if (item.count > 0) {
                        Swal.fire({
                            title: "Are you sure?",
                            html: "You currently have <b>" + item.count + " students</b> in this grade.<br><br><span class='m--font-danger'><i class='fa fa-exclamation-triangle'></i> All these students grades will be cleared!</span> ",
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
                                // Delete grade
                                this.xx.items = this.items.filter(function (obj) {
                                    return obj.id !== item.id;
                                });
                            }
                        }.bind(this));
                    } else {
                        // Delete grade
                        this.xx.items = this.items.filter(function (obj) {
                            return obj.id !== item.id;
                        });
                    }

                    //alert(item.name);
                },
                itemStyle: function (item) {
                    //console.log(item.name);
                    var style;
                    if (item.status == 0)
                        style = "opacity: .5"

                    return style;
                }
            }
        };

        const DraggableList = {
            template: '#list-template',
            components: {
                SortableItem,
                SortableList,
            },
            data() {
                return {xx: xx};
            },
            created: function () {
                this.getList();
            },
            methods: {
                getList: function () {
                    $.getJSON('/data/grades', function (data) {
                        this.xx.items = data;
                        this.xx.original = JSON.stringify(this.xx.items);
                    }.bind(this));
                },
                save: function () {
                    $('#saveList1').html('Saving');
                    $('#saveList1').addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light');

                    updateGradeDB(JSON.stringify(this.xx.items)).then(function (result) {
                        if (result)
                            window.location.href = "/account/grades";
                    }.bind(this));
                },
                add: function () {
                    if (this.xx.addItem != '')
                        this.xx.items.push({id: 'new', order: '', name: this.xx.addItem, count:0, status: 1, edit: 0});
                    this.xx.addItem = '';
                },
                restore: function () {
                    this.xx.items = JSON.parse(this.xx.original);
                },
            }
        };

        const root = new Vue({
            el: '#vue-dragableList',
            data: {xx: xx,},
            render: function (h) {
                return h(DraggableList)
            }
            //render: (h) => h(DraggableList),
        });

        // Update user in Database Attendance and return a 'promise'
        function updateGradeDB(items) {
            return new Promise(function (resolve, reject) {
                var grades = {grades: items};
                grades._method = 'patch';
                $.ajax({
                    url: '/settings/grade/' + "{{ session('aid') }}",
                    type: 'POST',
                    data: grades,
                    success: function (result) {
                        resolve(items);
                    },
                    error: function (result) {
                        alert("Something went wrong updating grades. Please refresh the page to resync.");
                        //console.log('DB updated user FAILED:[' + user.eid + '] ' + user.name);
                        reject(false);
                    }
                });
            });
        }
        ;


        // Tab Page Links
        $("#type_schools").click(function () {
            window.location.href = "/account/schools";
        });

        $("#type_grades").click(function () {
            window.location.href = "/account/grades";
        });

        // Hide Edit Grade Save button on empty name
        $("#grade_name").keyup(function () {
            $('#but_edit_grade').hide();
            if ($('#grade_name').val() != '')
                $('#but_edit_grade').show();
        });
        // Hide Edit Grade Save button on empty name
        $(".edit-name").keyup(function () {
            alert('key');
            $('#but_edit_grade').hide();
            if ($('#grade_name').val() != '')
                $('#but_edit_grade').show();
        });

        // Save new grade name to item array
        $("#but_edit_grade").click(function () {
            $('#modal_edit_grade').modal('hide');
            $.each(xx.items, function (key, value) {
                if (value.id == $('#grade_id').val())
                    value.name = $('#grade_name').val()
            });
        });
    </script>
@endsection
