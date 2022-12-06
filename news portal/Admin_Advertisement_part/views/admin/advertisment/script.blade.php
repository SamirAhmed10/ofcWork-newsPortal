<script>
    //submit form
    $(document).ready(function(){
        //$('#category_div').hide();
         $('#page_name').on('change',function() {
           var id = $(this).val();
           var url = {!! json_encode(url('/admin/getADvertisementBySection')) !!} ;
           var csrf = "{{ csrf_token() }}";

            if (id == 0) {
                $("#position").empty();
                $("#position").append('<option value="">Select any</option>');
            }
            else {
                $.ajax({
                    type: 'post',
                    url: url,
                    data: {id: id , _token: csrf},
                    dataType: 'json',
                    success: function (data){

                        $("#position").empty();
                        $("#position").append('<option value="">Select any</option>');
                        $.each(data, function(index,val){
                            // console.log(val.scetion);
                            $('#position').append("<option value='"+val.id+"'>"+val.name+"</option>");
                        });

                    },
                    error:  function (data){
                    }
                });
            }
        });

        //section wise advertisment
        $('#type').on('change',function() {
           var type = $(this).val();
           if(type == "Image"){
                $('#image').show();
                $('#script').hide();
           }else{
                $('#image').hide();
                $('#script').show();
           }

        });
        $('#position').on('change',function() {
            var type          =$('#type').val();
            if(type == "Image"){
                var position      = $(this).find('option:selected').text();
                var positionArr   = position.split(/(.{1,10})\s/).filter(Boolean);
                var position_text = positionArr.slice(-1);

                var  text = "(The image size is" + " " + position_text.toString().replace(/\(|\)/g, '') +")";
                $('.img_text').text(text);
            }
        });

        //select2
        $('.select2').select2({});

        $("#page_name").change(function(){
            var news = $(this).val();
            $("#category_id").empty();
            if(news == 3){
                $("#category_id").attr("readonly", false);
                $('#category_id').attr("style", "pointer-events: auto;");
                var data = {!! json_encode($categories) !!};
                $("#category_id").append('<option value="">All</option>');
                $.each(data, function(index,val){
                    $('#category_id').append("<option value='"+val.id+"'>"+val.name_with_parents+"</option>");
                });
            }else{
                $("#category_id").append('<option value="0">Not Applicable</option>');
                $("#category_id").attr("readonly", 'readonly');
                $('#category_id').attr("style", "pointer-events: none;");
            }

        });
    })
</script>
