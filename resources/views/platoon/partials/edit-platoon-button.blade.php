@can('update', $platoon)
    <a href="{{ route('editPlatoon', [$division->abbreviation, $platoon]) }}"
       title="Edit {{ $platoon->name }}"
       class="btn btn-default pull-right"><i class="fa fa-wrench text-success"></i> Edit
    </a>
@endcan