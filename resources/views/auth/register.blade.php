@extends('layout.main')
@section('content')
    
      
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="container mt-4 p-5 bg-light">
            <div id="user_form_error"></div>
           <form id="user_form">
            <div class="mb-3"><a href="{{route('login')}}">I have an account</a></div>
            
            <div class="mb-3 mt-3">  
                <label for="" class="form-label">Name</label>
                <input required type="text" class="form-control" name="name" placeholder="John">
              </div>

            <div class="mb-3 mt-3">  
                <label for="" class="form-label">Email</label>
                <input required type="email" class="form-control" name="email" placeholder="abc@xyz.com">
              </div>

              <div class="mb-3">
                <label for="" class="form-label">Role</label>
                <select class="form-select form-select" name="role_id" id="">

                    @foreach ($roles as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
              </div>
  
              <div class="mb-3">  
                  <label for="" class="form-label">Password</label>
                  <input required type="password" class="form-control" name="password" placeholder="Password">
                </div>
  
              <div class="mb-3 text-end">
                  <button type="submit" class="mt-3 btn w-25 btn-outline-success">Register</button>
              </div>
           </form>

        </div>
    </div>
    <div class="col-md-3"></div>
</div>

<script>

    $(document).ready(function () { 
        $('#user_form').on('submit', (e)=>{
            e.preventDefault();
            let userFieldForSantization = $('#user_form').serializeArray();
            let user = [];
            userFieldForSantization.forEach(element=>{user[element.name] = element.value})

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{url('api/register')}}",   
                data: Object.assign({}, user),
                dataType: 'json',
                error:(err)=>{
                    let html = `<div id="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    <strong>Oops! </strong> ${err.responseJSON.message}
                                </div>`

                    $('#user_form_error').append(html)
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

                    $('#user_form_error').append(html)
                    setTimeout(()=>{
                        $('#error').fadeOut()
                        location.reload()
                    }, 1000)
                }
            });
        })
    });
    
    
    </script>

@endsection