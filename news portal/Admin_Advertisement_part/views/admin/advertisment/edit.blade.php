@extends('admin.master')
@section('title')
    Update | Advertisment
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
    form-control:disabled, .form-control[readonly] {
    background-color: white !important;
    opacity: 1;
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
                <a class="btn btn-default btn-circle"  href="{{route('advertisements.create')}}"><i class="fa fa-plus"></i></a>
                <a class="btn btn-default btn-circle"  href="{{route('advertisements.index')}}"><i class="fa fa-list"></i></a>
            </div>
        </div>
        </div>
        <div class="col-xl-12 col-md-12">
            <div class="card">
                <div class="card-header card_bg">
                    Update Advertisment
                </div>
            <div>
            <div class="card-body">
                <form enctype="multipart/form-data" id="settings-form">
                    @csrf
                    <p class="note">Fields with <span class="required">*</span> are required.</p>
                    <div class="clearfix">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="position" class="required">Page <span class="required">*</span></label>
                                <select name="page_name" id="page_name" class="form-control select2">
                                    <option value="">Select any</option>
                                    @foreach($pages as $key => $page)
                                        <option value="{{$page->id}}" {{ ($page->id == $addvertisment->page_name) ? 'selected' : '' }}>{{$page->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    <strong id="name_error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="position" class="required">Position <span class="required">*</span></label>
                                <select name="position" id="position" class="form-control select2">
                                    <option value="">Select any</option>
                                    @foreach($positions as $key => $val)
                                        <option value="{{$val->id}}" {{ ($val->id == $addvertisment->position) ? 'selected' : '' }}>{{$val->name}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    <strong id="position_error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="required">Name <span class="required">*</span></label>
                                <input  class="form-control" name="name" value="{{$addvertisment->name}}" id="name" type="text" />
                             </div>
                            <span class="text-danger">
                                <strong id="name_error"></strong>
                            </span>
                        </div>
                    </div>
                    @if($addvertisment->page_name == 3)
                    <div class="row category_div">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="category_id" class="required">Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="0">All</option>
                                    @foreach($categories as $key => $category)
                                        <option value="{{$category->id}}" {{ ($category->id == $addvertisment->category_id) ? 'selected' : '' }}>{{$category->name_with_parents}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="type" class="required">Type <span class="required">*</span></label>
                                <select name="type" id="type" class="form-control">
                                    <option value="Image" {{($addvertisment->type == 'Image') ? 'selected' : ''}}>Image</option>
                                    <option value="Script" {{($addvertisment->type == 'Script') ? 'selected' : ''}}>Script</option>
                                </select>
                                <span class="text-danger">
                                  <strong id="type_error"></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display: none;" id="script">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="required">Script<span class="required">*</span></label>
                                <textarea name="script" id="" cols="30"  class="form-control">{{$addvertisment->script}}</textarea>
                            </div>
                            <span class="text-danger">
                                <strong id="description_error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row" id="image">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image" class="required">Image <span class="img_text text-danger"></span> <span class="required">*</span>
                                </label>   <br>
                                <input  name="image" id="image" type="file" /><br><br>
                                @if($addvertisment->image)
                                <img src="{{asset('admin/advertisment/'.$addvertisment->image)}}" height="90px" width="90px" alt="" class="img-responsive">
                                @else
                                    <img src="{{asset('admin/image/default.jpg')}}" height="90px" width="90px" alt="" class="img-responsive">
                                @endif
                            </div>
                            <span class="text-danger">
                                <strong id="image_error"></strong>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="link" class="required">Link
                            </label>
                            <input class="form-control" value="{{$addvertisment->link}}" name="link" id="link" type="text" />
                        </div>
                            <span class="text-danger">
                                <strong id="link_error"></strong>
                            </span>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="sort_order" class="required">Sort Order *</span>
                            </label>
                            <input class="form-control" value="{{$addvertisment->sort_order}}" name="sort_order" id="sort_order" type="text" />                            </div>
                            <span class="text-danger">
                                <strong id="sort_error"></strong>
                            </span>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="active" class="required">Status</label>
                                <select name="status" id="" class="form-control">
                                    <option value="Active" {{($addvertisment->status == 'Active') ? 'selected' : ''}}>Active</option>
                                    <option value="Inactive" {{($addvertisment->status == 'Inactive') ? 'selected' : ''}}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                              <label  for="start_date" class="required">Start Date</label>
                              <input name="start_date"  id="myflatpickr"  class="form-control" value="{{$addvertisment->start_date}}" >
                              <script type="text/javascript">
                                flatpickr("#myflatpickr", {});
                            </script>
                            </div>
                            <span class="text-danger">
                                <strong id="stdate_error"></strong>
                            </span>
                        </div>
                    </div>

                     <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                              <label  for="end_date" class="required">End Date</label>
                              <input name="end_date"  id="myflatpickr"  class="form-control" value="{{$addvertisment->end_date}}"  >
                              <script type="text/javascript">
                                flatpickr("#myflatpickr", {});
                             </script>
                               <span class="text-danger">
                                <strong id="edate_error"></strong>
                            </span>
                            </div>
                        </div>
                     </div>
                   <div class="row">
                       <div class="col-md-12">
                         <div class="form-group">
                            <label  for="start_time" class="required">Start Time</label>
                            <input style="width: 100% ; border: 1px solid #ced4da;"  class="flatpickr flatpickr-input active"   type="text" name="start_time" readonly="readonly" value="{{$addvertisment->start_time}}">
                        </div>
                     </div>
                   </div>
                   <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                         <label  for="start_time" class="required">End Time</label>
                         <input style="width: 100% ; border: 1px solid #ced4da;" class="flatpickr flatpickr-input active"  type="text" name="end_time" value="{{$addvertisment->end_time}}" readonly="readonly" value="{{$addvertisment->start_time}}">
                     </div>
                  </div>
                </div>


                    <div class="form-group buttons">
                        <input class="btn btn-primary" type="submit" name="yt0" value="Save" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
     <script>
    flatpickr("input[type=datetime-local]");
    $( '.flatpickr' ).flatpickr({
  	noCalendar: true,
    enableTime: true,
    dateFormat: 'h:i K'
   });

 </script>
</main>
@include("admin.pages.advertisment.script")
<script>
    //  submit form
    $(document).ready(function(){

        $('form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            formData.append('_method', 'put');
            formData.append('_token', "{{ csrf_token() }}");
            $.ajax({
                type: 'post',
                url: "{{route('advertisements.update',$addvertisment->id)}}",
                data:  formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    //validation condition
                    if (data.errors) {
                        if (data.errors.page_name) {
                            $('#page_error').html(data.errors.page_name[0]);
                        }
                        if (data.errors.position) {
                            $('#position_error').html(data.errors.position[0]);
                        }
                        if (data.errors.name) {
                            $('#name_error').html(data.errors.name[0]);
                        }
                        if (data.errors.script) {
                            $('#script_error').html(data.errors.script[0]);
                        }
                        if (data.errors.image) {
                            $('#image_error').html(data.errors.image[0]);
                        }
                        if (data.errors.start_date) {
                            $('#stdate_error').html(data.errors.start_date[0]);
                        }
                        if (data.errors.end_date) {
                            $('#edate_error').html(data.errors.end_date[0]);
                        }
                        if (data.errors.link) {
                            $('#link_error').html(data.errors.link[0]);
                        }
                        if (data.errors.metatag_keywords) {
                            $('#metatag_keywords').html(data.errors.metatag_keywords[0]);
                        }
                    }
                    //Success condition
                    if (data.success) {
                        $('#form').trigger("reset");
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: data.success,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        location.href = "{{route('advertisements.index')}}";
                    }
                    //Warning Condition
                    if (data.error) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: data.error,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        //location.href = "{{route('advertisements.index')}}";
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
