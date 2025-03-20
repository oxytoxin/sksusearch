<div>
    <div id="printableDiv" class="p-6 bg-white shadow-md border border-gray-300 mx-auto max-w-3xl">
       <x-sksu-header/>

        <h2 class="text-center text-xl font-bold underline mb-6">Formal Management Reminder</h2>

        <p><strong>No. xxxx-xxxx</strong></p>
        <p><strong>To:</strong> [NAME]</p>
        <p><strong>Re:</strong> Reminder to liquidate cash advance</p>
        <p><strong>Date:</strong> [Date]</p>

        <p class="mt-4">Pursuant to Section 1 of the Sanctions for Violations...</p>

        <p class="mt-4">Please be informed that cash advances must be liquidated...</p>

        <p class="mt-4">For your guidance and immediate compliance.</p>

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

