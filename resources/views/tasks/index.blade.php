<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Tasts</title>
    <style>
        .error{
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-3">
                <button type="button" class="btn btn-primary addData">Add</button>
            </div>
        </div>
        <div class="row">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Date</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
               @foreach ($tasks as $task)
               <tr>
                <th scope="row">{{$task->id}}</th>
                <td>{{$task->name}}</td>
                <td>{{$task->description}}</td>
                <td>{{$task->date}}</td>
                <td>
                    <button type="button" class="btn btn-info updateData" data-id="{{$task->id}}">Update</button>
                    <button type="button" class="btn btn-danger deleteData" data-id="{{$task->id}}">Delete</button>
                </td>
              </tr>
               @endforeach
            </tbody>
          </table>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form id="form" method="POST">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                  <label for="recipient-name" class="col-form-label">Name:</label>
                  <input type="hidden" class="form-control" id="id" name="id" value="">
                  <input type="text" class="form-control" id="name" name="name" required>
                  <label id="name-error" class="error" for="name"></label>

                </div>
                <div class="mb-3">
                  <label for="message-text" class="col-form-label">Description:</label>
                  <textarea class="form-control" id="description" name="description" required></textarea>
                  <label id="description-error" class="error" for="description"></label>
                </div>
                <div class="mb-3">
                    <label for="message-text" class="col-form-label">Date:</label>
                    <input type="date" class="form-control" id="date"  name="date" required/>
                    <label id="date-error" class="error" for="date"></label>
                  </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">submit</button>
            </div>
         </form>
          </div>
        </div>
      </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <script>
        $( document ).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(".addData").click(function(e){
                var $alertas = $('#form');
                $alertas.trigger('reset');
                $alertas.validate().resetForm();
                $alertas.find('.error').removeClass('error');
                $("#exampleModalLabel").text('Add task');
                $('#exampleModal').modal('show');
            })

            $(".updateData").click(function(e){
                var $alertas = $('#form');
                $alertas.trigger('reset');
                $alertas.validate().resetForm();
                $alertas.find('.error').removeClass('error');
                getData($(this).data('id'));
                $("#exampleModalLabel").text('Update task');
                $('#exampleModal').modal('show');
            })

            $(".deleteData").click(function(e){
                if (confirm("Are you sure? you want to delete task.")) {
                    deleteData($(this).data('id'));
                }
            })

            function getData(id)
            {
                $.ajax({
                    type: "GET",
                    url: `/tasks/${id}/edit`,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        Object.keys(response.data).map((key)=>{
                            $("#"+key).val(response.data[key]);
                        })
                        console.log("response.data.data",response.data);
                    },
                    error: function(){
                        location.reload();
                    }
                });
            }

            function deleteData(id)
            {
                $.ajax({
                    type: "DELETE",
                    url: `/tasks/${id}`,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        location.reload();
                        // console.log("response.data.data",response.data);
                    },
                    error: function(){
                        location.reload();
                    }
                });
            }

 $("#form").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 191,
                },
                description: {
                    required: true,
                    maxlength: 255,
                },
                date: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: 'Please enter name',
                    maxlength: 'Please enter less then 191 characters',
                },
                description: {
                  required: 'Please enter description',
                  maxlength: 'Please enter less then 255 characters',
               },
               date: {
                    required: 'Please enter date',
                },
            },
            submitHandler: function (form, event) { // for demo
                event.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{url('tasks')}}",
                    data: new FormData(form),
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.status == 422) {
                            $.each(response.errors, function(key, val) {
                                $("#" + key + "-error").text(val[0]);
                                $("#" + key + "-error").addClass("error");
                            });
                            $(".error").removeAttr("style");
                            return false;
                        }
                        else{
                            // location.reload();
                        }
                    },
                    error: function(){
                        $('#loader').hide();
                        $('#Modal-btn').prop('disabled', false);
                    }
                });

            }
    });

        });
    </script>
</body>
</html>
