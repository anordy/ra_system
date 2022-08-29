<?php
@component('mail::message')
    # Hello {{ $application->taxpayer->fullname() }},

    Your Competence ID is {{$application->competence_number}}; Please visit ZRB for taking picture and collecting driving license card

    {{ config('app.name') }}
@endcomponent