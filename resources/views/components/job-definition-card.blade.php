@props([
    'viewOnly' => true,
    'job'
])
@php
    use \App\Enums\JobPriority;
    /*
    $badge='<div class="badge badge-xs badge-TYPE">VALUE</div>';
    $priorityType=match($job->priority){
        JobPriority::MANDATORY=>'success',
        JobPriority::RECOMMENDED=>'info',
        JobPriority::HIGHLY_RECOMMENDED=>'warning',
        JobPriority::BEYOND=>'error'
    };
    */

    if($job->priority === JobPriority::MANDATORY)
    {
        //$badgePriority = '<div class="badge badge-sm badge-error">'.__('Mandatory').'</div>';
        $mandatoryBadge = '<span class="indicator-item badge badge-secondary bg-opacity-50">'.__('Mandatory').'</span>';
    }
    //$badge = '<div class="badge badge-success">'.$job->priority->name.'</div>';
    //
    //$badgePriority = str_replace(['TYPE','VALUE'],[$priorityType,__($job->priority->name)],$badge);
    $requiredYears = $job->required_xp_years +1;
    $yearStyle = ['info','success','warning','neutral'][$job->required_xp_years];
    $priorityStyle = ['error','warning','accent','neutral'][$job->priority->value];


    //faster local tests...
    if(config('custom.hide-job-image'))
    {
        $imageSrc='';
        $imageBg='bg-base-300';
    }
    else
    {
        $mask = \App\Http\Middleware\Theme::timestampToTheme(now()->toDateTime())=='valentine'?'mask mask-heart':'rounded-md';
        $imageSrc=route('dmz-asset',['file'=>$job->image?->storage_path]);
    }


    if(!$viewOnly)
    {
        $cardElement = 'a';
        $cardAdditionalClass = 'hover:bg-gradient-to-b hover:from-primary/25 hover:to-base-100';
        $cardHref= 'href='. route('jobs-apply-for',['jobDefinition'=>$job]);
    }
    else
    {
        $cardElement='div';
        $cardAdditionalClass='';
        $cardHref='';
    }

@endphp

<div class="flex flex-col">

    <div class="z-10 absolute h-0 flex flex-row">
        @if($job->attachments->isNotEmpty())
        <div class="dropdown dropdown-hover">
            <label tabindex="0">
                <img src="{{url('img/paperclip.svg')}}" class="hover:cursor-pointer h-10" >
            </label>
            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-96">
                @foreach($job->attachments as $docAttachment)
                    <li>
                        <a href="{{attachmentUri($docAttachment)}}" download="{{$docAttachment->name}}" ><i class="fa-solid fa-paperclip"></i> {{ Str::limit($docAttachment->name,30,'....'. pathinfo($docAttachment->name)['extension'])}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        @endif
        @if($job->skills->isNotEmpty())
        <div x-data="{ open: false }" @mouseleave="open = false" class="hover:cursor-help">
            <label tabindex="0">
                <img @mouseover="open = true" src="{{url('img/badge.svg')}}" class="hover:cursor-help h-10" >
            </label>
            <div x-show="open" class="overflow-y-auto rounded-box border-2 border-base-300 bg-base-200 text-base-content w-80 max-h-80">
                <h2 class="text-center">{{__('Skills')}}</h2>
                @foreach($job->skills as $skill)
                    <div class="badge badge-outline gap-2 mx-1 my-1">
                        {{$skill->getFullName()}}
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>


    <{{$cardElement}} class="h-full mt-2 card card-compact {{$job->one_shot?'border border-accent border-dashed':''}} w-auto bg-base-100 shadow-xl {{$cardAdditionalClass}}" {{$cardHref}} >
        {{-- DELETE/EVALUATE BUTTONS --}}
        @if($job->providers->contains(Auth::user()) || Auth::user()->can('jobDefinitions'))
            <div class="place-self-end mr-3 mt-2 -mb-6 flex gap-1">
                @can(['jobDefinitions.edit'])
                    <div class="rounded-box hover:cursor-pointer hover:bg-info hover:bg-opacity-50">
                        <a href="{{route('jobDefinitions.edit',$job)}}">
                            <i class="fa-solid fa-edit"></i>
                        </a>
                    </div>
                @endcan
                @can('jobDefinitions.trash')
                    <div class="rounded-box hover:bg-error hover:bg-opacity-50">
                        <a @click="document.querySelector('#delete-job-form').action='{{url('jobDefinitions',$job->id)}}';
                    jobNameToDelete='{{$job->title}}';
                    setTimeout(()=>document.querySelector('#delete-job-modal-submit').disabled=false,3000)">

                            <label for="delete-job-modal" class="hover:cursor-pointer">
                                <i class="fa-solid fa-trash "></i>
                            </label>

                        </a>
                    </div>
                @endcan
            </div>
        @endif


        <div class="indicator self-center mt-3">
            {!!  $mandatoryBadge??'' !!}
            <div class="grid w-24 h-24 place-items-center {{$imageBg??''}}">
                <figure class="mt-1">
                    <img class="object-scale-down {{$mask??''}}" src="{{$imageSrc}}" alt="{{$job->title}}"/>
                </figure>
            </div>

        </div>


        <div class="card-body">

            <h2 class="card-title">{{ $job->title }}
                {{--
                <div class="flex flex-col gap-1 items-center">
                {!! $badgePriority??'' !!}
                <div class="badge badge-sm badge-{{$badgeYears}}">{!! $requiredYears.'<sup>'.__(ordinal($requiredYears)).'</sup>&nbsp;'.__('year')  !!}</div>
                </div>
                --}}
            </h2>
            <p>
            {{ $job->description }}
            </p>

            <div class="grid grid-cols-3 border-secondary border border-opacity-50 rounded divide-y divide-dotted divide-secondary
                    {{$viewOnly?'max-w-fit self-center':''}}">
                <div class="flex justify-end content-center text-sm pr-1">
                    {{__('Priority')}}
                </div>
                <div class="col-span-2 justify-start items-center">
                    <progress class="progress progress-{{$priorityStyle}} w-20"
                              value="{{\App\Enums\JobPriority::last()->value-$job->priority->value}}"
                              max="{{\App\Enums\JobPriority::last()->value}}"></progress>
                    <span
                        class="text-xs">&nbsp;( {{__(Str::ucfirst(Str::lower($job->priority->name)))}} )</span>
                </div>

                <div class="flex justify-end content-center text-sm pr-1">
                    {{__('Experience')}}
                </div>
                <div class="col-span-2 justify-start items-center">
                    <progress class="progress progress-info w-20" value="{{$job->required_xp_years}}"
                              max="3"></progress>
                    <span class="text-xs">&nbsp;( {!! $requiredYears.'<sup>'.__(ordinal($requiredYears)).'</sup>&nbsp;'.__('year')  !!} )</span>
                </div>

                <div class="flex justify-end content-center text-sm pr-1">
                    {{__('Workload')}}
                </div>
                <div class="col-span-2 justify-start items-center">
                    <progress class="progress progress-success w-20"
                              min="{{\App\Models\JobDefinition::MIN_PERIODS}}"
                              value="{{$job->getAllocatedTime(\App\Enums\RequiredTimeUnit::PERIOD)}}"
                              max="{{\App\Models\JobDefinition::MAX_PERIODS}}"></progress>
                    <span class="text-xs">&nbsp;( {{$job->getAllocationDetails()}} )</span>
                </div>

            </div>
            <div class="card-actions justify-end">
                <i class="text-accent">{{__('Providers')}}: </i>
                @foreach($job->providers as $provider)
                    <button class="btn btn-accent btn-outline btn-xs">{{ $provider->getFirstnameL() }}</button>
                @endforeach

            </div>
        </div>
    </{{$cardElement}}>

</div>
