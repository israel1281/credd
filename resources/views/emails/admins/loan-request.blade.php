@component('mail::message')
# Details of Loan Application
-----------------------------
<table>
    <tbody style="border-bottom: 1px solid black">
        <tr>
            <th align="left">Name</th>
            <td align="center" width="120px">:</td>
            <td>{{ $loanRequest->user->name }}</td>
        </tr>
        <tr>
            <th align="left">Amount</th>
            <td align="center" width="120px">:</td>
            <td>{{ $loanRequest->amount_string }}</td>
        </tr>
        <tr>
            <th align="left">Interest</th>
            <td align="center" width="120px">:</td>
            <td>{{ $loanRequest->interest_string }}</td>
        </tr>
        <tr>
            <th align="left">Date</th>
            <td align="center" width="120px">:</td>
            <td>{{ $loanRequest->created_at }}</td>
        </tr>
    </tbody>
</table>

@component('mail::button', ['url' => spaUrlBuilder('/dashboard/admin/loan/pending')])
Go to Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
