<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h4 class="page-title">{{ end($data)['name'] }}</h4>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-right">
                @foreach($data as $key => $value)
                    @if($key != count($data) - 1)
                        <li class="breadcrumb-item">
                            <a href="{{ $value['route'] }}">{{ $value['name'] }}</a>
                        </li>
                    @else
                        <li class="breadcrumb-item active">{{ $value['name'] }}</li>
                    @endif
                @endforeach
            </ol>
        </div>
    </div>
</div>