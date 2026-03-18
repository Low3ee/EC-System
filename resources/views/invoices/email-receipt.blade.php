<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: sans-serif;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#f3f4f6">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">
                    <tr>
                        <td style="padding: 30px;">
                            
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" style="padding-bottom: 30px;">
                                        <img src="{{ config('app.url') . '/proweaver_ph_careers_logo-removebg-preview.png' }}" alt="Company Logo" height="48" style="display: block; border: 0;">
                                        <p style="color: #4b5563; font-size: 14px; margin-top: 10px; line-height: 1.5;">
                                            Proweaver<br>
                                            Cebu City, 6000<br>
                                            Philippines
                                        </p>
                                    </td>
                                    <td align="right" style="padding-bottom: 30px; vertical-align: top;">
                                        <h1 style="font-size: 24px; font-weight: bold; color: #1f2937; margin: 0;">RECEIPT</h1>
                                        <p style="color: #6b7280; font-size: 14px; margin: 5px 0;">Invoice #: {{ $invoice->id }}</p>
                                        <p style="color: #6b7280; font-size: 14px; margin: 0;">Issued: {{ $invoice->created_at->format('M d, Y') }}</p>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 30px;">
                                <tr>
                                    <td width="50%" align="left" style="vertical-align: top;">
                                        <h3 style="font-size: 14px; font-weight: bold; color: #1f2937; margin-bottom: 10px;">Billed To:</h3>
                                        <p style="color: #374151; font-size: 14px; margin: 2px 0;">{{ $invoice->tenant->name }}</p>
                                        <p style="color: #374151; font-size: 14px; margin: 2px 0;">{{ $invoice->tenant->email }}</p>
                                        <p style="color: #374151; font-size: 14px; margin: 2px 0;">{{ $invoice->tenant->phone }}</p>
                                    </td>
                                    <td width="50%" align="right" style="vertical-align: top;">
                                        <h3 style="font-size: 14px; font-weight: bold; color: #1f2937; margin-bottom: 10px;">For Room:</h3>
                                        <p style="color: #374151; font-size: 14px; margin: 2px 0;">Room #{{ $invoice->room->room_number ?? 'N/A' }}</p>
                                        <p style="color: #374151; font-size: 14px; margin: 2px 0;">{{ $invoice->room->type ?? 'N/A' }}</p>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 2px solid #e5e7eb;">
                                        <th align="left" style="padding: 12px; color: #6b7280; font-size: 12px; text-transform: uppercase;">Description</th>
                                        <th align="right" style="padding: 12px; color: #6b7280; font-size: 12px; text-transform: uppercase;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->items as $item)
                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                        <td style="padding: 12px; color: #374151; font-size: 14px;">{{ $item->description }}</td>
                                        <td align="right" style="padding: 12px; color: #374151; font-size: 14px;">₱{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td align="right" style="padding: 12px 12px 5px 12px; color: #1f2937; font-weight: bold; font-size: 14px;">Total Amount</td>
                                        <td align="right" style="padding: 12px 12px 5px 12px; color: #1f2937; font-weight: bold; font-size: 14px;">₱{{ number_format($invoice->total_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td align="right" style="padding: 5px 12px; color: #4b5563; font-size: 14px;">Amount Paid</td>
                                        <td align="right" style="padding: 5px 12px; color: #16a34a; font-size: 14px;">- ₱{{ number_format($invoice->amount_paid, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td align="right" style="padding: 5px 12px; color: #111827; font-weight: 800; font-size: 18px;">Balance Due</td>
                                        <td align="right" style="padding: 5px 12px; color: #111827; font-weight: 800; font-size: 18px;">₱{{ number_format($invoice->total_amount - $invoice->amount_paid, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>

                            @if($invoice->status == 'paid')
                                <div style="text-align: center; margin-top: 20px;">
                                    <span style="color: #22c55e; border: 4px solid #22c55e; padding: 10px 20px; font-size: 30px; font-weight: bold; opacity: 0.5; display: inline-block; border-radius: 8px;">PAID</span>
                                </div>
                            @endif

                            <p style="text-align: center; color: #9ca3af; font-size: 12px; margin-top: 40px;">
                                Thank you!
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>