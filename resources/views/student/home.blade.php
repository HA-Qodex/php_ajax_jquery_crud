@extends('layouts.app')

@section('title', 'Home')

@section('content')

    <!-- Modal -->
    <div class="modal fade" id="AddStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="errorList" style="padding-left: 20px "></ul>

                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="name form-control" name="name">
                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="email form-control" name="email">
                    </div>

                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="number" class="phone form-control" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="">Course</label>
                        <input type="text" class="course form-control" name="course">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_student">Save Student</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="EditStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="updateErrorList" style="padding-left: 20px "></ul>
                    <input type="hidden" class="student_id">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="name form-control" name="name">
                    </div>

                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="email form-control" name="email">
                    </div>

                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="number" class="phone form-control" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="">Course</label>
                        <input type="text" class="course form-control" name="course">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary update_student">Update Student</button>
                </div>
            </div>
        </div>
    </div>


    <div class="container py-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            Student Data
                            <a href="#" class="btn btn-primary float-end btn-sm" data-bs-toggle="modal"
                               data-bs-target="#AddStudentModal">Add Student</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <p id="successMessage"></p>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            fetchStudent();

            function fetchStudent(){
                $.ajax({
                    type:'GET',
                    url:'/fetch',
                    dataType:'json',
                    success: function (response){
                        $('tbody').html('');
                        $.each(response.students, function (key, data) {
                            $('tbody').append('<tr>\
                                <td>'+data.id+'</td>\
                                <td>'+data.name+'</td>\
                                <td>'+data.email+'</td>\
                                <td>'+data.phone+'</td>\
                                <td>'+data.course+'</td>\
                                <td style="text-align: center; display:flex; justify-content: space-evenly"><button type="button" value="'+data.id+'" class="edit_student btn btn-primary btn-sm text-center">Edit</button>\<' +
                                'button type="button" value="'+data.id+'" class="delete_student btn btn-danger btn-sm text-center">Delete</button></td>\
                                </tr>');
                        })
                    }

                });
            }

            $(document).on('click', '.edit_student', function (e){
                e.preventDefault();
                const student_id = $(this).val();

                $('#EditStudentModal').modal('show');

                $.ajax({
                   type:'GET',
                   url:'/edit-student/'+student_id,
                   success: function (response){
                       if(response.message===400){
                           $('#successMessage').html('');
                           $('#successMessage').addClass('alert alert-danger');
                           $('#successMessage').text(response.message);
                       } else {
                           $('#EditStudentModal').modal('show');
                           $('.student_id').val(response.student.id);
                           $('.name').val(response.student.name);
                           $('.email').val(response.student.email);
                           $('.phone').val(response.student.phone);
                           $('.course').val(response.student.course);
                       }
                   }
                });
            });

            $(document).on('click','.update_student', function (e){
             e.preventDefault();

             const student_id = $('.student_id').val();

             const data = {
                 'name': $('.name').val(),
                 'email': $('.email').val(),
                 'phone': $('.phone').val(),
                 'course': $('.course').val(),
             }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });


                $.ajax({
                 type: "PUT",
                 url: "/update-student/"+student_id,
                 data: data,
                 dataType: "json",
                 success: function (response) {
                     if(response.status===400) {
                         $("#errorList").html("");
                         $('#errorList').addClass('alert alert-danger');
                         $.each(response.errors, function (key, error_value) {
                             $('#errorList').append('<li>' + error_value + '</li>');
                         })
                     } else {
                         $('#successMessage').html('');
                         $('#successMessage').addClass('alert alert-success');
                         $('#successMessage').text(response.message);
                         $('#EditStudentModal').modal('hide');
                         $('#EditStudentModal').find('input').val('');
                         fetchStudent();
                     }
                 }
             });

            });

            $(document).on('click', '.add_student', function (e) {
                e.preventDefault();
                const data = {
                    'name': $('.name').val(),
                    'email': $('.email').val(),
                    'phone': $('.phone').val(),
                    'course': $('.course').val(),
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "",
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === 400) {
                            $("#errorList").html("");
                            $('#errorList').addClass('alert alert-danger');
                            $.each(response.errors, function (key, error_value) {
                                $('#errorList').append('<li>' + error_value + '</li>');
                            })
                        } else {
                            $('#successMessage').html('');
                            $('#successMessage').addClass('alert alert-success');
                            $('#successMessage').text(response.message);
                            $('#AddStudentModal').modal('hide');
                            $('#AddStudentModal').find('input').val('');
                            fetchStudent();
                        }
                    }
                });
            })
        });
    </script>

@endsection
