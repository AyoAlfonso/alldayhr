@if(count($jobQuestion) > 0)
    @forelse($jobQuestion as $question)
        <div class="form-group">
            <input class="form-control" type="text"id="answer[{{ $question->question->id}}]" name="answer[{{ $question->question->id}}]" placeholder="{{ $question->question->question }} ?">
        </div>
    @empty
    @endforelse
@endif