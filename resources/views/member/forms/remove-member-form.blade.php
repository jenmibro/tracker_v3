<div class="panel-heading">
    <i class="fa fa-trash text-danger"></i> Remove Member
</div>

<div class="panel-body">
    <p>
        <span class="text-warning">WARNING:</span> You are about to remove a member from AOD, which cannot be reversed.
        One removed, a member
        <strong>MUST</strong> be re-inducted through the traditional recruitment procedure. This process does several
        things:
    </p>
    <ul>
        <li>Resets any platoon, squad, position, and leadership assignments the member currently has</li>
        <li>Dissociates the member from any division they are currently full-time, or part-time in</li>
        <li>Opens the AOD Member Removal form, performing forum removal from AOD</li>
    </ul>

    <p>If you are sure you wish to proceed, provide a brief explanation for the removal, and click to proceed.</p>

    <div class="form-group">
        {!! Form::label('removal_reason', 'Reason') !!}
        {!! Form::textarea('removal_reason', null, ['class' => 'form-control', 'required' => 'required', 'rows' => 3, 'value' => 'Member removed. Reason: ']) !!}
    </div>

    {{ csrf_field() }}
</div>
<div class="panel-footer">
    <button type="submit" title="Remove player from AOD" data-member-id="{{ $member->clan_id }}"
            class="btn btn-danger remove-member">Submit<span class="hidden-sm hidden-xs"> removal</span></button>
</div>
