<div>
    <h3>Create Disbursement Voucher</h3>
    <div>
        {{ $this->form }}
    </div>
    <script>
        function printOut(data) {
            var mywindow = window.open('', 'Print Itinerary', 'height=1000,width=1000');
            mywindow.document.write('<html><head>');
            mywindow.document.write('<title>Print Itinerary</title>');
            mywindow.document.write(`<link rel="stylesheet" href="{{ Vite::asset('resources/css/app.css') }}" />`);
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');
            mywindow.document.close();
            mywindow.focus();
            setTimeout(() => {
                mywindow.print();
            }, 1000);
            return false;
        }
    </script>
</div>
