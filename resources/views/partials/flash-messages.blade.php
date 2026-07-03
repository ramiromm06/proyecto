@if (session('success'))
    <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    </div>
@endif

@if (session('error'))
    <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    </div>
@endif
