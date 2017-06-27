{{--@extends('app')--}}

{{--@section('content')--}}
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('acl.role.store') }}" method="post" class="">

                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="">Name</label>
                        <input class="form-control" name="name">
                    </div>

                    <div class="form-group">
                        <label class="">Label</label>
                        <input class="form-control" name="label">
                    </div>

                    <div class="form-group">
                        <button type="submit">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
{{--@stop--}}