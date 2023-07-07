@extends('layout.main')
@section('content')
    
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="container mt-4 p-5 bg-light">

           <form id="product_form">
            <div id="product_form_error"></div>
            <div class="mb-3 mt-3">  
                <label for="" class="form-label">Product Name</label>
                <input required type="text" class="form-control" name="name" placeholder="Cup, Spoon, fork etc..">
              </div>
  
              <div class="mb-3">  
                  <label for="" class="form-label">Product Price</label>
                  <input required type="text" class="form-control" name="price" id="" aria-describedby="helpId" placeholder="500">
                </div>
  
  
                <div class="mb-3">  
                  <label for="" class="form-label">Product Quantity</label>
                  <input required type="number" max="100" min="1" value="1" class="form-control" name="quantity" >
              </div>
  
              <div class="mb-3 text-end">
                  <button type="submit" class="mt-3 btn w-25 btn-outline-success">Save</button>
              </div>
           </form>

        </div>
    </div>
    <div class="col-md-3"></div>
</div>


<script>

$(document).ready(function () { 
    $('#product_form').on('submit', (e)=>{
        e.preventDefault();
        let productFieldForSantization = $('#product_form').serializeArray();
        let product = [];
        productFieldForSantization.forEach(element=>{product[element.name] = element.value})
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
            type: "POST",
            url: "{{url('api/products')}}",
            data: Object.assign({}, product),
            error:(err)=>{
                    let html = `<div id="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Oops! </strong> ${err.responseJSON.message}
                                </div>`

                    $('#product_form_error').append(html)
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

                    $('#product_form_error').append(html)
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