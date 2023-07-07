<nav class="navbar navbar-expand-md navbar-info bg-light">
      <div class="container">
        <a class="navbar-brand" href="/">Fake Review Identification System</a>
        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="/" aria-current="page">Home <span class="visually-hidden">(current)</span></a>
                </li>
             
                @if (auth()->user())
              @if (auth()->user()->role_id == 2)
              <li class="nav-item">
                <a class="nav-link btn-outline-success btn ms-2" href="{{url('/product/create')}}"> + Create Product</a>
            </li>
              @endif


                <li class="nav-item">
                    <a class="nav-link btn-outline-danger btn ms-2" href="{{url('logout')}}"> Logout</a>
                </li>
                @else

                <li class="nav-item">
                    <a class="nav-link btn-outline-success btn ms-2" href="{{route('login')}}"> Login</a>
                </li>

                @endif

            </ul>
          
        </div>
  </div>
</nav>
