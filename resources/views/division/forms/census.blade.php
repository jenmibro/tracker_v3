<div class="table-responsive">
    <table class="table table-hover basic-datatable table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th class="text-center">Population</th>
            <th class="text-center">Weekly TS Active</th>
            <th class="text-center" title="Data began collection 4/7/2024">Weekly Discord Active*</th>
{{--            <th class="text-center">Weekly Forum Active</th>--}}
            {{--<th>Notes</th>--}}
        </tr>
        </thead>
        @foreach ($censuses as $census)

            @php
                $popMinusTs = $census->count - $census->weekly_ts_count;
                $popMinusDiscord = $census->count - $census->weekly_voice_count;
            @endphp

            <tr>
                <td>{{ $census->created_at->format('m/d/Y') }}</td>
                <td class="text-center">{{ $census->count }}</td>
                <td class="text-center slight">
                    {{ $census->count > 0 ? number_format($census->weekly_ts_count / $census->count * 100, 1) : 0 }}%
                    <span class="census-pie"
                          data-colors="{{ json_encode(['#404652', '#56C0E0']) }}"
                          data-counts="{{ json_encode([$popMinusTs, $census->weekly_ts_count]) }}">
                    </span>
                </td>
                <td class="text-center slight">
                    {{ $census->count > 0 ? number_format($census->weekly_voice_count / $census->count * 100, 1) : 0
                     }}%
                    <span class="census-pie"
                          data-colors="{{ json_encode(['#404652', '#56C0E0']) }}"
                          data-counts="{{ json_encode([$popMinusDiscord, $census->weekly_voice_count]) }}">
                    </span>
                </td>
{{--                <td class="text-center slight">--}}
{{--                    {{ $census->count > 0 ? number_format($census->weekly_active_count / $census->count * 100, 1) : 0 }}%--}}
{{--                    <span class="census-pie"--}}
{{--                          data-colors="{{ json_encode(['#404652', '#1bbf89']) }}"--}}
{{--                          data-counts="{{ json_encode([$popMinus, $census->weekly_active_count]) }}">--}}
{{--                    </span>--}}
{{--                </td>--}}
                {{--<td>{{ $census->notes }}</td>--}}
            </tr>
        @endforeach
    </table>
</div>