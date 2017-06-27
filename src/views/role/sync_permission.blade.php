<form action="{{ route('acl.role.sync') }}" method="post">
    {{ csrf_field() }}

    <select name="role">
        @foreach($roles as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>

    <table border="1">
        <thead>
        <tr>
            <th>Model</th>
            <th>Method</th>
        </tr>
        </thead>
        <tbody>
        @foreach($permissions as $model => $permission)
            <tr>
                <th>{{ $model }}</th>
                <td>
                    @foreach($permission as $id => $method)
                        <label>
                            <input type="checkbox" name="permissions[]" value="{{ $id }}">
                            <span>{{ $method }}</span>
                        </label>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <button type="submit">Submit</button>
</form>
