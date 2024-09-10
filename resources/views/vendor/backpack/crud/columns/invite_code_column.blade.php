@if($entry->inviteCode)
    {{$entry->inviteCode->code}}
@else
    {{'-'}}
@endif
