<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 my-10 p-8">
        <div class="flex justify-between items-start mb-8">
            <div>
                <img src="/proweaver_ph_careers_logo-removebg-preview.png" alt="Company Logo" class="h-12">
                <p class="text-gray-600 mt-2">
                    Proweaver<br>
                    Cebu City, 6000<br>
                    Philippines
                </p>
            </div>
            <div class="text-right">
                <h1 class="text-3xl font-bold text-gray-800">RECEIPT</h1>
                <p class="text-gray-500">Invoice #: {{ $invoice->id }}</p>
                <p class="text-gray-500">Issued: {{ $invoice->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-10">
            <div>
                <h3 class="font-bold text-gray-800 mb-2">Billed To:</h3>
                <p class="text-gray-700">{{ $invoice->tenant->name }}</p>
                <p class="text-gray-700">{{ $invoice->tenant->email }}</p>
                <p class="text-gray-700">{{ $invoice->tenant->phone }}</p>
            </div>
            <div class="text-right">
                <h3 class="font-bold text-gray-800 mb-2">For Room:</h3>
                <p class="text-gray-700">Room #{{ $invoice->room->room_number ?? 'N/A' }}</p>
                <p class="text-gray-700">{{ $invoice->room->type ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="overflow-x-auto mb-8 relative">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 text-sm border-b-2 border-gray-200">
                        <th class="px-4 py-3 font-medium">Description</th>
                        <th class="px-4 py-3 font-medium text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($invoice->items as $item)
                        <tr class="text-gray-700 border-b border-gray-100">
                            <td class="px-4 py-3">{{ $item->description }}</td>
                            <td class="px-4 py-3 text-right">₱{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="text-gray-800 font-bold">
                        <td class="px-4 py-3 text-right">Total Amount</td>
                        <td class="px-4 py-3 text-right">₱{{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                    <tr class="text-gray-600">
                        <td class="px-4 py-3 text-right">Amount Paid</td>
                        <td class="px-4 py-3 text-right text-green-600">- ₱{{ number_format($invoice->amount_paid, 2) }}</td>
                    </tr>
                    <tr class="text-gray-900 font-extrabold text-lg">
                        <td class="px-4 py-3 text-right">Balance Due</td>
                        <td class="px-4 py-3 text-right">₱{{ number_format($invoice->total_amount - $invoice->amount_paid, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
             @if($invoice->status == 'paid')
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-9xl font-extrabold text-green-500 opacity-20 transform -rotate-15">PAID</span>
                </div>
            @endif
        </div>

        <div class="text-center mt-12 text-gray-500 text-sm">
            Thank you for your business!
        </div>
    </div>

    <div class="text-center my-6 no-print">
         <button onclick="window.print()" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-600">
            Print Receipt
        </button>
        <button onclick="window.close()" class="bg-gray-500 text-white font-bold py-2 px-4 rounded hover:bg-gray-600">
            Close
        </button>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>