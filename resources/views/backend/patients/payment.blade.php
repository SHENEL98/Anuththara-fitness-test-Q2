<div class="statbox widget box box-shadow">
    <div class="row">
        <div class="form-group col-md-4">
            <label for="name">Enter Payment ID in here :</label>
            <input  type="text" class="form-control" onkeyup="showHint(this.value)"  id="payment" name="payment" >
        </div>
    </div>
</div>

<div id="form1" class="widget-content widget-content-area">
    <form id="user_form" role="form" method="post" enctype="multipart/form-data" action="/addPatient" >
  
        {{ csrf_field() }}
        <div class="row">
            <div class="form-group col-md-6">
                <label for="name">Bill Number</label>
                <input type="text" class="form-control"  id="pid" name="pid" readonly >
               
            </div>
            <div class="form-group col-md-6">
                <label for="name">Patient's Name</label>
                <input type="text" class="form-control"  id="name" name="name" readonly >
               
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="bdate">Date of Brith</label>
                <input type="date" class="form-control" readonly id="birthday" name="birthday">
              
            </div>
            <div class="form-group col-md-3">
                <label for="name">Patient's Contact No</label>
                <input type="text" class="form-control"  id="contact_no" name="contact_no"  readonly>
                
            </div>
            <div class="form-group col-md-3">
                <label for="name">Payment </label>
                <input type="text" class="form-control"  id="paymentz" name="paymentz" readonly>
            </div>
            <div class="form-group col-md-3">
                <label for="name">Published On </label>
                <input type="text" class="form-control"  id="created_at" name="created_at" readonly>
            </div>
        </div>

        <div class="row"> 
            <div class="custom-file-container col-12 col-lg-6" data-upload-id="mainImg">
                <label> Patient's Photo</label>
                <div class="custom-file-container__image-preview" id="p_photo">

                </div>
            </div>
       
            <div class="form-group col-md-6">
                <label for="name">Prescriptions </label>
                <input type="text" class="form-control"  id="p_note" name="p_note" readonly>
            </div>
           
        </div>
        <div class="row">
            <button type="button" onclick="HideFormData()" class="btn btn-info btn-blck mt-5">Clear</button>
        </div>
        <hr />
        <hr />
 
    </form>
</div>

<script>
    $(document).ready(function () {
        // form one is hidden here
        $('#form1').hide(); 
    });
</script>
<script>
    function showHint(str) {
       var x = document.getElementById("payment");
       var value = x.value;
       console.log(value);
       
       $.ajax({
            url : '/getPaymentBill',
            method : "post",
            data : { val :value, _token:"{{csrf_token()}}"},
            dataType : "json",
            success : function (r) {
                
                console.log(r); 
                    $('#pid').val(r.data[0].id);
                    $('#name').val(r.data[0].name);
                    $('#birthday').val(r.data[0].birthday);
                    $('#contact_no').val(r.data[0].contact_no); 
                    $('#nic').val(r.data[0].nic);
                    $('#paymentz').val(r.data[0].payment);
                    $('#p_note').val(r.data[0].p_note);
                    $('#created_at').val(r.data[0].created_at); 

                    let image_path = 'assets/backend/main/patients/' + r.data[0].photo;

                    value += '<img style="display: block;" src="' + image_path + '" width="200px" height="250px" >';

              viewPDetails();
            $('#payment').val('');

              $('#p_photo').html(value);
               
            }
        });
    }

    function viewPDetails()
    { 
        $('#form1').show();
    }
    // this is for when form is hide btn click for show form
    function HideFormData() {
        $('#form1').hide();
        $('#payment').val('');

       
    }
</script>