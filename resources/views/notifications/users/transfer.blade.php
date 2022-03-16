@component('mail::message')
**Hi {{ $user->first_name }},**

Your money has been sent.<br>
The details of your transaction are shown below:

<table width="720px">
	<tbody>
		<tr>
			<td width="250px">Payment Method</td>
			<td width="10px">:
			<td width="360px">Bank Transfer</td>
		</tr>
		<tr>
			<td width="250px">Amount</td>
			<td width="10px">:
			<td width="360px">N1000</td>
		</tr>
		<tr>
			<td width="250px">Transaction ID</td>
			<td width="10px">:
			<td width="360px">0945969959</td>
		</tr>
		<tr>
			<td width="250px">Remarks</td>
			<td width="10px">:
			<td width="360px">Payment for funds</td>
		</tr>
		<tr>
			<td width="250px">Date/Time</td>
			<td width="10px">:
			<td width="360px">24-04-2021 01:12pm</td>
		</tr>
	</tbody>
</table>

@include('emails.signature')
@endcomponent
