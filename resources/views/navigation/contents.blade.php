@extends('site')

@section('title', __('site.title_contents'))

@section('scripts')
    <script>
        function toggleChapter(chapter) {
            var subchapterList = document.getElementById(chapter);
            if (subchapterList.style.display != "block") {
                subchapterList.style.display = "block"; 
            } else {
                subchapterList.style.display = "none"; 
            }
        }
    </script>
@endsection

@section('content')
<div id="table-of-contents">
    @foreach($chapters as $number => $volume)
        <div>
            <h3 class="heading">{{ __('site.title_chapter', ['number' => $number]) }}</h3>
            <ul class="chapters">
                @foreach($volume as $chapter)
                <li class="chapter">
                    @if(empty($subchapters[$number][$chapter[0]]))
                    <a class="chapter-title chapter-link" href="{{ route('navigation.chapter', urlencode($chapter[0])) }}">{{ $chapter[0].' / '.$chapter[1] }}</a>
                    @else
                    <div class="chapter-title"><a class="chapter-link" onclick="toggleChapter('{{ $number }}-{{ $chapter[0] }}')">{{ $chapter[0].' / '.$chapter[1] }}</a></div>
                    <ul id="{{ $number }}-{{ $chapter[0] }}" class="subchapters">
                        @foreach($subchapters[$number][$chapter[0]] as $subchapter)
                        <li><a class="chapter-link" href="{{ route('navigation.subchapter', [urlencode($chapter[0]), urlencode($subchapter[0])]) }}">{{ $subchapter[0].' / '.$subchapter[1] }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>
@endsection