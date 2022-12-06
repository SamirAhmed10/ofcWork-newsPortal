@extends('admin.master')
@section('title')
    manage | advertisment
@endsection
<style>
    .card_bg{
        color: #333 !important;
        background-color: #f5f5f5 !important;
        border-color: #ddd !important;
    }
    .action-button{
        margin-top: 1.5rem!important;
        text-align: right;
    }

</style>
@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mt-4">Advertisment</h3>
            </div>
            <div class="col-md-6  action-button">
                <a class="btn btn-default btn-circle" title="New Employer" href="{{route('advertisements.create')}}"><i class="fa fa-plus"></i></a>
                <a class="btn btn-default btn-circle" title="All category" href="{{route('advertisements.index')}}"><i class="fa fa-list"></i></a>
            </div>
        </div>
        </div>
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-header card_bg">
                  Manage Advertisment
                </div>
            <div>
            <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>SN#</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Sort Order</th>
                    <th width="10%">Action</th>
                  </tr>
                  </thead>
                    <tbody>
                        @foreach ($adds as $key => $add)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$add->name ?? ''}}</td>
                            <td>{{$add->position_name->name ?? ''}}</td>
                            <td>
                                @if ($add->status == 'Active')
                                <span class="badge badge-info mr-1">
                                    Active
                                </span>
                                @else
                                <span class="badge badge-danger mr-1">
                                    InActive
                                </span>
                                @endif

                            </td>
                            <td>{{$add->sort_order ?? ''}}</td>
                            <td>
                                <a href="{{route('advertisements.edit',$add->id)}}" class="btn btn-sm btn-primary"><span class="fa fa-edit"></span></a>
                                <a href="javascript:;" class="btn btn-sm btn-danger sa-delete" data-form-id="advertisements-delete-{{$add->id}}">
                                    <span class="fa fa-trash"></span>
                                </a>
                                <form id="advertisements-delete-{{$add->id}}" action="{{ route('advertisements.destroy', $add->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<script>
     CKEDITOR.replace( 'footer_text' );
     CKEDITOR.replace( 'footer_mtext' );

    //  submit form
    $(document).ready(function(){
        $('form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "{{route('sites.store')}}",
                data:{
                    footer_text  : CKEDITOR.instances.editor.getData(),
                    footer_mtext : CKEDITOR.instances.editor1.getData(),
                    notification_email : $('#notification_email').val(),
                    email_alert  : $('#Settings_email_alert').val(),
                    sms_alert    : $('#Settings_sms_alert').val(),
                    id           : $('#id').val(),
                    '_token'     : '{{csrf_token()}}',
                },
                dataType: "json",
                success: function(data) {
                    //validation condition
                    if (data.errors) {
                        if (data.errors.footer_text) {
                            $('#footer_text_error').html(data.errors.footer_text[0]);
                        }
                        if (data.errors.footer_mtext) {
                            $('#footer_mtext_error').html(data.errors.footer_mtext[0]);
                        }
                        if (data.errors.notification_email) {
                            $('#notification_email_error').html(data.errors.notification_email[0]);
                        }
                    }
                    //Success condition
                    if (data.success) {
                        console.log(data);
                        //$('#form').trigger("reset");
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        })
                       // location.reload();
                    }
                },
                error: function(error) {
                    console.log('error');
                }
            });
        });
    })
</script>
@endSection
