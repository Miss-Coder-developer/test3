<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Images') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Select a folder to work with
                </div>

                <div class="py-6">
                    <table class="min-w-max w-full table-auto">
                        <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <td class="py-3 px-6 text-left">Folder name</td>
                            <td class="py-3 px-6 text-left">Total</td>
                            <td class="py-3 px-6 text-left">Confirmed</td>
                            <td class="py-3 px-6 text-left">Approved</td>
                            <td class="py-3 px-6 text-left">Deleted</td>

                        </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                        @foreach($folders as $folder)

                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                <a href="{{route('images.show', ['id'=>$folder['full_link'], 'type'=>$type])}}">{{$folder['name']}}</a>
                            </td >
                            <td class="py-3 px-6 text-left whitespace-nowrap"> {{$folder['total']?? ''}}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{$folder['confirmed']?? ''}}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{$folder['approved']?? ''}}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{$folder['deleted']?? ''}}</td>


                        </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .dropdown:focus-within .dropdown-menu {
        opacity:1;
        transform: translate(0) scale(1);
        visibility: visible;
    }
</style>
