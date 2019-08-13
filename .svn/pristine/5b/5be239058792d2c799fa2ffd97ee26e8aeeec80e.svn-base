var UITree = function () {

    /*var handleSample1 = function () {

        $('#tree_1').jstree({
            "core" : {
                "themes" : {
                    "responsive": false
                }            
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-folder icon-state-warning icon-lg"
                },
                "file" : {
                    "icon" : "fa fa-file icon-state-warning icon-lg"
                }
            },
            "plugins": ["types"]
        });

        // handle link clicks in tree nodes(support target="_blank" as well)
        $('#tree_1').on('select_node.jstree', function(e,data) { 
            var link = $('#' + data.selected).find('a');
            if (link.attr("href") != "#" && link.attr("href") != "javascript:;" && link.attr("href") != "") {
                if (link.attr("target") == "_blank") {
                    link.attr("href").target = "_blank";
                }
                document.location.href = link.attr("href");
                return false;
            }
        });
    }*/

   var handleSample2 = function () {
       $('#tree_2').jstree({
            'plugins': ["types"],
            'core': {
                "themes" : {
                    "responsive": false
                },    
                'data': [
                    {   "id":"100",
                        "text": "栏目一 ",
                        "state": {
                                "opened": true
                        },
                        "children": [{
                            "id":"2",
                            "text": "视频栏目 "
                        }, {
                            "id":"3",
                            "text": "相册栏目 "
                        }, {
                            "text": "新闻栏目 ",
                            "state": {
                                "opened": true
                            },
                            "children": [{
                                "text": "国内新闻 ",
                                "state": {
                                "opened": true
                        },
                                "children": [{
                                    "text": "浙江新闻 ",
                                    "state": {
                                "opened": true
                        },
                                    "children": [{
                                        "text": "杭州新闻 "
                                    },{
                                        "text": "宁波新闻 "
                                    }]
                                }]
                            }]
                        },
                        {
                            "text": "视频栏目 "
                        }, {
                            "text": "相册栏目 "
                        }, {
                            "text": "新闻栏目 ",
                            "state": {
                                "opened": true
                            },
                            "children": [{
                                "text": "国内新闻 ",
                                "state": {
                                "opened": true
                        },
                                "children": [{
                                    "text": "浙江新闻 ",
                                    "state": {
                                    "opened": true
                        },
                                    "children": [{
                                        "text": "杭州新闻 "
                                    },{
                                        "text": "宁波新闻 "
                                    }]
                                }]
                            }]
                        }
                        ]
                    },
                ]
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-folder icon-state-warning icon-lg"
                },
                "file" : {
                    "icon" : "fa fa-file icon-state-warning icon-lg"
                }
            }
        });
    }
       var handleSample3 = function () {
        $('#tree_3').jstree({
            'plugins': ["checkbox", "types"],
            'core': {
                "themes" : {
                    "responsive": false
                },    
                'data': [
                    {
                        "text": "栏目二",
                        "state": {
                                "opened": true
                        },
                        "children": [{
                            "text": "视频栏目33333"
                        }, {
                            "text": "相册栏目333"
                        }, {
                            "text": "新闻栏目3333",
                            "state": {
                                "opened": true
                            },
                        }]
                    },
                ]
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-folder icon-state-warning icon-lg"
                },
                "file" : {
                    "icon" : "fa fa-file icon-state-warning icon-lg"
                }
            }
        });
    }
           var handleSample4 = function () {
        $('#tree_4').jstree({
            'plugins': ["checkbox", "types"],
            'core': {
                "themes" : {
                    "responsive": false
                },    
                'data': [
                    {
                        "text": "栏目三",
                        "state": {
                                "opened": true
                        },
                        "children": [{
                            "text": "视频栏目44444"
                        }, {
                            "text": "相册栏目4444"
                        }, {
                            "text": "新闻栏目444",
                            "state": {
                                "opened": true
                            },
                        }]
                    },
                ]
            },
            "types" : {
                "default" : {
                    "icon" : "fa fa-folder icon-state-warning icon-lg"
                },
                "file" : {
                    "icon" : "fa fa-file icon-state-warning icon-lg"
                }
            }
        });
    }
    var ajaxTreeSample = function () {

        $("#tree_single_select_with_ajax").jstree({
            "core": {
                "themes": {
                    "responsive": false
                },
                // so that create works
                "check_callback": true,
                'data': {
                    'url': function (node) {
                        return '/category/privateAjax';
                    },
                    'data': function(node) {
                        return { 'id' : node.id };
                    }
                }
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder icon-state-warning icon-lg"
                },
                "file": {
                    "icon": "fa fa-file icon-state-warning icon-lg"
                }
            },
            "state": {"key": "demo3"},
            "plugins": ["dnd", "state", "types"]
        });

    };

    return {
        //main function to initiate the module
        init: function () {
            //handleSample1();
            handleSample2();
            handleSample3();
            handleSample4();
            //contextualMenuSample();
            ajaxTreeSample();
        }
    };
}();