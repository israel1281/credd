@component('mail::message')
# Hello {{ $user->first_name }},

Congratulations and welcome to {{ config('app.name') }}. We are simplifying how Africa does payments.

Do well to verify your email address and your bvn in other to start making use of our full features.

If you have any questions, please visit our FAQ page or [Contact us]({{ config('app.spa_url') }} "contact us"). <br>
Our Customer Support Team is available 24/7.

Best Regards,<br>
The {{ config('app.name') }} Team!
@endcomponent
