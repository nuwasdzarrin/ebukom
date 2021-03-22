@push('head')
    <link rel='stylesheet' href='<?php echo asset("assets/vendor/select2/dist/css/select2.min.css")?>'/>
    <style type="text/css">
        .select2-container .select2-selection--single {
            height: 30px
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #3c8dbc !important;
            border-color: #367fa9 !important;
            color: #fff !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff !important;
        }

        .custom-select:disabled{
            color: #212529;
            height: 30px;
            padding-left: 5px;
            background: url('') right 0.75rem center no-repeat;
            background-color: #e9ecef;
        }
    </style>
@endpush
@push('bottom')
    <script src='<?php echo asset("assets/vendor/select2/dist/js/select2.full.min.js")?>'></script>
@endpush
