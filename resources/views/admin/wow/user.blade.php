@extends('admin.master')
@section('content')
    <table class="table table-hover table-bodered">
        <tr>
            <th>STT</th>
            <th>Name</th>
            <th>Fbid</th>
            <th>Money</th>
            <th>Roles</th>
            <th>Tools</th>
        </tr>
    @foreach ($users as $k=>$user)
        <tr>
            <td>{{ $k + 1 }}</td>
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['fbid'] }}</td>
            <td>{{ $user['money'] }}</td>
            <td>{{ $user['roles'] }}</td>
            <td>
                <button class="btn btn-success">Edit</button>
                <button class="btn btn-danger">Block</button>
            </td>
        </tr>
    @endforeach
    </table>
    <div class="text-center">
        {{ $users->links() }}
    </div>
@endsection