@extends('admin.app')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">图书</a></li>
        <li><a href="{{url("chapter?book_id=$item->book_id")}}">{{$item->book->name}}</a></li>
        <li class="active">{{$item->name}}</li>
    </ol>
    <form action="{{url("chapter/$item->id")}}" method="post">
        <div class="form-group">
            <label>名称</label>
            <input type="text" class="form-control" name="name" value="{{$item->name}}">
        </div>
        <div class="form-group">
            <label>排序</label>
            <input type="text" class="form-control" name="order" value="{{$item->order}}">
        </div>
        <button type="submit" class="btn btn-default">提交</button>
    </form>
@endsection