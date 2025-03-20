<div>

    <div id="printableDiv" class="p-6 bg-white shadow-md border border-gray-300 mx-auto max-w-3xl">
        <div class="text-center">
            <img src="{{ asset('images/sksulogo.png') }}" class="w-24 mx-auto mb-4" alt="University Logo">
            <p class="text-lg font-bold uppercase">Republic of the Philippines</p>
            <p class="text-lg font-bold">SULTAN KUDARAT STATE UNIVERSITY</p>
            <p class="text-sm">EJC Montilla, City of Tacurong, 9800</p>
            <p class="text-sm mb-4">Province of Sultan Kudarat</p>
        </div>

        <h2 class="text-center text-xl font-bold underline mb-6">Endorsement for FD</h2>

        <p><strong>Date:</strong> [Date]</p>
        <p><strong>To:</strong> [NAME]</p>

        <p class="mt-4">This is in relation to the following cash advance issued to [NAME]...</p>

        <button onclick="printDiv('printableDiv')" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">
            Print Document
        </button>

        <script>
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>
    </div>

</div>
