@php
    $dateTime = $timestamp ? \Carbon\Carbon::parse($timestamp)->format('d-m-Y H:i') : '';
    $userName = $userId ? \App\Models\User::find($userId)->user_nicename ?? '' : '';
@endphp

@if($dateTime || $userName)
    @if($dateTime)
        <p class="date-time text-center">
            <span class="fw-bold label d-block">Updated At:</span>
            <span class="value d-block">{{ $dateTime }}</span>
        </p>
        <hr>
    @endif

    @if($userName)
        <p class="date-time text-center">
            <span class="fw-bold label d-block">Updated By:</span>
            <span class="value d-block">{{ $userName }}</span>
        </p>
    @endif
@else
    <p class="date-time">
        <span class="value d-block">None</span>
    </p>
@endif
