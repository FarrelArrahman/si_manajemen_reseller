@component('mail::message')
# {{ $data['subject'] }}
 
{{ $data['message'] }}
 
@component('mail::button', ['url' => $data['url']])
{{ $data['button'] }}
@endcomponent
 
Terima kasih,<br>
{{ config('app.name') }}
@endcomponent