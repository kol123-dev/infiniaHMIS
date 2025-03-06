@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getLogoUrl()) }}" class="logo" style="object-fit: cover" alt="{{ getAppName() }}">
        @endcomponent
    @endslot

    @php
        $sAdmin = App\Models\User::orderBy('created_at')->first();
    @endphp

    <p><b>Hello {{$sAdmin->first_name.' '.$sAdmin->last_name }},</b></p>
    <p>This is a enquiry notification from <b>{{ $data['full_name'] }}</b></p>
    <p>Purpose: {{$data['purpose']}}</p>
    <p>Phone: {{$data['contact_no']}}</p>
    <p>Email: {{$data['email']}}</p>
    <p>Message: {{$data['message']}}</p>
    <br>
    <p>Thanks & Regards,</p>
    <p>{{ getAppName() }}</p>
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
