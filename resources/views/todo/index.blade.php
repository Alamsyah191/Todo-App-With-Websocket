@extends('layouts.app')

@section('content')
    @push('table')
        <link rel="stylesheet" href="{{ asset('assets/datatable/datatables.min.css') }}">
    @endpush
    <div class="container">
        <div class="card p-3 ">
            <div id="notifications"></div>
            <div class=""><a href="{{ route('todo.create') }}" class="btn btn-success">Add</a></div>
            <div class="table-responsive">
                <table id="datatable" class=" table-grid w-100">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Project</th>
                            <th>Desc Project</th>
                            <th>User Requested</th>
                            <th>Dept</th>
                            <th>Status</th>
                            <th>Date Requested</th>
                            <th>Deadline</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('script')
        <script src="{{ asset('assets/datatable/datatables.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.11.0/sweetalert2.min.js"
            integrity="sha512-Wi5Ms24b10EBwWI9JxF03xaAXdwg9nF51qFUDND/Vhibyqbelri3QqLL+cXCgNYGEgokr+GA2zaoYaypaSDHLg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(document).ready(function() {
                let userId = '{{ Auth::id() }}'; // Pastikan ini sesuai dengan metode autentikasi Anda
                // console.log(userId);
                window.Echo.private('App.Models.User.' + userId)
                    .notification((notification) => {
                        console.log(notification);

                        let timerInterval;
                        Swal.fire({
                            title: notification.project,
                            html: "I will close in <b></b> milliseconds.",
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const timer = Swal.getPopup().querySelector("b");
                                timerInterval = setInterval(() => {
                                    timer.textContent = `${Swal.getTimerLeft()}`;
                                }, 100);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                console.log("I was closed by the timer");
                            }
                        });

                        // Tambahkan baris baru ke DataTable
                        let table = $('#datatable').DataTable();
                        table.row.add({
                            project: notification.project,
                            desc_project: notification.desc_project,
                            name_requested: notification.name_requested,
                            dept: notification.dept,
                            status_project: notification.status_project,
                            requested_date: notification.requested_date,
                            deadline: notification.deadline
                        }).draw(false);
                    });
            });
        </script>

        {{-- <script>
            $(document).ready(function() {
                Echo.channel('todos')
                    .listen('TodoCreated', (e) => {
                        console.log('a');
                        alert(`Todo Created: ${e.todo.project}`);
                        // You can also update the UI here
                    });
            });
        </script> --}}

        <script>
            $(document).ready(function() {
                $('#datatable').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": function(data, callback, settings) {
                        $.ajax({
                            url: "{{ route('ajax_todo') }}",
                            type: "GET",
                            success: function(response) {
                                if (response.data.length === 0) {
                                    // No data returned
                                    // You can display a message or take other actions
                                    console.log("No data available.");
                                    return;
                                }
                                callback(response);
                            },
                            error: function(xhr, status, error) {
                                // Handle errors if any
                                console.error("An error occurred:", error);
                            }
                        });
                    },
                    "columns": [{
                            data: null,
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            }
                        },
                        {
                            data: 'project',
                            name: 'project',
                            defaultContent: '-'
                        },
                        {
                            data: 'desc_project',
                            name: 'desc_project',
                            defaultContent: '-'
                        },
                        {
                            data: 'name_requested',
                            name: 'name_requested',
                            defaultContent: '-'
                        },
                        {
                            data: 'dept',
                            name: 'dept',
                            defaultContent: '-'
                        },
                        {
                            data: 'status_project',
                            name: 'status_project',
                            defaultContent: '-'
                        },
                        {
                            data: 'requested_date',
                            name: 'requested_date',
                            defaultContent: '-'
                        },
                        {
                            data: 'deadline',
                            name: 'deadline',
                            defaultContent: '-'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return '<button class="btn btn-danger delete-btn" data-id="' + row.id +
                                    '">Delete</button>';
                            }
                        }
                    ]
                });
            });



            $(document).ready(function() {
                // var baseUrl = window.location.origin;
                // var deleteUrl = baseUrl + '/api/todo_destroy';

                $('#datatable').on('click', '.delete-btn', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ url('api/todo_destroy') }}/" + id,
                                type: "DELETE",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'Your item has been deleted.',
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    });
                                    $('#datatable').DataTable().ajax.reload();
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: xhr.responseText,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                    console.error("An error occurred:", error);
                                }
                            });
                        }
                    });
                });

            });
        </script>
    @endpush
@endsection
