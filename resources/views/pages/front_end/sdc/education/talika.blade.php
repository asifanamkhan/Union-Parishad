@extends('layouts.front_end_layout.master')
@section('head_content')
    শিক্ষাগত যোগ্যতা তালিকা
@endsection
@section('content')
    <div class="panel panel-default mb-5">
        <div class="panel-body" >
            <div class="row">
                <div class="col-sm-12" style="margin-bottom:10px;margin-top:10px;">
                    <div class="form-group has-feedback">
                        <label for="Delivery-type" class="col-sm-3 control-label text-right">ওয়ার্ড নং <span style="color: red">*</span></label>
                        <div class="col-sm-8 has-feedback" id="">
                            <input type="text" class="form-control" id="word_no" placeholder="ইংরেজিতে ওয়ার্ড নং প্রদান করুন">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12" style="margin-bottom:10px;margin-top:10px;">
                    <div class="form-group has-feedback">
                        <label for="Delivery-type" class="col-sm-3 control-label text-right">শিক্ষাগত যোগ্যতা  <span style="color: red">*</span></label>
                        <div class="col-sm-8 has-feedback" id="">
                            <select name="education" id="education" class="form-control col-sm-8 js-example-basic-single text-left">
                                <option value="0">চিহ্নিত করুন</option>
                                @foreach($edus as $edu)
                                    @if($edu->status==1)
                                        <option value="{{$edu->id}}">{{$edu->education}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success col-sm-offset-6" id="submit" style="background-color: darkgreen">search</button>
            </div>
        </div>

        <div class="panel-body mt-5">
            <table id="example" class="table table-striped table-bordered dt-responsive nowrap text-left mt-5" cellspacing="0" width="100%" style="color: #000102;font-weight:bolder;">
                <thead>
                <tr class="">
                    <th>ক্র.নং </th>
                    <th>নাম </th>
                    <th>পিতার নাম</th>
                    <th>মাতার নাম</th>
                    <th>গ্রাম</th>
                    <th>পেশা</th>
                    <th>জন্ম তারিখ</th>
                    <th>জাতীয় পরিচয় পত্র</th>
                    <th>মোবাইল</th>
                </tr>
                </thead>
            </table>
        </div>


    </div>
@endsection
@section('script')
    <link rel="stylesheet" href="{{asset('datatables/css/dataTables.bootstrap4.css')}}" />
    <link rel="stylesheet" href="{{asset('datatables/css/responsive.bootstrap.min.css')}}" />
    <script type="text/javascript" src="{{asset('datatables/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('datatables/js/dataTables.bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('datatables/js/dataTables.responsive.min.js')}}"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>

    <script>

        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });

        $('#submit').on('click',function () {
            $('#submit').prop('disabled',true);
            $('#example'). DataTable().destroy();
            $('#example'). DataTable( {

                "bInfo": false,
                "processing": true,
                "serverSide": true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},

                "ajax":{
                    "url": "{{ route('sdcEducationShow') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{ csrf_token() }}",
                        word_no:$('#word_no').val(),
                        education:$('#education').val(),
                    },
                    complete: function() {
                        $('#submit').prop('disabled',false);
                    },

                },

                "columns": [
                    { "data": "id" },
                    { "data": "bname" },
                    { "data": "bfname" },
                    { "data": "bmname" },
                    { "data": "b_gram" },
                    { "data": "occupation" },
                    { "data": "dob" },
                    { "data": "nid" },
                    { "data": "mob" },

                ]
            });
        })

    </script>
@endsection
