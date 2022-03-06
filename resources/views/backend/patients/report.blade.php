    <h3 align="center">REPORT</h3><br />
    <form action="pdf_report">
        <div class="row">
            <div class="col-md-3">
                <select id="pid" class="form-control">
                    <option value="null"> Select Patient Name - NIC</option>
                    @foreach($get_prescriptions as $get_prescription)
                        <option value="{{ $get_prescription->id }}">{{ $get_prescription->name }} -
                            {{ $get_prescription->nic }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <div class="input-group input-daterange">
                    <input type="date" name="from_date" id="from_date" readonly class="form-control" />
                    <P></P>
                    <div class="input-group-addon">
                        <h4>&nbsp;&nbsp; To &nbsp;&nbsp; </h4>
                    </div>
                    <P></P>
                    <input type="date" name="to_date" id="to_date" readonly class="form-control" />
                </div>
            </div>
            <div class="col-md-3">
                <button type="button" name="filter" id="filter" class="btn btn-info btn-md">Filter</button>&nbsp;&nbsp;
                <button type="button" name="refresh" id="refresh" class="btn btn-warning btn-md">Refresh</button> 
                <button type="submit" class="btn btn-success btn-xl">Genereate PDF</button>
            </div>
        </div>
    </form>


    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Date of Brith</th>
                        <th>Contact No</th>
                        <th>NIC</th>
                        <th>Prescriptions</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            {{ csrf_field() }}
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function () {

            var date = new Date();

            $('.input-daterange').datepicker({
                todayBtn: 'linked',
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            var _token = $('input[name="_token"]').val();

            fetch_report();

            function fetch_report(from_date = '', to_date = '') {
                $.ajax({
                    url: "{{ route('daterange.fetch_report') }}",
                    method: "POST",
                    data: {
                        pid: $('#pid').val(),
                        from_date: from_date,
                        to_date: to_date,
                        _token: _token
                    },
                    dataType: "json",
                    success: function (data) {
                        var output = '';
                        let totalprice = 0;

                        let lcount = 1;

                        $('#total_records').text(data.length);
                        for (var count = 0; count < data.length; count++) {
                            output += '<tr>';

                            output += '<td>' + data[count].created_at + '</td>';
                            output += '<td>' + data[count].name + '</td>';
                            output += '<td>' + data[count].birthday + '</td>';
                            output += '<td>' + data[count].contact_no + '</td>';
                            output += '<td>' + data[count].nic + '</td>';
                            output += '<td>' + data[count].p_note + '</td>';
                            output += '<td>' + data[count].payment + '</td></tr>';
 
                            lcount++;

                        }
 
                        $('tbody').html(output);
                    }
                })
            }

            $('#filter').click(function () {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (from_date != '' && to_date != '') {
                    fetch_report(from_date, to_date);
                } else {
                    alert('Both Date is required');
                }
            });

            $('#refresh').click(function () {
                $('#from_date').val('');
                $('#to_date').val('');
                fetch_report();

            });

        });

    </script>
