@component('mail::message')
**Hi {{ $user->first_name }},**

Your {{ config('app.name') }} balance has been updated.<br>
The details of your transaction are shown below:

<table width="720px">
	<tbody>
		<tr>
			<td width="250px">Payment Method</td>
			<td width="10px">:
			<td width="360px">{{ $paymentMethod }}</td>
		</tr>
		<tr>
			<td width="250px">Amount</td>
			<td width="10px">:
			<td width="360px">{{ $transaction->amount_string }}</td>
		</tr>
		<tr>
			<td width="250px">Transaction ID</td>
			<td width="10px">:
			<td width="360px">{{ $transaction->txn_no }}</td>
		</tr>
		<tr>
			<td width="250px">Date/Time</td>
			<td width="10px">:
			<td width="360px">{{ $transaction->created_at }}</td>
		</tr>
	</tbody>
</table>
<br>
@include('emails.signature')
@endcomponent
