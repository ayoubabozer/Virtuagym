

function sortable()
{
    $( ".sortable" ).sortable({
        revert: true,
        connectToSortable: ".sortable",
        receive: function(event,ui) {
            var exerciseId = ui.item.attr('data-exercise-id');
            var dayId = $(this).attr('data-day-id');
            $.ajax({
                url: "v1/exercises",
                type:"POST",
                data:{dayId:dayId, exerciseId:exerciseId},
            });
        }
    });
    $( "#exercise-list li" ).draggable({
        connectToSortable: ".sortable",
        helper: "clone",
        revert: "invalid",
        start  : function(event, ui){
            $(ui.helper).addClass("ui-helper");
        }

    });
    $( "ul, li" ).disableSelection();
}

function draggable()
{
    $( "#plans-tbody td" ).draggable({
        helper: "clone",
    });

    $( "#users-tbody tr" ).droppable({
        drop: function(event, ui) {
            var user_id = $(this).attr('data-user-id');
            var plan_id = ui.draggable.attr('data-plan-id');
            $.ajax({
                url:"v1/assignPlans",
                type:"PUT",
                data:{user_id:user_id, plan_id:plan_id},
                success: function (data) {
                    fetch_users();
                }
            });
        },

    });
}





function fetch_exercises() {
    $.ajax({
        url: "v1/exercises",
        dataType: "json",
        success: function (data) {
            var html = '';
            $.each(data, function(k, v) {
                html += '<li data-exercise-id="'+v.id+'" class="list-group-item">'+v.name+'</li>';
            });
            $('#exercise-list').html(html);
        }
    });
}

function fetch_users() {
    $.ajax({
        url: "v1/users",
        dataType:"json",
        success: function (data) {
            var html = '';

            $.each(data, function(k, v) {
                var plan_name = 'drag plan ..';
                if(v.plan_name)
                    plan_name = v.plan_name;

                html += '<tr class="user-tr" data-user-id="'+v.id+'">' +
                    '                        <td>'+v.first_name+'</td>' +
                    '                        <td>'+v.last_name+'</td>' +
                    '                        <td>'+v.email+'</td>' +
                    '                        <td>'+plan_name+'</td>' +
                    '                        <td><button data-user-id="'+v.id+'" type="button" class="btn btn-warning btx-xs edit-user">' +
                    '                            Edit</button>' +
                    '                        </td>' +
                    '                        <td><button data-user-id="'+v.id+'" type="button" class="btn btn-danger btx-xs delete-user">\n' +
                    '                            Delete</button>\n' +
                    '                        </td>\n' +
                    '                    </tr>';

            });
            $("#users-tbody").html(html);
            draggable();
        }
    });
}

function fetch_plans() {
    $.ajax({
        url: "v1/plans",
        dataType:"json",
        success: function (data) {
            var html = '';

            $.each(data, function(k, v) {

                html += '<tr id="plan_'+v.id+'" class="plan-tr" data-plan-id="'+v.id+'">' +
                    '                        <td id="plan_td_'+v.id+'" data-plan-id="'+v.id+'" class="plan-td">'+v.name+'</td>' +
                    '                        <td><button data-plan-id="'+v.id+'" type="button" class="btn btn-warning btx-xs edit-plan">' +
                    '                            Edit</button>' +
                    '                        </td>' +
                    '                        <td><button data-plan-id="'+v.id+'" type="button" class="btn btn-danger btx-xs delete-plan">' +
                    '                            Delete</button>' +
                    '                        </td>' +
                    '                    </tr>';

            });
            $('#plans-tbody').html(html);
            draggable();
        }
    });
}

function fetch_days(plan_id) {
    $('#hidden_day_plan_id').val(plan_id);
    $('.plan-tr').removeClass('selected');
    $('#plan_'+plan_id).addClass('selected');
    var text = $('#plan_td_'+plan_id).text();
    $.ajax({
        url:"v1/days/"+plan_id,
        dataType:"json",
        success:function(data)
        {
            var html = '                <h4 id="days-title">Days</h4>' +
                '                <input type="button" class="btn btn-success add-new-day" value="+ day" style="float: right">' +
                '                <div class="card" style="margin-top: 70px">' +
                '                    <ul class="list-group list-group-flush" id="day-list">';
            $.each(data, function(k, v) {
                html += '<li class="list-group-item">'+k+'</li>';
                var dayId = v[0].dayId;
                html += '<ul data-day-id="'+dayId+'" class="list-group list-group-flush connectedSortable sortable">';
                $.each(v, function(k1, v1) {
                    var exerciseName = v1.exerciseName;
                    if(exerciseName)
                        html += '<li class="list-group-item">'+exerciseName+'</li>';
                });
                html += '</ul>';
                html += '</ul></div>';
            });

            $('#days-div').html(html);
            $('#days-title').html(text);
            sortable();
        }
    });
}


$(document).ready(function() {

    fetch_plans();
    fetch_users();
    fetch_exercises();

    $("#user-form").submit(function (event) {
        event.preventDefault(); //prevent default action

        var form_data = $(this).serialize();
        var action = $('#action').val();
        var method = "POST";
        var url = "v1/users";
        if(action == 'updateUser')
        {
            method = "PUT";
            var id = $("#hidden_id").val();
            url += "/"+id;
        }
        $.ajax({
            url: url,
            type: method,
            data: form_data,
            success: function (data) {
                fetch_users();
                $('#user-form')[0].reset();
                $('#user-modal').modal('hide');
                if (data == 'insert') {
                    alert("User inserted successfully!.");
                }
                if (data == 'update') {
                    alert("User data had been upated successfully!.");

                }
            }
        });

    });

    $("#plan-form").submit(function (event) {
        event.preventDefault(); //prevent default action
        var form_data = $(this).serialize();
        var action = $('#plan-action').val();
        var method = "POST";
        var url = "v1/plans";
        if(action == 'updatePlan')
        {
            method = "PUT";
            var id = $("#hidden_plan_id").val();
            url += "/"+id;
        }

        $.ajax({
            url: url,
            type: method,
            data: form_data,
            success: function (data) {
                fetch_plans();
                fetch_users();
                $('#plan-form')[0].reset();
                $('#plan-modal').modal('hide');
                if (data == 'insert') {
                    alert("Plan inserted successfully!.");
                }
                if (data == 'update') {
                    alert("Plan data had been upated successfully!.");

                }
            }
        });

    });

    $("#day-form").submit(function (event) {
        event.preventDefault(); //prevent default action
        var plan_id = $('#hidden_day_plan_id').val();
        var form_data = $(this).serialize();
        $.ajax({
            url: "v1/days",
            method: "POST",
            data: form_data,
            success: function (data) {
                fetch_days(plan_id);
                $('#day-form')[0].reset();
                $('#day-modal').modal('hide');
                if (data == 'insert') {
                    alert("Day inserted successfully!.");
                }

            }
        });

    });

    $('#add-new-user').on('click', function () {
        $('#action').val('addUser');
        $('#modal-title').html('Add new user');
        $('#user-modal').modal('show');
    });

    $('#add-new-plan').on('click', function () {
        $('#plan-action').val('addPlan');
        $('#plan-modal-title').html('Add new plan');
        $('#plan-modal').modal('show');
    });



});

$(document).on('click', '.edit-user', function(){
    var id = $(this).attr('data-user-id');
    $.ajax({
        url:"v1/users/"+id,
        dataType:"json",
        success:function(data)
        {
            $('#hidden_id').val(id);
            $('#user-firstname').val(data.first_name);
            $('#user-lastname').val(data.last_name);
            $('#user-email').val(data.email);
            $('#action').val('updateUser');
            $('#modal-title').html('Edit user data');
            $('#user-modal').modal('show');
        }
    });
});



$(document).on('click', '.edit-plan', function(){
    var id = $(this).attr('data-plan-id');
    $.ajax({
        url:"v1/plans/"+id,
        dataType:"json",
        success:function(data)
        {
            $('#hidden_plan_id').val(id);
            $('#plan-name').val(data.name);
            $('#plan-action').val('updatePlan');
            $('#plan-modal-title').html('Edit plan data');
            $('#plan-modal').modal('show');
        }
    });
});

$(document).on('click', '.plan-td', function(){
    var id = $(this).attr('data-plan-id');
    fetch_days(id);
});


$(document).on('click', '.delete-user', function(){
    var id = $(this).attr('data-user-id');
    if(confirm("Are you sure you want to remove this user ?"))
    {
        $.ajax({
            url:"v1/users/"+id,
            method:"DELETE",
            success:function(data)
            {
                fetch_users();
            }
        });
    }

});

$(document).on('click', '.delete-plan', function(){
    var id = $(this).attr('data-plan-id');
    if(confirm("Are you sure you want to remove this plan ?"))
    {
        $.ajax({
            url:"v1/plans/"+id,
            type:"DELETE",
            success:function(data)
            {
                fetch_plans();
            }
        });
    }

});

$(document).on('click', '.add-new-day', function(){
    $('#day-action').val('addDay');
    $('#day-modal-title').html('Add new day');
    $('#day-modal').modal('show');

});



