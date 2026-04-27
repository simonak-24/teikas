@foreach($places as $place)
    <div id="place-{{ $place->id }}" class="place-legends">
        <div class="heading">
            <h3>{{ $place->name }}</h3>
            <a class="popup-link" onclick="closePopup()">X</a>
        </div>
        <br>
        <div class="place-legend">
            <table>
                <colgroup>
                    <col span="1" id="place-legend-identifier" />
                    <col span="1" id="place-legend-chapter"/>
                    <col span="1" id="place-legend-title"/>
                    <col span="1" id="place-legend-text"/>
                </colgroup>
                <tr>
                    <th>{{ __('resources.legend_identifier') }}</th>
                    <th>{{ __('resources.legend_chapter-lv') }}</th>
                    <th>{{ __('resources.legend_title-lv') }}</th>
                    <th>{{ __('resources.legend_preview') }}</th>
                </tr>
                @foreach($place->legends as $legend)
                <tr>
                    <td><a href="{{ route('legends.show', $legend->identifier) }}" target="_blank">{{ $legend->identifier }}</a></td>
                    <td><a href="{{ route('navigation.chapter', $legend->chapter_lv) }}" target="_blank">{{ $legend->chapter_lv }} / {{ $legend->chapter_de }}</a></td>
                    <td><a href="{{ route('navigation.subchapter', [$legend->chapter_lv, $legend->title_lv]) }}" target="_blank">{{ $legend->title_lv }} / {{ $legend->title_de }}</a></td>
                    <td>{{ Str::limit($legend->text_lv, 120) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endforeach