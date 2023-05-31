<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>{{$title}}</h2>

            </div>



         <form method="POST" id="formdata-p" enctype="multipart/form-data"   class="needs-validation @if(!$errors->isEmpty()) after-form-submit @endif" novalidate>

            @csrf


            <div class="card-body">
                <div class="row w-75 mx-auto">
                    <div class="col-lg-6 @if ($errors->has('product_name')) validation-failed @endif">
                        <div class="form-group">
                            <label for="validationCustom01">{{ __('Product name') }}
                            <span class="mandatory">*</span>
                            </label>
                            <input autocomplete="off" type="text" value="{{old('product_name')}}" name="product_name" class="form-control" id="validationCustom01" placeholder="Product Name" required>
                            <div class="invalid-feedback" >
                                @if ($errors->has('product_name'))
                                {{ $errors->first('product_name') }}
                                @endif
                            </div>
                            <small id="product_name-p" class="text-danger d-none"></small>
                        </div>
                    </div>

                    <div class="col-lg-6 @if ($errors->has('image')) validation-failed @endif">
                        <div class="form-group">
                            <label for="validationCustom02">{{ __('image') }}
                            <span class="mandatory">*</span>
                            </label>
                            <input type="file" name="image" id="validationCustom02" class="form-control">

                            {{-- <input type="file" class="form-control" name="image" accept="image/x-png,image/jpeg,,image/jpg" /> --}}

                            <div class="invalid-feedback ">
                                @if ($errors->has('image'))
                                {{ $errors->first('image') }}
                                @endif
                            </div>
                            <small id="image-p" class="text-danger d-none"></small>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="validationCustom03">{{ __('Category') }}
                                <span class="mandatory">*</span>
                            </label>
                            <select name="category_id" class="form-control" id="validationCustom03">
                                <option value=""> Select Category </option>
                                @foreach ($categories as $category)
                                <option value="{{$category->id}}"  >{{$category->category_name}}</option>
                                @endforeach

                            </select>



                            <div class="invalid-feedback ">
                                @if ($errors->has('category'))
                                {{ $errors->first('category') }}
                                @endif
                            </div>
                            <small id="category-p" class="text-danger d-none"></small>

                        </div>
                    </div>

                    <div class="col-lg-6 @if ($errors->has('price')) validation-failed @endif">
                        <div class="form-group">
                            <label for="validationCustom04">{{ __('Price') }}
                            <span class="mandatory">*</span>
                            </label>
                            <input autocomplete="off" type="text" value="{{old('price')}}" name="price" class="form-control" id="validationCustom04" placeholder="Price" required>
                            <div class="invalid-feedback" >
                                @if ($errors->has('price'))
                                {{ $errors->first('price') }}
                                @endif
                            </div>
                            <small id="price-p" class="text-danger d-none"></small>
                        </div>
                    </div>




                </div>

                <div class="row w-75 mx-auto">
                    <div class="col-12 text-center px-0  mb-3">
                      <button onclick="location.href='{{ route('product.list') }}'" class="btn btn-secondary mt-2" type="button">{{ __('Cancel') }}</button>
                      <button id="submit-p" class="btn btn-primary mt-2" type="button"><i class="mdi mdi-content-save-edit mr-1"></i>{{ __('Add Product') }}</button>
                    </div>
                 </div>


            </div>

         </form>

        </div>
    </div>
</div>



@push('scripts')
<script>
    $('document').ready(function () {
        // success alert
        function swal_success(title) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: title,
                showConfirmButton: false,
                timer: 1000
            })
        }
        // error alert
        function swal_error(title) {
            Swal.fire({
                position: 'centered',
                icon: 'error',
                title: title,
                showConfirmButton: true,
            })
        }
        // table serverside
        var table = $('#tableUser').DataTable({
            processing: false,
            serverSide: true,
            ordering: false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'excel', 'pdf'
            ],
            ajax: "{{ route('product.list') }}",
            columns: [{
                    data: 'product_name',
                    name: 'product_name'
                },
                {
                    data: 'category_id',
                    name: 'category_id'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // csrf token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // initialize btn add
        // $('#createNewUser').click(function () {
        //     $('#saveBtn').val("create user");
        //     $('#user_id').val('');
        //     $('#formUser').trigger("reset");
        //     $('#modal-user').modal('show');
        // });
        // initialize btn edit

        // initialize btn save



        $('body').on('click','#submit-p',function(e){
        e.preventDefault();
        var postData = new FormData($("#formdata-p")[0]);
         $.ajax({
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },

            url: "{{ route('product.store') }}",
            processData: false,
            contentType: false,
            type:'POST',
            data : postData,
            success: function(data) {
                  if($.isEmptyObject(data.error)){
                    swal_success(data.success);

                    setTimeout(() => {
                        window.location.href = 'list';
                    }, 500);
                  }else{
                    swal_error(data.success);
                  }
            }, error: function (request, status, error) {



                if(typeof(request.responseJSON.errors.product_name) != "undefined" && request.responseJSON.errors.product_name !== null) {
                        $('#product_name-p').removeClass('d-none').text(request.responseJSON.errors.product_name[0]);
                        $('#validationCustom01').removeClass('is-valid').addClass('is-invalid');
                     }else{
                        $('#product_name-p').addClass('d-none').text('');
                        $('#validationCustom01').removeClass('is-invalid').addClass('is-valid');
                     }

                     if(typeof(request.responseJSON.errors.category_id) != "undefined" && request.responseJSON.errors.category_id !== null) {
                        $('#category-p').removeClass('d-none').text(request.responseJSON.errors.category_id);
                        $('#validationCustom02').removeClass('is-valid').addClass('is-invalid');
                     }else{
                        $('#category-p').addClass('d-none').text('');
                        $('#validationCustom02').removeClass('is-invalid').addClass('is-valid');
                     }

                     if(typeof(request.responseJSON.errors.product_image) != "undefined" && request.responseJSON.errors.product_image !== null) {
                        $('#image-p').removeClass('d-none').text(request.responseJSON.errors.product_image);
                        $('#validationCustom03').removeClass('is-valid').addClass('is-invalid');
                     }else{
                        $('#image-p').addClass('d-none').text('');
                        $('#validationCustom03').removeClass('is-invalid').addClass('is-valid');
                     }

                     if(typeof(request.responseJSON.errors.price) != "undefined" && request.responseJSON.errors.price !== null) {
                        $('#price-p').removeClass('d-none').text(request.responseJSON.errors.price);
                        $('#validationCustom04').removeClass('is-valid').addClass('is-invalid');
                     }else{
                        $('#price-p').addClass('d-none').text('');
                        $('#validationCustom04').removeClass('is-invalid').addClass('is-valid');
                     }

                }
         });

   })



        // initialize btn delete
        $('body').on('click', '.deleteUser', function () {
            var user_id = $(this).data("id");

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{route('users.store')}}" + '/' + user_id,
                        success: function (data) {
                            swal_success();
                            table.draw();
                        },
                        error: function (data) {
                            swal_error();
                        }
                    });
                }
            })
        });

        // statusing


    });

</script>
@endpush
