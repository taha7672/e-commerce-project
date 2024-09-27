<x-mail::message>
<strong>You request for Reset Password.</strong> <br> <br>
Click the reset button below to reset your password. If you did not request to reset your password, please contact support immediately.

<x-mail::button :url="route('password.reset',['token'=>$token])">
Reset Password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
