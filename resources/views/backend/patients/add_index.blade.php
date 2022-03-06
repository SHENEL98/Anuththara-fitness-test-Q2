     <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h3>Add New Patient</h3>
                </div>
            </div>
        </div> 
        <div class="widget-content widget-content-area">
            <form id="user_form" role="form" method="post" enctype="multipart/form-data" action="/addPatient" >
                {{ csrf_field() }}
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Patient's Name</label>
                        <input type="text" class="form-control"  id="name" name="name" required >
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="bdate">Date of Brith</label>
                        <input type="date" class="form-control @error('bdate') is-invalid @enderror"
                            value="{{ old('bdate') }}" required id="bdate" name="bdate">
                        @error('bdate')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="name">Patient's Contact No</label>
                        <input type="text" class="form-control"  id="contactno" name="contactno" onkeypress="isInputNumber(event)" maxlength="10" required>
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row"> 
                    <div class="custom-file-container col-12 col-lg-6" data-upload-id="mainImg">
                        <h4> Patient's Photo</h4>
                        <label>Clear Picture<a id="mainImg" href="javascript:void(0)"
                                                class="custom-file-container__image-clear"
                                                title="Clear Image"> x</a></label>
                        <label class="custom-file-container__custom-file">
                            <input type="file" id="mainImage"
                                    class="custom-file-container__custom-file__custom-file-input"
                                    accept="image/*" name="mainImg">
                            <input type="hidden" name="MAX_FILE_SIZE" value="10485760"/>
                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                        </label>
                        <div class="custom-file-container__image-preview"></div>
                        @error('mainImg')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="nic">Patient's NIC</label>
                        <input type="text" class="form-control @error('nic') is-invalid @enderror"
                            value="{{ old('nic') }}" required minlength="10" id="nic" name="nic">
                        @error('nic')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Note</label>
                        <input type="text" class="form-control"  id="note" name="note" >
                        @error('note')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror 
                    </div>
                </div>
                <hr/>
                <hr/>
                <button class="btn btn-primary mb-2 mr-2" type="submit" >Submit</button>
                <button class="btn btn-primary mb-2" type="reset" >Clear</button>
            </form>
        </div>
    </div>

    
<script>
    $(document).ready(function() {
        $('#mainImg').val(''); 
        var main = new FileUploadWithPreview('mainImg'); 
    });
</script>

<script>
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
</script>

<script>
    function addPatient()
    {
        alert("olo");
        $.ajax({
            url: "/addPatient",
            type: "post",
            data: {
                name: $('#name').val(),
                bdate: $('#bdate').val(),
                contactno: $('#contactno').val(),
                mainImg: $('#mainImg').val(),
                nic: $('#nic').val(),
                note: $('#note').val(),
            },
        
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType: 'json',
            
            error: function (error) {
                console.log(error);
                Swal.fire(
                    'ERROR',
                    '',
                    'question'
                    )
            },
            success: function (r) {

                console.log(r);
                if (r.status == 200) {
                        swal.fire({
                        title: 'Successfully Added', 
                        position: 'center',
                        icon: 'success',
                        type: 'success',
                        padding: '2em',
                        confirmButtonColor: '#282d3b',
                        confirmButtonText: 'SUCCESS',
                        onClose: function () {
                            window.location.href = '{{url('/')}}';
                        }
                            
                    }); 
                }
                else{ 
                    Swal.fire(
                        'Something Wrong',
                        '',
                        'question'
                        );
                    }
            }
            
        });
    }
</script>

 