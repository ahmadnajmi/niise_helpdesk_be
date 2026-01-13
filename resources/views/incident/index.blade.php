<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Incident Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-12 lg:px-8">
            <form method="GET" action="{{ route('web.incident.index') }}">
                @csrf


                <div>
                    <x-input-label for="incident_no" :value="__('Incident Number')" />
                    <x-text-input id="incident_no" class="block mt-1 w-full" type="text" name="incident_no"  required autofocus value="{{ request()->incident_no}}" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Get Incident Details') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
        @if(isset($incident))
        <div class="max-w-7xl mx-auto sm:px-12 lg:px-8">
            <div class="flex gap-8">
                
                <div class="w-1/2">
                    Incident Details
                    <table> 
                        <tr>
                            <td class="px-5"> Incident  No</td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->incident_no }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> State </td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->branch?->stateDescription?->name }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Branch </td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->branch?->name }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Incident Date </td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->incident_date->format('d F Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Expected Due Date </td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->expected_end_date?->format('d F Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Actual Due Date </td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->actual_end_date?->format('d F Y h:i A') }}</td>
                        </tr>
                        
                        <tr>
                            <td class="px-5"> Total Penalty IRT Price</td>
                            <td> : </td>
                            <td class="px-5"> RM {{ $incident->incidentPenalty?->penalty_irt }}</td>
                        </tr>
                         <tr>
                            <td class="px-5"> Total Penalty ORT Price</td>
                            <td> : </td>
                            <td class="px-5"> RM {{ $incident->incidentPenalty?->penalty_ort }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Total Penalty PRT Price</td>
                            <td> : </td>
                            <td class="px-5"> RM {{ $incident->incidentPenalty?->penalty_prt }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Total Penalty VPRT Price</td>
                            <td> : </td>
                            <td class="px-5"> RM {{ $incident->incidentPenalty?->penalty_vprt }}</td>
                        </tr>

                    </table>
                </div>
                <div class="w-2/2">
                    SLA TEMPLATE {{ $incident->sla?->slaTemplate?->code }} Version =  {{$incident->slaVersion?->version}}
                    <table> 
                        <tr>
                            <td class="px-5"> SLA CODE </td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->code_sla }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Initial Response Time (IRT) </td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->slaVersion?->response_time.' '.$incident->slaVersion?->responseTimeTypeDescription?->name }} 
                                RM {{ $incident->slaVersion?->response_time_penalty.' Per '.$incident->slaVersion?->responseTimePenaltyTypeDescription?->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-5"> OnSite Response Time (ORT) </td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->slaVersion?->response_time_location.' '.$incident->slaVersion?->responseTimeLocationTypeDescription?->name }} 
                                RM {{ $incident->slaVersion?->response_time_location_penalty.' Per '.$incident->slaVersion?->responseTimeLocationPenaltyTypeDescription?->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-5"> Problem Resolution Time (PRT) </td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->slaVersion?->resolution_time.' '.$incident->slaVersion?->resolutionTimeTypeDescription?->name }} 
                                RM {{ $incident->slaVersion?->resolution_time_penalty.' Per '.$incident->slaVersion?->resolutionTimePenaltyTypeDescription?->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-5"> Verify Problem Resolution Time (VPRT) </td>
                            <td> : </td>
                            <td class="px-5"> {{ $incident->slaVersion?->verify_resolution_time.' '.$incident->slaVersion?->verifyResolutionTimeTypeDescription?->name }} 
                                RM {{ $incident->slaVersion?->verify_resolution_time_penalty.' Per '.$incident->slaVersion?->verifyResolutionTimePenaltyTypeDescription?->name }}
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
            <br>
            <div class="flex gap-8">
                <div class="w-1/2">
                    Stimulate Incident Details
                    <table> 
                        <tr>
                            <td class="px-5">Generate Due Date </td>
                            <td> : </td>
                            <td class="px-5"> {{ $generate_due_date?->format('d F Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Total Penalty IRT Price</td>
                            <td> : </td>
                            <td class="px-5"> RM {{ $generate_penalty['penalty_irt'] }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Total Penalty ORT Price</td>
                            <td> : </td>
                            <td class="px-5"> RM {{ $generate_penalty['penalty_ort'] }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Total Penalty PRT Price</td>
                            <td> : </td>
                            <td class="px-5"> RM {{ $generate_penalty['penalty_prt'] }}</td>
                        </tr>
                        <tr>
                            <td class="px-5"> Total Penalty VPRT Price</td>
                            <td> : </td>
                            <td class="px-5"> RM {{ $generate_penalty['penalty_vprt'] }}</td>
                        </tr>
                    </table>
                </div>
                
            </div>


            <div class="flex gap-8 px-5 py-6">
                <div class="w-1/2">
                    Operating Time 
                    <table> 
                        <tr>
                            <th> No </th>
                            <th class="px-5 py-2 whitespace-nowrap text-center"> Day Start </th>
                            <th class="px-5 py-2 whitespace-nowrap text-center"> Day End </th>
                            <th class="px-5 py-2 whitespace-nowrap text-center"> Operation Start </th>
                            <th class="px-5 py-2 whitespace-nowrap text-center"> Operation End </th>
                        </tr>
                        @foreach($operating_time as $idx => $operating)
                        <tr>
                            <td class="px-5 py-2 whitespace-nowrap text-center"> {{ $idx +1 }}</td>
                            <td class="px-5 py-2 whitespace-nowrap text-center"> {{ $operating->daystartDescription?->name }} </td>
                            <td class="px-5 py-2 whitespace-nowrap text-center"> {{ $operating->dayendDescription?->name }}</td>
                            <td class="px-5 py-2 whitespace-nowrap text-center"> {{ $operating->operation_start }}</td>
                            <td class="px-5 py-2 whitespace-nowrap text-center"> {{ $operating->operation_end }}</td>

                        </tr>
                        @endforeach
                    </table>
                </div>
                <div class="w-2/2">
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
                            <td class="px-5 py-2 whitespace-nowrap text-center"> {{ $idx +1 }}</td>
                            <td class="px-5 py-2 whitespace-nowrap text-left"> {{ $holiday->name }} </td>
                            <td class="px-5 py-2 whitespace-nowrap text-center"> {{ $holiday->start_date?->format('d F Y') }}</td>
                            <td class="px-5 py-2 whitespace-nowrap text-center"> {{ $holiday->end_date?->format('d F Y') }}</td>

                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        @endif
        
    </div>

</x-app-layout>
