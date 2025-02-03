<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-12 lg:px-8">
            <div class="flex justify-center gap-6"> 
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900" style="height: 250px">
                        <a href="{{config('app.url_application.fe_am')}}">
                        <img src="{{url('logo_asset_management.jpeg')}}" alt="Image" style='height: 100%; width: 100%; object-fit: contain'/>
                        </a>
                    </div>
                   
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900" style="height: 250px">
                        <a href="{{config('app.url_application.fe_ifics')}}">
                            <img src="{{url('logo_helpdesk.jpeg')}}" alt="Image" style='height: 100%; width: 100%; object-fit: contain'/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
