@extends('layouts.master')

@section('title', 'Streets')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Streets
            <div class="card-tools">
                @can('setting-street-add')
                    @if (approvalLevel(auth()->user()->level_id, 'Maker'))
                        <a href="{{ route('settings.street.bulk-sample-download') }}"
                            class="btn btn-outline-secondary btn-sm px-3">
                            Download Sample Bulk Sheet
                        </a>

                        <button onclick="document.getElementById('fileInput').click();" class="btn btn-secondary btn-sm px-3">
                            <i class="bi bi-plus-circle-fill pr-1"></i> Upload Bulk
                        </button>

                        <button class="btn btn-primary btn-sm px-3" onclick="Livewire.emit('showModal', 'street-add-modal')">
                            <i class="bi bi-plus-circle-fill pr-1"></i> Add New Street
                        </button>
                    @endif
                @endcan
            </div>
        </div>



        <div class="card-body">
            @livewire('street-table')
        </div>
    </div>

    <form action="{{ route('settings.street.bulk-upload') }}" method="post" enctype="multipart/form-data" id="uploadForm">
        @csrf
        <input type="file" name="file" id="fileInput" accept=".csv">
    </form>

    <script>
        document.getElementById('fileInput').style.display = 'none'
        document.getElementById('fileInput').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                let fileName = e.target.files[0].name;
                document.getElementById('uploadForm').submit();
            }
        });
    </script>
@endsection
