@extends('admin.app')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">图书</a></li>
        <li><a href="{{url("section?book_id=$item->book_id")}}">{{$item->book->name}}</a></li>
        @if($item->chapter)
            <li><a href="{{url("section?chapter_id=$item->chapter_id")}}">{{$item->chapter->name}}</a></li>
        @endif
        <li class="active">{{$item->name}}</li>
    </ol>
    <form action="{{url("section/$item->id")}}" method="post">
        <div class="form-group">
            <label>名称</label>
            <input type="text" class="form-control" name="name" value="{{$item->name}}">
        </div>
        <div class="form-group">
            <label>排序</label>
            <input type="text" class="form-control" name="order" value="{{$item->order}}">
        </div>
        <div class="form-group">
            <label>路径</label>
            <input type="text" class="form-control" name="path" value="{{$item->path}}">
        </div>
        <div class="form-group">
            <label>内容</label>
            <textarea class="form-control" rows="20" readonly>{!! $txt !!}</textarea>
        </div>
        <button type="submit" class="btn btn-default">提交</button>
    </form>
@endsection