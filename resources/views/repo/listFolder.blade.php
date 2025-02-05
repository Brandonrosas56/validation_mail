<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{__('List Folder')}}
        </h2>
    </x-slot>
    <div class="flex w-full h-full items-center justify-center mt-8" >
        <div class="flex justify-center w-60 h-full gap-4  rounded-lg" style="background: #B8C3CA;">
            <form action="{{route('move_file')}}" method="POST">
                @csrf
                <label class="mt-4" style="font-size:24px;">{{__('Select_folder')}}</label>
                @foreach($directoriesList as $list)
                <button value="{{$list}}" name="folder" class="mt-4 mb-4 flex border-2 rounded-2 px-4 py-2 border-transparent rounded-md" style="background: #009F00">
                    {{$list}}
                </button>
                @endforeach
            </form>
        </div>
    </div>
</x-app-layout>