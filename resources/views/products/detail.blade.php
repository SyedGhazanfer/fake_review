@extends('layout.main')
@section('content')
    
<div class="container mt-4">
    <div class="row align-items-center">
        <div class="col-md-3">
            <img class="card-img-top" width="50%" loading="lazy" src="https://www.kwalitywalls.in/content/dam/unilever/heart/global/kw_in/double_chocolate_thumbnails_product-1862840-png.png.ulenscale.300x300.png" alt="image">
        </div>
        <div class="col-md-9">
            <div class="card-body">
                <h3 class="card-title text-black text-decoration-none">{{$item->name}}</h3>
                <p class="card-text">Price: {{$item->price}} <br> Quantity: {{$item->quantity}}</p>
            </div>
        </div>
    </div>


    <div class="row">
        @foreach ($reviews as $review)
           <div class="card mt-3 p-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-8 fw-bold">{{$review->user->name}}</div>
                    @if (auth()->user()->role_id == 2 && $review->is_suspicious == 1)
                        <div class="col-4 text-end">
                            <span class="badge bg-danger">Suspecious Review</span>
                        </div>
                    @endif
                </div>
                
            </div>
            <div class="card-body">  
                <p class="card-text">{{$review->review}}</p>
            </div>   
           </div>
        @endforeach
    </div>



    @if (auth()->user()->role_id ==1)
    <div class="row mt-4">
        <div class="col-12">
            <form id="review_form">
                <div id="review_form_error"></div>
                <input type="hidden" name="product_id" value="{{$item->id}}">
                <div class="mb-3">
                    <label for="" class="form-label">Reviews</label>
                    <textarea rows="5" class="form-control" name="review" id="" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-info">Give Review</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>


<script>

    $(document).ready(function () { 
        $('#review_form').on('submit', (e)=>{
            e.preventDefault();
            let reviewFieldForSantization = $('#review_form').serializeArray();
            let review = [];
            reviewFieldForSantization.forEach(element=>{review[element.name] = element.value})

            console.log(review);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{url('api/review/create')}}",   
                data: Object.assign({}, review),
                dataType: 'json',
                error:(err)=>{
                    let html = `<div id="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Oops! </strong> ${err.responseJSON.message}
                                </div>`

                    $('#review_form_error').append(html)
                    setTimeout(()=>{
                        $('#error').fadeOut()
                    }, 1000)


                },
                success: function (response) {
                    console.log(response);
                    let html = `<div id="error" class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Success! </strong> ${response.message}
                                </div>`

                    $('#review_form_error').append(html)
                    setTimeout(()=>{
                        $('#error').fadeOut()
                        location.reload();
                    }, 1000)
                }
            });
        })
    });
    
    
    </script>

@endsection