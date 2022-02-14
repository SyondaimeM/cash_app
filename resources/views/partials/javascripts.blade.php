<script>
    window.deleteButtonTrans = '{{ trans('global.app_delete_selected') }}';
    window.copyButtonTrans = '{{ trans('global.app_copy') }}';
    window.csvButtonTrans = '{{ trans('global.app_csv') }}';
    window.excelButtonTrans = '{{ trans('global.app_excel') }}';
    window.pdfButtonTrans = '{{ trans('global.app_pdf') }}';
    window.printButtonTrans = '{{ trans('global.app_print') }}';
    window.colvisButtonTrans = '{{ trans('global.app_colvis') }}';
</script>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script src="{{ url('adminlte/js') }}/bootstrap.min.js"></script>
<script src="{{ url('adminlte/js') }}/select2.full.min.js"></script>
<script src="{{ url('adminlte/js') }}/main.js"></script>

<script src="{{ url('adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/fastclick/fastclick.js') }}"></script>
<script src="{{ url('adminlte/js/app.min.js') }}"></script>
<script>
    window._token = '{{ csrf_token() }}';
</script>
<script>
    $.extend(true, $.fn.dataTable.defaults, {
        "language": {
            "url": "http://cdn.datatables.net/plug-ins/1.10.16/i18n/English.json"
        }
    });
</script>




<script>
    $('.clickable').on('click', function(e) {
        name = $(this).data('target');
        $.ajax({
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            url: "/admin/fetchTransaction",
            type: "JSON",
            method: "post",
            data: {
                'name_of_sender': $(this).data("target")
            },
            success: function(data) {
                // console.log(data);
                var table = '';
                data.result.forEach(element => {
                    table += '<tr>';
                    table += '<td>' + element.date + '</td>';
                    table += '<td>$' + element.net_amount + '</td>';
                    table += '<td>' + element.status + '</td>';
                    table += '</tr>';
                });
                $('#transactionModalBoday').html(table);
                $('#nameModal').html(name);
                $('#transaction').modal('toggle');
            },
            error: function(data) {
                console.log("error: " + JSON.stringify(data));
            },
        });
    });
</script>
@yield('javascript')
