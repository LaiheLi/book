@extends('admin.app')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">图书</a></li>
        <li class="active">{{$item->name}}</li>
    </ol>
    <form action="{{url("book/$item->id")}}" method="post">
        <div class="form-group">
            <label>封面</label>
            <input type="text" class="form-control" name="cover" value="{{$item->cover}}">
            <a href="{{url("book/$item->id/image")}}" target="_blank">
                <img src="{{url("book/$item->id/image")}}" alt="" style="height: 300px;">
            </a>
        </div>
        <div class="form-group">
            <label>名称</label>
            <input type="text" class="form-control" name="name" value="{{$item->name}}">
        </div>
        <div class="form-group">
            <label>类型</label>
            <select name="type" class="form-control">
                <option>请选择类型</option>
                <option value="1" @if($item->type == 1)selected @endif>章-节</option>
                <option value="0" @if($item->type == 0)selected @endif>节</option>
            </select>
        </div>
        <div class="form-group">
            <label>分类</label>
            <select name="catalog" class="form-control">
                <option>请选择分类</option>
                @foreach(config('book.catalogs') as $catalog)
                    <option value="{{$catalog}}" @if($item->catalog == $catalog)selected @endif>{{$catalog}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>作者</label>
            <input type="text" class="form-control" name="author" value="{{$item->author}}">
        </div>
        <div class="form-group">
            <label>文件路径</label>
            <input type="text" class="form-control" name="path" value="{{$item->path}}">
        </div>
        <div class="form-group">
            <label>简介</label>
            <textarea type="text" class="form-control" rows="8" name="description">{{$item->description}}</textarea>
        </div>
        <button type="submit" class="btn btn-default">提交</button>
    </form>
@endsection