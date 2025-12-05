<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Incident Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-12 lg:px-8">
            <form method="GET" action="{{ route('web.incident.generate_duedate') }}">
                @csrf


                <div class="px-4 py-2">
                    <x-input-label for="incident_no" :value="__('Branch')" />
                    <select id="branch_id" name="branch_id" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option value="">-- Please select --</option>
                        @foreach($list_branch as $branch)
                        <option value="{{ $branch->id }}" @if(old('branch_id', request()->branch_id) == $branch->id) selected @endif >{{ $branch->name }}</option>
                        @endforeach 
                    </select>
                </div>

                <div class="px-4 py-2">
                    <x-input-label for="incident_no" :value="__('Sla Version')" />
                    <select id="sla_template_id" name="sla_template_id" class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option value="">-- Please select --</option>
                        @foreach($list_sla_template as $sla_template)
                        <option value="{{ $sla_template->id }}" @if(old('sla_template_id', request()->sla_template_id) == $sla_template->id) selected @endif >{{ $sla_template->code}}</option>
                        @endforeach 
                    </select>
            </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Generate Due Date') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
        <div class="max-w-7xl mx-auto sm:px-12 lg:px-8">
            @if($generate_due_date)
                <div class="w-1/2">
                    <span class="px-4 py-2">
                    Accident Date = {{ $incident_date->format('l,d F Y h:i A') }}
                    </span>
                </div>
                <div class="w-2/2">
                    <span class="px-4 py-2">
                        Expected Due Date = {{ $generate_due_date->format('l,d F Y h:i A') }}
                    </span>
                </div>
            @endif
       
            <div class="flex gap-8 px-4 py-6">
                <div class="w-1/2">
                    Operating Time 
                    <table> 
                        <tr>
                            <th> No </th>
                            <th class="px-4 py-2 whitespace-nowrap text-center"> Day Start </th>
                            <th class="px-4 py-2 whitespace-nowrap text-center"> Day End </th>
                            <th class="px-4 py-2 whitespace-nowrap text-center"> Operation Start </th>
                            <th class="px-4 py-2 whitespace-nowrap text-center"> Operation End </th>
                            <th class="px-4 py-2 whitespace-nowrap text-center"> Total Working Hour </th>

                        </tr>
                        @foreach($operating_time as $idx => $operating)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-center"> {{ $idx +1 }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-center"> {{ $operating->daystartDescription?->name }} </td>
                            <td class="px-4 py-2 whitespace-nowrap text-center"> {{ $operating->dayendDescription?->name }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-center"> {{ $operating->operation_start }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-center"> {{ $operating->operation_end }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-center"> 
                                @php  
                                    $start = Carbon\Carbon::parse($operating->operation_start);
                                    $end   = Carbon\Carbon::parse($operating->operation_end);
                                    $working_minutes = $start->diffInMinutes($end);
                                    $hours = intdiv($working_minutes, 60);
                                    $minutes = $working_minutes % 60;
                                @endphp 
                                {{ $hours .' Hours '. $minutes .' Minute' }}
                            </td>

                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="flex gap-8 px-4 py-6">
                <div class="w-1/2">
                    Public Holiday 
                    <table> 
                        <tr>
                            <th> No </th>
                            <th> Name </th>
                            <th> Start Date </th>
                            <th> End Date </th>
                        </tr>
                        @foreach($public_holiday as $idx => $holiday)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-center"> {{ $idx +1 }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-left"> {{ $holiday->name }} </td>
                            <td class="px-4 py-2 whitespace-nowrap text-center"> {{ $holiday->start_date?->format('d F Y') }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-center"> {{ $holiday->end_date?->format('d F Y') }}</td>

                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        
    </div>

</x-app-layout>
