@php $isMajor = $violation->offense_category === 'major'; @endphp
@if($violation->offense_code)
    <div class="flex items-center gap-2">
        <span class="px-2 py-0.5 rounded text-xs font-black text-white {{ $isMajor ? 'bg-red-600' : 'bg-gray-500' }}">{{ $violation->offense_code }}</span>
        <span class="text-xs font-bold text-gray-600">{{ $violation->offense_ordinal }} offense</span>
    </div>
    <p class="text-sm font-bold text-red-700 leading-tight mt-1">{{ \Illuminate\Support\Str::limit($violation->violation_type, 45) }}</p>
@else
    <p class="font-bold text-red-700">{{ $violation->violation_type }}</p>
@endif
