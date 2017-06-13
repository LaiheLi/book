@extends('admin.app')
@section('content')
    <ol class="breadcrumb">
        <li><a href="/">图书</a></li>
        <li><a href="{{url("section?book_id=$book->id")}}">{{$book->name}}</a></li>
        @if($chapter)
            <li><a href="{{url("section?chapter_id=$chapter->id")}}">{{$chapter->name}}</a></li>
        @endif
    </ol>
    <table class="table table-striped">
        <caption>节</caption>
        <thead>
        <tr>
            <th>#</th>
            <th>名称</th>
            <th>排序</th>
            <!--th>操作</th-->
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr data-id="{{$item->id}}">
                <td><a href="{{url("section/$item->id/edit")}}">{{$item->id}}</a></td>
                <td><input type="text" class="form-control name" value="{{$item->name}}"></td>
                <td><input type="text" class="form-control order" value="{{$item->order}}"></td>
                <!--td><button type="button" class="btn btn-primary delete">删除</button></td-->
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
                    url: 'section/' + id,
                    type: 'delete',
                    success: function () {
                        location.reload()
                    }
                });
            });
            $('input.name').on('change', function () {
                var id = $(this).parent().parent().data('id');
                $.post('section/' + id, {name: $(this).val()})
            });
            $('input.order').on('change', function () {
                var id = $(this).parent().parent().data('id');
                $.post('section/' + id, {order: parseInt($(this).val()) || 0})
            })
        })
    </script>
@endsection