@extends('layout.default')
@section('styles')
    {{-- <link rel="stylesheet" href="https://preview.keenthemes.com/metronic/theme/html/demo1/dist/assets/plugins/custom/jstree/jstree.bundle.css?v=7.2.6" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <style>
        
    </style>
@stop
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- {{ __('You are logged in!') }} --}}

                    <div class="row">
                        <div class="col-md-4 col-sm-8 col-xs-8">
                            <button type="button" class="btn btn-success btn-sm" onclick="node_create();">Create</button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="node_rename();">Rename</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="node_delete();">Delete</button>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-4" style="text-align:right;">
                            <input type="text" value="" style="box-shadow:inset 0 0 4px #eee; width:120px; margin:0; padding:6px 12px; border-radius:4px; border:1px solid silver; font-size:1.1em;" id="search_box" placeholder="Search">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div id="category_tree" class="tree-demo"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script> --}}
    <script src="https://preview.keenthemes.com/metronic/theme/html/demo1/dist/assets/plugins/custom/jstree/jstree.bundle.js?v=7.2.6"></script>
    <script>
        
        function node_create() {
            var ref = $('#category_tree').jstree(true),
                sel = ref.get_selected();

            if(!sel.length) { return false; }
            parent_id = sel[0];

            $.ajax({
                url: API_URL + `/new/${parent_id}`,
                type: "POST",
                dataType: 'json',
                success: function (res) {
                    if(res['success']) {
                        const data = res['data'];
                        console.log(data);
                        
                        sel = ref.create_node(parent_id, {
                            "id": data['id'],
                            "text": data['title'],
                            "type": "default"}
                        );

                        if(sel) {
                            update_node(ref, sel);
                        }
                        showNotify('success', 'The category updated successfully!');
                    } else {
                        showNotify('danger', 'You can\'t create node here!');
                    }
                },
                error: function (data) {
                    showNotify('danger', 'You can\'t create node here!');
                }
            });


        };

        function node_rename() {
            var ref = $('#category_tree').jstree(true),
                sel = ref.get_selected();
            if(!sel.length) { return false; }
            sel = sel[0];
            update_node(ref, sel);
        };

        function node_delete() {
            var ref = $('#category_tree').jstree(true),
                sel = ref.get_selected();
            if(!sel.length) { return false; }
            console.log(sel[0])
            ref.delete_node(sel);
            $.ajax({
                url: API_URL + `/destroy/${sel[0]}`,
                type: "GET",
                dataType: 'json',
                success: function (res) {
                    showNotify('success', 'The category deleted successfully!');
                },
                error: function (data) {
                    showNotify('danger', 'An error occurred during deleting category!');
                }
            });
        };
        
        function update_node(ref, sel) {
            ref.edit(sel, null, function(e) {
                var data = {
                    title: e.text
                };

                if (data) {
                    $.ajax({
                        url: API_URL + `/update/${e.id}`,
                        type: "POST",
                        data: data,
                        dataType: 'json',
                        success: function (res) {
                            
                        },
                        error: function (data) {
                            showNotify('danger', 'An error occurred during updating category!');
                        }
                    });
                } else {
                    showNotify('danger', 'Please fill out all required fields!');
                }
            });
        }

        function showNotify(type,message) {
            $.notify({
                // options
                message: message,
            }, {
                // settings
                type: type,
                offset: {
                    x: 25,
                    y: 25
                },
                placement: {
                from: 'top',
                align: 'right'
            },
                delay: 2000
            });
        }
        $(function () {
            var to = false;
            $('#search_box').keyup(function () {
                if(to) { clearTimeout(to); }
                to = setTimeout(function () {
                    var v = $('#search_box').val();
                    $('#category_tree').jstree(true).search(v);
                }, 250);
            });

            $("#category_tree").jstree({
                "core": {
                    "themes": {
                        "responsive": false,
                        "stripes" : true
                    },
                    "expand_selected_onload" : false, 
                    'force_text' : true,
                    // so that create works
                    "check_callback": true,
                    "data": {
                        "url": function(node) {
                            return API_URL + "/getCategories";
                        },
                        "data": function(node) {
                            return {
                                "parent": node.id
                            };
                        }
                    }
                },
                "types": {
                    "default": {
                        "icon": "fa fa-folder icon-lg kt-font-danger"
                    },
                    "file": {
                        "icon": "fa fa-file icon-lg kt-font-danger"
                    }
                },
                "state": {
                    "key": "demo1"
                },
                "plugins" : [ "contextmenu", "search", "state", "types", "wholerow" ],
                "contextmenu": {
                    "items": function( $node ) {
                        return {
                            "Create": {
                                "label": "Create",
                                "action": function( obj ) {
                                    node_create();
                                }
                            },
                            "Rename": {
                                "label": "Rename",
                                "action": function( obj ) {
                                    node_rename();
                                }
                            },
                            "Delete": {
                                "label": "Delete",
                                "action": function( obj ) {
                                    node_delete();
                                }
                            }
                        };
                    }
                }
            }).bind("dblclick.jstree", function (event) {
                node_rename();
            });
        });		
 
    </script>
@endsection