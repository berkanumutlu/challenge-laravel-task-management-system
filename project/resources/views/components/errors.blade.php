@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 {{ $alertClass ?? '' }}"
         role="alert">
        <ul class="list-group list-unstyled {{ $ulClass ?? '' }}">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
