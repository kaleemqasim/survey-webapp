<p>A new withdrawal request has been made.</p>

<p><strong>User:</strong> {{ $user->name }}</p>
<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>Amount:</strong> ${{ number_format($withdrawal->amount, 2) }}</p>
<p><strong>Bank Details:</strong></p>
<ul>
    <li>Account Holder Name: {{ $user->bank_holder_name }}</li>
    <li>Bank Name: {{ $user->bank_name }}</li>
    <li>IBAN: {{ $user->iban }}</
    <li>Branch Code: {{ $user->branch_code }}</li>
    <li>Country: {{ $user->bank_country }}</li>
</ul>

<p>Please process this withdrawal as soon as possible.</p>