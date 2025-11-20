@extends('site')

@section('title', __('title_edit'))

@section('stylesheets')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            var collectors_search = <?=($collectors_search)?>;
            var collectors_data = [];
            for (var key in collectors_search) {
                var obj = collectors_search[key];
                collectors_data.push(obj);
            }
            $('.select2-collector').select2({
                data: collectors_data
            });

            var narrators_search = <?=($narrators_search)?>;
            var narrators_data = [];
            for (var key in narrators_search) {
                var obj = narrators_search[key];
                narrators_data.push(obj);
            }
            $('.select2-narrator').select2({
                data: narrators_data
            });

            var places_search = <?=($places_search)?>;
            var places_data = [];
            for (var key in places_search) {
                var obj = places_search[key];
                places_data.push(obj);
            }
            $('.select2-place').select2({
                data: places_data
            });

            var sources_search = <?=($sources_search)?>;
            var sources_data = [];
            for (var key in sources_search) {
                var obj = sources_search[key];
                sources_data.push(obj);
            }
            $('.select2-sources').select2({
                data: sources_data
            });

            var selected = <?=json_encode(old('sources', ($sources_selected)))?>;
            console.log(<?=json_encode(old('sources', ($sources_selected)))?>);
            var selected_data = [];
            for (var key in selected) {
                var obj = selected[key];
                selected_data.push(obj);
            }
            $(".select2-sources").val(selected_data);
            $(".select2-sources").trigger('change');
        });
    </script>
@endsection

@section('content')
    <div id="heading">
        <h2><a class="return-link" href="{{ url()->previous() }}">&nbsp;<&nbsp;</a>&nbsp;{{ __('resources.title_edit') }}</a></h2>

        <form method="POST" action="{{ route('legends.destroy', $legend->identifier) }}">
            @csrf
            @method('DELETE')
            <button class="resource-button" type="submit">{{ __('resources.button_delete') }}</button>
        </form>
    </div>
    
    <form action="{{ route('legends.update', $legend->identifier) }}" method="POST">
        @csrf
        @method('PUT')
        <table>
        <colgroup>
                <col span="1" id="display-item-column" />
                <col span="1" id="display-item-value"/>
        </colgroup>
        <tr>
            <td><b><label for="identifier">{{ __('resources.legend_identifier') }}: </label></b></td>
            <td><input type="text" id="identifier" name="identifier" value="{{ old('identifier', $legend->identifier) }}"></td>
        </tr>
        <tr>
            <td><b><label for="volume">{{ __('resources.legend_volume') }}: </label></b></td>
            <td><input type="text" id="volume" name="volume" value="{{ old('volume', $legend->volume) }}"></td>
        </tr>
        <tr>
            <td><b><label for="chapter_lv">{{ __('resources.legend_chapter-lv') }}: </label></b></td>
            <td><input type="text" id="chapter_lv" name="chapter_lv" value="{{ old('chapter_lv', $legend->chapter_lv)  }}"></td>
        </tr>
        <tr>
            <td><b><label for="chapter_de">{{ __('resources.legend_chapter-de') }}: </label></b></td>
            <td><input type="text" id="chapter_de" name="chapter_de" value="{{ old('chapter_de', $legend->chapter_de)  }}"></td>
        </tr>
        <tr>
            <td><b><label for="title_lv">{{ __('resources.legend_title-lv') }}: </label></b></td>
            <td><input type="text" id="title_lv" name="title_lv" value="{{ old('title_lv', $legend->title_lv) }}"></td>
        </tr>
        <tr>
            <td><b><label for="title_de">{{ __('resources.legend_title-de') }}: </label></b></td>
            <td><input type="text" id="title_de" name="title_de" value="{{ old('title_de', $legend->title_de) }}"></td>
        </tr>
        <tr>
            <td><b><label for="text_lv">{{ __('resources.legend_text-lv') }}: </label></b></td>
            <td><textarea id="text_lv" name="text_lv" rows="8">{{ old('text_lv', $legend->text_lv)  }}</textarea></td>
        </tr>
        <tr>
            <td><b><label for="text_de">{{ __('resources.legend_text-de') }}: </label></b></td>
            <td><textarea id="text_de" name="text_de" rows="8">{{ old('text_de', $legend->text_de) }}</textarea></td>
        </tr>
        <tr>
            <td><b><label for="metadata">{{ __('resources.legend_metadata') }}: </label></b></td>
            <td><input type="text" id="metadata" name="metadata" value="{{ old('metadata', $legend->metadata) }}"></td>
        </tr>

        <tr>
            <td><b><label for="collector">{{ __('resources.legend_collector') }}: </label></b></td>
            <td><select id="collector" name="collector" class="select2-collector">
                <option value="" disabled selected></option>
                @foreach ($collectors as $collector)
                    <option value="{{ $collector->id }}" {{ $collector->id == old('collector', $legend->collector_id) ? 'selected' : '' }}>
                        {{ $collector->fullname }}
                    </option>
                @endforeach
            </select></td>
        </tr>
        <tr>
            <td><b><label for="narrator">{{ __('resources.legend_narrator') }}: </label></b></td>
            <td><select id="narrator" name="narrator" class="select2-narrator">
                <option value="" disabled selected></option>
                @foreach ($narrators as $narrator)
                    <option value="{{ $narrator->id }}" {{ $narrator->id == old('narrator', $legend->narrator_id) ? 'selected' : '' }}>
                        {{ $narrator->fullname }}
                    </option>
                @endforeach
            </select></td>
        </tr>
        <tr>
            <td><b><label for="place">{{ __('resources.legend_place') }}: </label></b></td>
            <td><select id="place" name="place" class="select2-place">
                <option value="" disabled selected></option>
                @foreach ($places as $place)
                    <option value="{{ $place->id }}" {{ $place->id == old('place', $legend->place_id) ? 'selected' : '' }}>
                        {{ $place->name }} ({{ $place->latitude }}, {{ $place->longitude }})
                    </option>
                @endforeach
            </select></td>
        </tr>
        <tr>
            <td><b><label for="sources">{{ __('resources.legend_sources') }}: </label></b></td>
            <td><select id="sources" name="sources[]" class="select2-sources" multiple>
                @foreach ($sources as $source)
                    <option value="{{ $source->id }}">
                        {{ $source->title }} ({{ $source->identifier}})
                    </option>
                @endforeach
            </select></td>
        </tr>
        <tr>
            <td><b><label for="external_id">{{ __('resources.external-link-humma') }}: </label></b></td>
            <td><input type="number" id="external_id" name="external_id" value="{{ old('external_id', $collector->external_id) }}"></td>
        </tr>
        </table>
        <br>
        <button class="resource-button" type="submit">{{ __('resources.button_save') }}</button>
    </form>
    <br>
    <div id="validation-errors">
        @foreach($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endsection