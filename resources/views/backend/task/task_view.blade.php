@extends('layouts/admin')
@section('content')
@can('task_access')
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3>Tasks List</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>SL</th>
                            <th>Task</th>
                            <th>Deadline</th>
                            <th>Assigned To</th>
                            <th>Assigned By</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($tasks as $sl=>$task)
                        <tr>
                            <td>{{$sl+1}}</td>
                            <td class="text-wrap">{{$task->name}}</td>
                            <td class="text-wrap">
                                @php
                                    $startDateTime = \Carbon\Carbon::now(); // Current date and time
                                    $endDateTime = \Carbon\Carbon::parse($task->deadline);

                                    // Get the difference
                                    $diff = $startDateTime->diff($endDateTime);

                                    $days = $diff->days;
                                    $hours = $diff->h;
                                    $seconds = $diff->s;

                                    $output = ($days > 0 ? "{$days} days, " : '') . ($hours > 0 ? "{$hours} hours, " : '') . ($seconds > 0 ? "{$seconds} seconds, " : ''). "left.";

                                @endphp
                                {{$output}}
                            </td>
                            <td>{{$task->rel_to_user->name}}</td>
                            <td>{{$task->rel_to_user2->name}}</td>
                            <td>
                                <span class="badge badge-{{($task->status == 0 ? 'danger':'primary')}}">{{($task->status == 0 ? 'Pending':'Done')}}</span>
                            </td>
                            <td>
                                @can('task_delete')
                                <a class="btn btn-danger del" data-link="{{route('task.del', $task->id)}}"><i class="fas fa-trash"></i></a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @can('task_assignment')
            <div class="card">
                <div class="card-header">
                    <h3>Add Task</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('task.add')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Task</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Deadline</label>
                            <input type="datetime-local" class="form-control" name="deadline">
                        </div>
                        <div class="mb-3">
                            <label for="assign_to" class="form-label">Assign To</label>
                            <select name="assign_to" class="form-control" name="assign_to">
                                <option>Select Staff</option>
                                @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@else
<h3>You do not have permission to view this page</h3>
@endcan
@endsection
@section('jscript')
    <script>
        let del = document.querySelector(".del");
        del.onclick = function(){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    let link = del.dataset.link;
                    window.location.href = link;
                }
            });
        }
    </script>
    @if (session('task_deleted'))
        <script>
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "{{session('task_deleted')}}",
                showConfirmButton: false,
                timer: 1500
                });
        </script>
    @endif
@endsection