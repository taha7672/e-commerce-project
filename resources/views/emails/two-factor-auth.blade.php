<x-mail::message>
    <strong>Your Two-Factor Authentication Code</strong> <br> <br>
    Use the following 4-digit code to complete your login process. If you did not request this code, please contact
    support immediately.
    <br> <br> <strong> {{ $code }}</strong> <br> <br>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
