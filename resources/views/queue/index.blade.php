<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Incident Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-12 lg:px-8">
            <div class="flex gap-8">
                
                <div class="w-1/2">
                    Failed Job
                    <table> 
                        <tr>
                            <th> No </th>
                            <th class="px-4 py-2 whitespace-nowrap text-center"> Time Failed </th>
                            <th class="px-4 py-2 whitespace-nowrap text-center"> Payload </th>
                            <th class="px-4 py-2 whitespace-nowrap text-center"> Error Message </th>
                            <th class="px-4 py-2 whitespace-nowrap text-center"> Action </th>
                        </tr>
                        @foreach($failed_jobs as $failed)
                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td> {{ $failed->failed_at }}</td>
                            <td> {{ $failed->payload }} </td>
                            <td> {{ $failed->exception }}</td>
                            <td> </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
