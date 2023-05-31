<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>{{$title}}</h2>
            </div>
            <div class="card-body">

                <div class="card-header">
                    <form method="POST"  id="forrm">
                        @csrf
                        <div class="card">
                            <!-- start first card body-->
                            <div class="card-body">
                               <div class="row w-75 mx-auto">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" required name="title" id="title"  class="form-control" placeholder="Enter Title" aria-label="Title" aria-describedby="basic-addon2">
                                       <small id="title-p" class="text-danger "></small>
                                    </div>
                                 </div>

                                 <div class="col-lg-6">
                                    <div class="form-group">
                                        <input type="text" required name="link" id="link"  class="form-control" placeholder="Enter URL" aria-label="Url" aria-describedby="basic-addon2">
                                       <small id="link-p" class="text-danger d-none" ></small>
                                    </div>
                                 </div>
                                 <div class="row w-75 mx-auto">
                                    <div class="col-12 text-center px-0  mb-3">
                                       <button id="sub" class="btn btn-primary mt-2" type="button"><i class="mdi mdi-content-save-edit mr-1"></i>{{ __('Submit') }}</button>
                                    </div>
                                 </div>
                               </div>
                            </div>
                        </div>


                    </form>
                  </div>

                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table" id="tableUser">
                            <thead class="font-weight-bold text-center">
                                <tr>
                                    <th>Title</th>
                                    <th>URL</th>
                                    <th>Short Url</th>
                                    <th>Created date</th>
                                    <th style="width:90px;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>


$(document).ready(function () {


$("table").on("click", ".clipboard", function () {
   let butid = $(this).attr('id');
   let dataAttr = $(this).attr("data-link")

	var temp = $("<input>");
  $("body").append(temp);
 temp.val(dataAttr).select();
  document.execCommand("copy");
  temp.remove();

});
    $("#sub").click(function (e) {
        e.preventDefault();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: "{{ route('generate.shorten.link.post') }}",
            type: 'POST',
            data: $('#forrm').serialize(),
            success: function (data) {

                if ($.isEmptyObject(data.error)) {
                        swal_success();
                        table.draw();

                    }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (typeof (jqXHR.responseJSON.errors['link']) != "undefined" && jqXHR.responseJSON.errors['link'] != null) {
                    $('#link-p').removeClass('d-none').text(jqXHR.responseJSON.errors['link']);
                } else {
                    $('#link-p').addClass('d-none').text('');
                }

                if (typeof (jqXHR.responseJSON.errors['title']) != "undefined" && jqXHR.responseJSON.errors['title'] != null) {
                    $('#title-p').removeClass('d-none').text(jqXHR.responseJSON.errors['title']);
                } else {
                    $('#title-p').addClass('d-none').text('');
                }

            }



        });
    })

        // success alert
        function swal_success() {
            Swal.fire({
                position: 'centered',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1000
            })
        }
        // error alert
        function swal_error() {
            Swal.fire({
                position: 'centered',
                icon: 'error',
                title: 'Something goes wrong !',
                showConfirmButton: true,
            })
        }
        // table serverside
        var table = $('#tableUser').DataTable({
            processing: false,
            serverSide: true,
            ordering: false,
            dom: 'Bfrtip',
            buttons: [],
            ajax: "{{ route('list') }}",
            columns: [{
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'link',
                    name: 'link'
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
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



    });

</script>
@endpush
