@extends('admin.app')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">图书</a></li>
        <li class="active">{{$book->name}}</li>
    </ol>
    <table class="table table-striped">
        <caption>章</caption>
        <thead>
        <tr>
            <th>#</th>
            <th>名称</th>
            <th>排序</th>
            <th>小节数</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td><a href="{{url("chapter/$item->id/edit")}}">{{$item->name}}</a></td>
                <td><input type="text" class="form-control" value="{{$item->order}}" data-id="{{$item->id}}"></td>
                <td><a href="{{url("section?chapter_id=$item->id")}}">{{$item->sectionNum}}</a></td>
                <td><button type="button" class="btn btn-primary delete">删除</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@section('js')
    <script>
        $(function () {
            $('input').on('blur', function () {
                $.post('chapter/' + $(this).data('id'), {order: $(this).val()})
            })
        })
    </script>
@endsection