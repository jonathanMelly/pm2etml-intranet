@props([
    'effort'=>true
])
<th><i class="fa-solid fa-calendar-day"></i> {{__('Start')}}</th>
<th><i class="fa-solid fa-calendar-days"></i> {{__('End')}} </th>
<th class="text-center"><i class="fa-solid fa-hourglass-half"></i> {{__('Elapsed time')}}</th>
@if($effort)
<th class="text-center"><i class="fa-solid fa-fire-burner"></i> {{__('Effort')}}</th>
@endif
<th class="text-center"><i class="fa-solid fa-stopwatch"></i> {{__('Remaining time')}}</th>
<th class="text-left"><i class="fa-solid fa-clipboard-check"></i> {{__('Result')}}</th>
