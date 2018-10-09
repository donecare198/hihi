@extends('admin.master')
@section('content')
    <form method="GET">
        <div class="row form-group">
            <div class="col-md-2">
                <input type="text" name="keyword" value="{{app('request')->get('keyword')}}" class="form-control" placeholder="Name or Fbid" />
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-danger">Search</button>
            </div>
        </div>
    </form>
    <table class="table table-hover table-bodered">
        <tr>
            <th>STT</th>
            <th>Name</th>
            <th>Fbid</th>
            <th>Money</th>
            <th>Status</th>
            <th>Edit</th>
        </tr>
    @foreach ($users as $k=>$user)
        <tr>
            <td>{{ $k + 1 }}</td>
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['fbid'] }}</td>
            <td>{{ $user['money'] }}</td>
            <td>
                @if($user['active'] == 1)
                    <a href="/admin/block_user/{{$user['_id']}}/0" class="btn-success">active</a>
                @else
                    <a href="/admin/block_user/{{$user['_id']}}/1" class="btn-danger">block</a>
                @endif
            </td>
            <td>
                <a href="/admin/user_w/{{$user['_id']}}" class="btn btn-success">Edit</button>
            </td>
        </tr>
    @endforeach
    </table>
    <div class="text-center">
        {{ $users->links() }}
    </div>
@endsection