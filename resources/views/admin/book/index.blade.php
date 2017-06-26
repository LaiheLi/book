@extends('admin.app')
@section('content')
    <ol class="breadcrumb">
        <li class="active">图书</li>
    </ol>
    <div class="row">
        <form action="{{url('book')}}">
            <div class="col-lg-1">
                <div class="input-group">
                    <select id="catalog" name="catalog" class="form-control">
                        <option value="">请选择分类</option>
                        @foreach($catalogs as $catalog)
                            <option value="{{$catalog->catalog}}"
                                    @if(request('catalog') == $catalog->catalog)selected @endif>{{$catalog->catalog}}
                                - {{$catalog->num}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-1">
                <div class="input-group">
                    <input id="name" name="name" class="form-control" value="{{request('name')}}">
                </div>
            </div>
        </form>
        <div class="col-lg-1 col-lg-offset-9">
            <a type="button" class="btn btn-info pull-right" href="{{url('book/export')}}">导出</a>
        </div>
    </div>
    {!! $data->appends(request()->only(['catalog']))->links() !!}
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th style="width: 60px;">章数</th>
            <th style="width: 60px;">小节数</th>
            <th></th>
            <th>名称</th>
            <th>状态</th>
            <th></th>
            <th>导出状态</th>
            <th>分类</th>
            <th>作者</th>
            <th>类型</th>
            <th>测试</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr data-id="{{$item->id}}">
                <td>{{$item->id}}</td>
                <td><a href="{{url("chapter?book_id=$item->id")}}">{{$item->chapterNum}}</a></td>
                <td><a href="{{url("section?book_id=$item->id")}}">{{$item->sectionNum}}</a></td>
                <td width="100px;">
                    <a href="{{url("book/$item->id/image")}}" target="_blank">
                        <img src="{{url("book/$item->id/image")}}" alt="" style="height: 40px;">
                    </a>
                </td>
                <td style="text-align: left;"><a href="{{url("book/$item->id/edit")}}">{{$item->name}}</a></td>
                @if($item->handle)
                    <td class="done">已确认</td>
                @else
                    <td class="undone">未确认</td>
                @endif
                <td>
                    @if($item->handle)
                        <button type="button" class="btn btn-danger handle">取消确认</button>
                    @else
                        <button type="button" class="btn btn-warning handle">确认</button>
                    @endif
                </td>
                @if($item->export)
                    <td class="done">已导出</td>
                @else
                    <td class="undone">
                        未导出
                        <button type="button" class="btn btn-warning test">测试</button>
                    </td>
                @endif
                <td>{{$item->catalog}}</td>
                <td>{{$item->author}}</td>
                <td>{{$item->typeName}}</td>
                @if($item->test === TRUE)
                    <td></td>
                @else
                    <td class="unTest">{{$item->test}}</td>
                @endif
                <td>
                    <button type="button" class="btn btn-primary delete">删除</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
@section('js')
    <script>
        $(function () {
            //删除书籍
            $('.delete').on('click', function () {
                var $this = $(this);
                var id = $this.parent().parent().data('id');
                $this.button('loading');

                $.ajax({
                    url: 'book/' + id,
                    type: 'delete',
                    success: function () {
                        location.reload()
                    }
                });
            });
            //确认书籍已处理完毕
            $('.handle').on('click', function () {
                var $this = $(this);
                var id = $this.parent().parent().data('id');
                $this.button('loading');

                $.ajax({
                    url: 'book/' + id + '/handle',
                    type: 'get',
                    success: function () {
                        location.reload()
                    }
                });
            });
            //确认书籍是否可以导出
            $('.test').on('click', function () {
                var $this = $(this);
                var id = $this.parent().parent().data('id');
                $this.button('loading');

                $.ajax({
                    url: 'book/' + id + '/test',
                    type: 'get',
                    success: function (ret) {
                        if (!ret.status) {
                            alert(ret.message)
                        }
                        $this.button('reset')
                    }
                });
            });
            //选择分类
            $('#catalog').on('change', function () {
                $('form').submit();
            })
            //查询名称
            $('#name').on('blur', function () {
                $('form').submit();
            })
        })
    </script>
@endsection