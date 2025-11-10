{{-- resources/views/emails/reset-password.blade.php --}}
@component('mail::message')
# Reset your password

Hello {{ $user->name ?? 'there' }},

Click the button below to reset your password. The link will expire soon.

@component('mail::button', ['url' => $resetUrl])
Reset Password
@endcomponent

If you didn’t request this, please ignore.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
