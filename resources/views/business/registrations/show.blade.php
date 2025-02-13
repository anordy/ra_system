@extends('layouts.master')

@section('title', "Business Registration Details for {$business->name}")

@section('content')
    <div class="my-3">
        @include('business.registrations.includes.business_info')
    </div>

    <div class="card rounded-0">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            Business Location Tax Returns
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs shadow-sm mb-0">
                @foreach($business->locations as $location)
                    <li class="nav-item">
                        <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                           id="{{ strtolower(str_replace(' ', '-', $location->name)) }}-tab"
                           data-toggle="tab" href="#{{ strtolower(str_replace(' ', '-', $location->name)) }}"
                           aria-controls="{{ strtolower(str_replace(' ', '-', $location->name)) }}"
                           role="tab" aria-selected="true">
                            {{ $location->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content bg-white border shadow-sm" id="myTabContent">
                @foreach($business->locations as $location)
                    <div class="tab-pane fade p-3 {{ $loop->first ? 'show active' : '' }}" id="{{ strtolower(str_replace(' ', '-', $location->name)) }}" role="tabpanel"
                         aria-labelledby="{{ strtolower(str_replace(' ', '-', $location->name)) }}-tab">
                        <livewire:business.location-returns-summary locationId="{{ encrypt($location->id) }}" />
                        <livewire:business.location-returns-table location_id="{{$location->id}}" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
