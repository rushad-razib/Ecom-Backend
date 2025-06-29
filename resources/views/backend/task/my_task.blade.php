@extends('layouts.admin')
@section('content')
@can('task_read')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h3>My Task</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>SL</th>
                                <th>Task</th>
                                <th>Deadline</th>
                                <th>Assigned By</th>
                                <th>Status</th>
                            </tr>
                            @foreach ($tasks as $sl=>$task)
                            <tr>
                                <td>{{$sl+1}}</td>
                                <td>{{$task->name}}</td>
                                <td>{{$task->deadline}}</td>
                                <td>{{$task->rel_to_user2->name}}</td>
                                <td><a type="button" data-toggle="modal" data-id={{$task->id}}  data-target="#exampleModal" class="id badge badge-{{($task->status==0?'secondary':'danger')}}">{{($task->status==0?'Pending':'Done')}}</a></td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
<h3>You do not have permission to view this page</h3>
@endcan
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('task.comment')}}" method="POST">
                    @csrf
                    <input type="hidden" class="task_id" name="task_id">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea class="form-control" name="comment" cols="30" rows="10"></textarea>
                    </div>
            </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
      </div>
    </div>
  </div>
@endsection
@section('jscript')
    <script>
        let id = document.querySelector('.id');
        let task_id = document.querySelector('.task_id')
        id.onclick = function(){
            task_id.value = id.dataset.id;
        }
    </script>
@endsection