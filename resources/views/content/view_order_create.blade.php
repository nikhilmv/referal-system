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
                    <div class="col-lg-6 @if ($errors->has('customer_name')) validation-failed @endif">
                        <div class="form-group">
                            <label for="validationCustom01">{{ __('Customer name') }}
                            <span class="mandatory">*</span>
                            </label>
                            <input autocomplete="off" type="text" value="{{old('customer_name')}}" name="customer_name" class="form-control" id="validationCustom01" placeholder="Customer name" required>
                            <div class="invalid-feedback" >
                                @if ($errors->has('customer_name'))
                                {{ $errors->first('customer_name') }}
                                @endif
                            </div>
                            <small id="customer_name-p" class="text-danger d-none"></small>
                        </div>
                    </div>


                    <div class="col-lg-6 @if ($errors->has('phone')) validation-failed @endif">
                        <div class="form-group">
                            <label for="validationCustom02">{{ __('Phone number') }}
                            <span class="mandatory">*</span>
                            </label>
                            <input autocomplete="off" type="text" value="{{old('phone')}}" name="phone_no" class="form-control" id="validationCustom02" placeholder="Phone number" required>
                            <div class="invalid-feedback" >
                                @if ($errors->has('phone'))
                                {{ $errors->first('phone') }}
                                @endif
                            </div>
                            <small id="phone-p" class="text-danger d-none"></small>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="validationCustom03">{{ __('Product') }}
                                <span class="mandatory">*</span>
                            </label>
                            <select name="product_id[1]" class="form-control" id="validationCustom03">
                                <option value=""> Select Product </option>
                                @foreach ($products as $Product)
                                <option value="{{$Product->id}}"  >{{$Product->product_name}}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback ">
                                @if ($errors->has('product'))
                                {{ $errors->first('product') }}
                                @endif
                            </div>
                            <small id="product-p" class="text-danger d-none"></small>

                        </div>
                    </div>

                    <div class="col-lg-6 @if ($errors->has('quantity')) validation-failed @endif">
                        <div class="form-group">
                            <label for="validationCustom04">{{ __('Quantity') }}
                            <span class="mandatory">*</span>
                            </label>
                            <input autocomplete="off" type="text" value="{{old('quantity')}}" name="quantity[1]" class="form-control" id="validationCustom04" placeholder="Quantity" required>
                            <div class="invalid-feedback" >
                                @if ($errors->has('quantity'))
                                {{ $errors->first('quantity') }}
                                @endif
                            </div>
                            <small id="quantity-p" class="text-danger d-none"></small>
                        </div>
                    </div>

                



            <!--
                    <div class="card"> -->

                    <h4 class="form-group-tiltle mb-2"></h4>
                           
                                <div class="col-12" id="more-course">
                                    <div id="TextBoxContainer">
                                        <!--Textboxes will be added here -->
                                    </div>
                                </div>

                                <div class="col-12 text-right">
                                        <button type="button"
                                            class="btn btn-primary  mb-1 waves-effect waves-light" id="add-courses"><i
                                                class="fa fa-plus"></i>{{ __('Add') }} </button>
                                </div>
                           

                    <!-- </div> -->

                            


                <div class="row w-75 mx-auto">
                    <div class="col-12 text-center px-0  mb-3">
                      <button onclick="location.href='{{ route('order.list') }}'" class="btn btn-secondary mt-2" type="button">{{ __('Cancel') }}</button>
                      <button id="submit-p" class="btn btn-primary mt-2" type="button"><i class="mdi mdi-content-save-edit mr-1"></i>{{ __('Add order') }}</button>
                    </div>
                 </div>

                 </div>
            </div> 

         </form>

        </div>
    </div>
</div>



@push('scripts')


<script>
    $(".invoiceDownload").click(function (e) {
        e.preventDefault();
        alert("test");
    });

    var labtestparameter                     = {!! json_encode($products) !!};

    var intTextBox = 1;
    $(function () {
        $("#add-courses").bind("click", function () {
            intTextBox++;
            var div = $("<div />");
            div.html(GetDynamicTextBox(""));
            $("#TextBoxContainer").append(div);


        });

        $("body").on("click", ".remove", function () { // to remove
            $(this).parent().parent().remove();
        });

    });


    function GetDynamicTextBox() {
        var html = "<option  value=''>Select Product</option>";
        labtestparameter.forEach(function(value, index) {

           html = html + '<option  value='+value.id+'>'+value.product_name+'</option>';
       });
       return '<div class="row">\
    <div class="col-lg-6">\
        <label for="validationCustom01">Product  <span class="mandatory">*</span></label>\
        <select name="product_id['+intTextBox+']" data-key="'+intTextBox+'" id="product_id-'+intTextBox+'" class="form-control select_course product_id" required>'+html+'</select>\
        <div class="invalid-feedback" >@if ($errors->has("product_id['+intTextBox+']")){{ $errors->first("product_id['+intTextBox+']") }}@else {{ __("Product cannot be empty.")}}@endif</div>\
    </div>\
    <div class="col-lg-6">\
        <label for="validationCustom01">Quantity <span class="mandatory">*</span></label>\
        <input type="number" maxlength="100" name="quantity['+intTextBox+']" class="form-control quantity" id="quantity-'+intTextBox+'" placeholder="Quantity" required>\
        <div class="invalid-feedback" >@if ($errors->has("quantity['+intTextBox+']")){{ $errors->first("quantity['+intTextBox+']") }}@else {{ __("Quantity cannot be empty.")}}@endif</div>\
    </div>\
    <div class="col-lg-1 ">\
        <button type="button" id="remove_'+intTextBox+'" class="btn btn-sqaure btn-danger btn-sm remove-btn-course remove" style="">-</button>\
    </div>\
</div>';




    }

    
</script>
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

            url: "{{ route('order.store') }}",
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



                if(typeof(request.responseJSON.errors.customer_name) != "undefined" && request.responseJSON.errors.customer_name !== null) {
                        $('#customer_name-p').removeClass('d-none').text(request.responseJSON.errors.customer_name[0]);
                        $('#validationCustom01').removeClass('is-valid').addClass('is-invalid');
                     }else{
                        $('#customer_name-p').addClass('d-none').text('');
                        $('#validationCustom01').removeClass('is-invalid').addClass('is-valid');
                     }

                     if(typeof(request.responseJSON.errors.phone_no) != "undefined" && request.responseJSON.errors.phone_no !== null) {
                        $('#phone-p').removeClass('d-none').text(request.responseJSON.errors.phone_no);
                        $('#validationCustom02').removeClass('is-valid').addClass('is-invalid');
                     }else{
                        $('#phone-p').addClass('d-none').text('');
                        $('#validationCustom02').removeClass('is-invalid').addClass('is-valid');
                     }

                     if(typeof(request.responseJSON.errors.product_id) != "undefined" && request.responseJSON.errors.product_id !== null) {
                        $('#product-p').removeClass('d-none').text(request.responseJSON.errors.product_id);
                        $('#validationCustom03').removeClass('is-valid').addClass('is-invalid');
                     }else{
                        $('#product-p').addClass('d-none').text('');
                        $('#validationCustom03').removeClass('is-invalid').addClass('is-valid');
                     }

                     if(typeof(request.responseJSON.errors.quantity) != "undefined" && request.responseJSON.errors.quantity !== null) {
                        $('#quantity-p').removeClass('d-none').text(request.responseJSON.errors.quantity);
                        $('#validationCustom04').removeClass('is-valid').addClass('is-invalid');
                     }else{
                        $('#quantity-p').addClass('d-none').text('');
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
