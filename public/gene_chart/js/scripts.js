"use strict";
! function(n) {
    n((function() {
        n("#chart-container").orgchart({
            data: {
                id: "1",
                name: document.getElementById("me").value,
                title: "Me",
                relationship: {
                    children_num: 2 
                },
                children: [
                    {
                        id: "2",
                        name: document.getElementById("levelOneFirst").value,
                        title: "",
                        relationship: {
                            parentId: 1,
                            children_num: 2,
                            sibling_no: 1
                        },
                        children: [
                            {
                                id: "3",
                                name: document.getElementById("f_u_1").value,
                                title: "",
                                relationship: {
                                    parentId: 2,
                                    children_num: 0,
                                    sibling_no: 1
                                },
                            },
                            {
                                id: "4",
                                name: document.getElementById("f_u_2").value,
                                title: "",
                                relationship: {
                                    parentId: 2,
                                    children_num: 0,
                                    sibling_no: 1
                                },
                            }
                        ]
                    },
                    {
                        id: "5",
                        name: document.getElementById("levelOneSec").value,
                        title: "",
                        relationship: {
                            parentId: 1,
                            children_num: 2,
                            sibling_no: 2
                        },
                        children: [
                            {
                                id: "6",
                                name: document.getElementById("s_u_1").value,
                                title: "",
                                relationship: {
                                    parentId: 5,
                                    children_num: 0,
                                    sibling_no: 1
                                },
                            },
                            {
                                id: "7",
                                name: document.getElementById("s_u_2").value,
                                title: "",
                                relationship: {
                                    parentId: 5,
                                    children_num: 0,
                                    sibling_no: 1
                                },
                            }
                        ]
                    }
                ]
            },
            depth: 2,
            nodeTitle: "name",
            nodeContent: "title",
            nodeID: "id",
            createNode: function(e, i) {
                var a = n("<i>", {
                        class: "fa fa-info-circle second-menu-icon",
                        click: function() {
                            n(this).siblings(".second-menu").toggle()
                        }
                    }),
                    t = '<div class="second-menu"><img class="avatar" src="img/avatar/' + i.id + '.jpg"></div>';
                e.append(a).append(t)
            }
        })
    }))
}(jQuery);