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
            <th style="width: 80px;">#</th>
            <th style="width: 60px;">小节数</th>
            <th style="width: 80px;">排序</th>
            <th>名称</th>
            <!--th>操作</th-->
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr data-id="{{$item->id}}">
                <td><a href="{{url("chapter/$item->id/edit")}}">{{$item->id}}</a></td>
                <td><a href="{{url("section?chapter_id=$item->id")}}">{{$item->sectionNum}}</a></td>
                <td><input type="text" class="form-control order" value="{{$item->order}}" data-id="{{$item->id}}"></td>
                <td><input type="text" class="form-control name" value="{{$item->name}}" data-id="{{$item->id}}"></td>
                <!--td>
                    <button type="button" class="btn btn-primary delete">删除</button>
                </td-->
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@section('js')
    <script>
        $(function () {
            //删除章
            $('.delete').on('click', function () {
                var $this = $(this);
                var id = $this.parent().parent().data('id');
                $this.button('loading');

                $.ajax({
                    url: 'chapter/' + id,
                    type: 'delete',
                    success: function () {
                        location.reload()
                    }
                });
            });
            $('input.name').on('change', function () {
                var id = $(this).parent().parent().data('id');
                $.post('chapter/' + id, {name: $(this).val()})
            });
            $('input.order').on('change', function () {
                var id = $(this).parent().parent().data('id');
                $.post('chapter/' + id, {order: parseInt($(this).val()) || 0})
            })
        })
    </script>
@endsection