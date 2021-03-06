@php
    $progress = $contract->getProgress();
    $progressPercentage = $progress['percentage'];
    $remainingDays = $progress['remainingDays'];

    $workers=collect($contract->workers)->transform(fn ($gm)=>$gm->user->getFirstnameL())->join(',')
@endphp
<tr>
    <td>
        <label>
            <input type="checkbox" class="checkbox" name="job-{{$job->id}}-contracts[]" value="{{$contract->id}}" data-workers="{{$workers}}"
            @change="massAction=isAnyChecked('job-{{$job->id}}-contracts[]')">
        </label>
    </td>
    <td>
        {{$contract->workers[0]->group->groupName->name}}
    </td>
    <td>
        {{$workers}}
    </td>
    <td>
        {{$contract->start->format(\App\SwissFrenchDateFormat::DATE)}}
    </td>
    <td>
        {{$contract->end->format(\App\SwissFrenchDateFormat::DATE)}}
    </td>
    <td class="w-36">
        <progress class="progress progress-accent w-36" value="{{$progressPercentage}}" max="100"></progress> ({{$progressPercentage}}%)
    </td>

    <td class="text-center">
        @if($progressPercentage<100)
            {{$remainingDays}} {{$remainingDays>1?__('days'):__('day')}}
        @else
            <i class="fa-solid fa-flag-checkered"></i>
        @endif
    </td>

    <x-contract-list-element-evaluation :contract="$contract" />
</tr>
