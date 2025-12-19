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
                        <a href="{{route('web.incident.index')}}" target="_blank">
                            <img src="{{url('incident.png')}}" alt="Image" style='height: 100%; width: 100%; object-fit: contain'/>
                        </a>
                    </div>
                   
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900" style="height: 250px">
                        <a href="{{route('web.incident.generate_duedate')}}" target="_blank">
                            <img src="{{url('stimulate_due_date.png')}}" alt="Image" style='height: 100%; width: 100%; object-fit: contain'/>
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900" style="height: 250px">
                        <a href="{{route('web.logs')}}" target="_blank">
                            <img src="{{url('logs.png')}}" alt="Image" style='height: 100%; width: 100%; object-fit: contain'/>
                        </a>
                    </div>
                </div>

                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900" style="height: 250px">
                        <a href="{{route('web.queue.index')}}" target="_blank">
                            <img src="{{url('logs.png')}}" alt="Image" style='height: 100%; width: 100%; object-fit: contain'/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
var LHCChatOptions = {}; 
LHCChatOptions.attr_prefill = new Array();  
LHCChatOptions.attr_prefill.push({'name':'email','value':'amina@gmail.com','hidden':true});  
LHCChatOptions.attr_prefill.push({'name':'phone','value':'123456789'});  
LHCChatOptions.attr_prefill.push({'name':'username','value':'Amina'}); 
LHCChatOptions.attr_prefill.push({'name':'question','value':'Default user message'});  
LHCChatOptions.attr_prefill.push({'name':'token','value':'abc1234'}); 
	
var LHC_API = LHC_API||{};
LHC_API.args = {mode:'widget',lhc_base_url:'http://172.22.50.110/lhc/index.php/',wheight:450,wwidth:350,pheight:520,pwidth:500,leaveamessage:true,department:["1"],theme:[1],check_messages:false};
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.setAttribute('crossorigin','anonymous'); po.async = true;
var date = new Date();po.src = 'http://172.22.50.110/lhc/design/defaulttheme/js/widgetv2/index.js?'+(""+date.getFullYear() + date.getMonth() + date.getDate());
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
		
</script>


</x-app-layout>
