@extends('admin.app')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">分类</a></li>
    </ol>
    <form action="{{url("catalog")}}" method="post">
        @foreach($data as $item)
            <div class="form-group row">
                <div class="col-lg-1">
                </div>
                <div class="col-lg-3">
                    <input type="text" class="form-control" name="data[]" value="{{$item}}">
                </div>
                <div class="col-lg-3">
                    <select id="catalog" class="form-control" name="catalogs[]">
                        <option value="">请选择分类</option>
                        @foreach(config('book.catalogs') as $catalog)
                            <option value="{{$catalog}}"
                                    @if($item == $catalog)selected @endif>{{$catalog}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endforeach
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-4">
                <button type="submit" class="btn btn-default">保存</button>
            </div>
        </div>
    </form>
@endsection