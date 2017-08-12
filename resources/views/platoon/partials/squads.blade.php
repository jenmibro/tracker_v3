@forelse($squads as $squad)

    <div class="m-b-xxl">

        <div class="panel squad">
            <div class="panel-body">
                <h5 class="text-center">
                    <span class="pull-left">
                        @if($squad->leader)
                            {!! $squad->leader->present()->rankName !!}
                        @else
                            Leader TBA
                        @endif
                    </span>

                    <strong>
                        {{ $squad->name or $division->locality('squad') . " " . $loop->iteration }}
                    </strong>

                    <span class="pull-right">
                       @can('update', $squad)
                            <a href="{{ route('editSquad', [$division->abbreviation, $platoon, $squad]) }}"
                               title="Edit {{ $division->locality('squad') }}"
                               class="btn btn-default btn-sm">
                            <i class="fa fa-wrench"></i>
                        </a>
                        @endcan
                    </span>
                </h5>
            </div>

            <div class="table-responsive">

                <table class="table table-hover members-table">
                    <thead>
                    <tr>
                        <th class='col-hidden'><strong>Rank Id</strong></th>
                        <th class='col-hidden'><strong>Last Login Date</strong></th>
                        <th><strong>Member</strong></th>
                        <th class='nosearch text-center'><strong>Rank</strong></th>
                        <th class='text-center hidden-xs hidden-sm'><strong>Joined</strong></th>
                        <th class='text-center'><strong>Forum Activity</strong></th>
                        <th class='text-center'><strong>TS Activity</strong></th>
                        <th class="col-hidden">Ts activity Dates</th>
                        <th class='text-center'>
                            <string>Last Promoted</string>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($squad->members as $member)
                        <tr role="row" style="cursor:pointer;"
                            onclick="window.location.href = '{{ route('member', $member->clan_id) }}';">
                            <td class="col-hidden">{{ $member->rank_id }}</td>
                            <td class="col-hidden">{{ $member->last_activity }}</td>
                            <td>
                                @if ($squad->leader && $squad->leader->clan_id == $member->recruiter_id)
                                    <strong style="color: magenta;" title="Direct Recruit">*</strong>
                                @endif
                                @if ($member->leave)
                                    <span class="text-accent" title="On Leave">*</span>
                                @endif
                                {!! $member->present()->nameWithIcon !!}
                            </td>
                            <td class="text-center">{{ $member->rank->abbreviation }}</td>
                            <td class="text-center hidden-xs hidden-sm">{{ $member->join_date }}</td>
                            <td class="text-center">
                                <span class="{{ getActivityClass($member->last_activity, $division) }}">{{ $member->present()->lastActive }}</span>
                            </td>
                            <td class="text-center">
                                @if ($member->tsInvalid)
                                    <span class="text-danger">MISCONFIGURATION</span>
                                @else
                                    {{ Carbon::parse($member->last_ts_activity)->diffForHumans() }}
                                @endif
                            </td>
                            <td class="col-hidden">{{ $member->last_ts_activity }}</td>
                            <td class="text-center">{{ $member->last_promoted }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@empty
    <div class="panel-body text-muted">
        No {{ str_plural($division->locality('squad')) }} currently exist
    </div>
@endforelse

<div class="panel-footer">
    <small class="slight" style="color: magenta;" title="Direct Recruit">* - recruited by the current leader</small>
    <small class="slight" title="Direct Recruit"><span class="text-accent">*</span> - On Leave</small>
</div>