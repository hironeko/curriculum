@extends('layouts.app')
<!-- 追記 -->
@section('content')
    <!-- 追記 -->
    <div class="border-solid border-b-2 border-gry-500 p-2 mb-2">
        <div class="flex justify-between">
            <h2 class="text-base mb-4">新規追加</h2>
            <button
                class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                <a href="{{ route('todo.index') }}">戻る</a>
            </button>
        </div>
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- <form method="POST"> --}}
        {!! Form::open(['route' => 'todo.store']) !!}
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                Title
            </label>
            {{-- <input
                class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                type="text" name="name" placeholder="新規のTodo"> --}}
            {!! Form::textarea('title', null, ['required', 'class' => 'appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'placeholder' => '新規Title', 'rows' => '3']) !!}
        </div>
        <div class="mb-4">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                内容
            </label>
            {{-- <input
                class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                type="text" name="name" placeholder="新規のTodo"> --}}
            {!! Form::textarea('content', null, ['required', 'class' => 'appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500', 'placeholder' => '新規Todo']) !!}
        </div>
        {{-- <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
            登録
        </button> --}}
        {!! Form::submit('登録', ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded']) !!}
        {!! Form::close() !!}
        {{-- </form> --}}
    </div>
@endsection
