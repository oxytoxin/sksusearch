<div x-data x-cloak>
    <h2 class="mb-4 font-light capitalize text-primary-600">Liquidation Reports / Create Liquidation Report</h2>
    <div class="flex flex-col gap-4 p-4 bg-white rounded">
        <div>
            {{ $this->form }}
        </div>
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
