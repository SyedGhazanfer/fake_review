@extends('layout.main')
@section('content')
    
<div class="container">
    <div class="row">
        @foreach ($products as $item)
            <div class="col-md-3 mt-4">
                <a class="text-decoration-none text-black" href="{{url('products/'.$item->id)}}">
                    <div class="card">
                        <img class="card-img-top" width="50%" loading="lazy" src="https://www.kwalitywalls.in/content/dam/unilever/heart/global/kw_in/double_chocolate_thumbnails_product-1862840-png.png.ulenscale.300x300.png" alt="image">
                        <div class="card-body">
                            <h4 class="card-title text-black text-decoration-none">{{$item->name}}</h4>
                            <p class="card-text"><strong>{{$item->price}} - Quantity: {{$item->quantity}} </strong> </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

@endsection