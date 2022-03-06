<div class="statbox widget box box-shadow">
    <div class="widget-header">
        <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h3>Add Prescriptions</h3>
            </div>
        </div>
    </div>
    <div class="widget-content widget-content-area">
            {{ csrf_field() }}
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="name">Select Patient</label>
                    <select id="pid" searchable="Search here.." class="form-control" >
                        <option value="null">Please Select Patient Name - NIC</option>
                        @foreach ($get_prescriptions as $get_prescription)
                            <option value="{{$get_prescription->id}}">{{$get_prescription->name}} - {{$get_prescription->nic}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row"> 
                <div class="form-group col-md-12">
                    <label for="name">Patient's Prescriptions</label> 
                    <textarea id="pnote" name="pnote" class="form-control"  rows="4" cols="50"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="name">Payment</label>
                    <input  type="text" class="form-control" onkeypress="isInputNumber(event)" id="payment" name="payment" >
                </div>
            </div>
            <hr /> 
            <button class="btn btn-primary mb-2 mr-2" type="submit" onclick="AddPrescriptions()"  >Submit</button>
            <button class="btn btn-primary mb-2" type="reset">Clear</button>
    </div>
</div>

<script>
    $(document).ready(function(){ 
      $("#pid").select2();  
    });
</script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<div class="row layout-top-spacing"> 
    <div id="basic" class="col-lg-12 layout-spacing">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div id="btn-main" class="row">
                        <button type="button" onclick="showInsertFormData()"
                            class="btn btn-success btn-blck mt-5">View Previous Prescriptions  </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="form1" class="col-lg-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <select id="patient_id" class="form-control"  onchange="selectPatient()" >
                <option value="null">Please Select Patient Name - NIC</option>
                @foreach ($get_prescriptions as $get_prescription)
                    <option value="{{$get_prescription->id}}">{{$get_prescription->name}} - {{$get_prescription->nic}}</option>
                @endforeach
            </select>
            <div id="div_prescriptions">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){ 
      $("#patient_id").select2();  
    });
</script>


<script>
    function isInputNumber(evt) {
        var ch = String.fromCharCode(evt.which);
        if (!(/[0-9]/.test(ch))) {
            evt.preventDefault();
        }
    }
</script>


<script>
    $(document).ready(function () {
        $('#form1').hide(); 
    });
</script>

<script>
  
    function showInsertFormData() {
        
        $('#form1').show();
        $('#btn').val('INSERT');
        let btnData = '';
        btnData +=
            '<button type="button" onclick="HideFormData()" class="btn btn-info btn-blck mt-5">Hide-Form</button>';
        btnData += '<br>';
        $('#btn-main').html(btnData);
    }

    
    function HideFormData() {
        $('#form1').hide(); 
        let btnData = '';
        btnData +=
            '<button type="button" onclick="showInsertFormData()" class="btn btn-success btn-blck mt-5">View Previous Prescriptions</button>';
        $('#btn-main').html(btnData);

    } 
</script>



<script>
function AddPrescriptions()
{ 
    $.ajax({
        url: "/AddPrescriptions",
        type: "post",
        data: {
            pid: $('#pid').val(),
            pnote: $('#pnote').val(),
            payment: $('#payment').val()
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

                $('#pid').val('');
                $('#pnote').val('');
                $('#payment').val('');

                    swal.fire({
                    title: 'Successfully Added', 
                    position: 'center', 
                    icon: 'success',
                    type: 'success',
                    padding: '2em',
                    confirmButtonColor: '#282d3b',
                    confirmButtonText: 'SUCCESS',   
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

<script>
    function selectPatient()
    {
        $.ajax({
        url: "/selectPatient",
        type: "POST",
        data: {
            patient_id: $('#patient_id').val(), 
            _token: $('meta[name="csrf-token"]').attr('content')
        },
         success: function (response) {
            console.log('jjjj'+response);
            let value = '';
            let val = ''; 
            value +='<div class="statbox widget box box-shadow">';
            
            for (var patient in response.data) {
                value += '<h3>' + response.data[patient].p_note + ' on '+ response.data[patient].created_at +'</h3><br>';
            }
            value += '</div>';
             $('#div_prescriptions').html(value);
              
        },
    });
    }
</script>
