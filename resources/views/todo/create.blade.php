@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card p-3">
        <form id="createTodoForm" method="post">
            @csrf
            <div class="form-group">
                <label for="project">Project</label>
                <input type="text" class="form-control" name="project" id="project">
            </div>
            <div class="form-group">
                <label for="desc_project">Desc Project</label>
               <textarea id="editor" name="desc_project" class="form-control"></textarea>

            </div>
            <div class="form-group">
                <label for="dept">Dept</label>
                <input type="text" class="form-control" name="dept" id="dept">
            </div>
            <div class="form-group">
                <label for="name_requested">Requested User</label>
                <input type="text" class="form-control" name="name_requested" id="name_requested">
            </div>
            <div class="form-group">
                <label for="requested_date">Requested Date</label>
                <input type="date" class="form-control" name="requested_date" id="requested_date">
            </div>
            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" class="form-control" name="deadline" id="deadline">
            </div>
            <div class="form-group">
                <label for="status_project">Status</label>
                <select name="status_project" class="form-select" id="status_project">
                    <option value="">--Select Status--</option>
                    <option value="On Progress">On Progress</option>
                    <option value="Pending">Pending</option>
                    <option value="Done">Done</option>
                </select>
            </div>

            <div class="form-group mt-3 text-center">
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

@push('script')
    

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <script src="{{ asset('assets/texteditor/ckeditor.js') }}"></script>

 <script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );
</script>

<script>
    // Ajax request to save todo data
    $(document).ready(function() {
        $('#createTodoForm').submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize();

            // Send Ajax request
            $.ajax({
                url: '{{ route('todo_create') }}',
                type: 'POST',
                data: formData,
                success: function(data) {
                    console.log(data);
                     // Handle success response
                    // Redirect to success page or show success message
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Handle error response
                }
            });
        });
    });
</script>
@endpush
@endsection
